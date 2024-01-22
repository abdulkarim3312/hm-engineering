<?php

namespace App\Http\Controllers;

use App\FlatSalesOrder;
use App\Floor;
use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Branch;
use App\Model\Cash;
use App\Model\Cheeque;
use App\Model\Client;
use App\Model\DeliveryLog;
use App\Model\Flat;
use App\Model\MobileBanking;
use App\Model\Project;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrder;
use App\Model\PurchaseProduct;
use App\Model\SaleInventoryLog;
use App\Model\SaleInventory;
use App\Model\SalePayment;
use App\Model\SaleProduct;
use App\Model\SaleProductStock;
use App\Model\SalesOrder;
use App\Model\ScrapSaleOrderProduct;
use App\Model\ScrapSalePayment;
use App\Model\ScrapSalesOrder;
use App\Model\TransactionLog;
use App\Model\Warehouse;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;

class SaleController extends Controller
{
    public function salesOrder() {
        $clients = Client::where('status', 1)->where('type',1)->orderBy('name')->get();
        $projects = Project::where('status', 1)->orderBy('name')->get();
        return view('sale.sales_order.create', compact('clients','projects'));
    }
    public function scrapSaleCreate(){
        $clients = Client::where('status', 1)
            ->where('type', 1)
            ->orderBy('name')
            ->get();
        $projects = Project::where('status', 1)
            ->get();
        $project = Project::where('status', 1)
            ->first();
        $products = PurchaseProduct::where('status',1)->get();

        return view('sale.scrap_sale.create', compact('clients','projects',
        'products', 'project'));

    }
    public function scrapSalePost(Request $request){
        // dd($request->all());

        $rules = [
            'financial_year' => 'required',
            'payment_type' => 'required',
            'account' => 'required',
            'note' => 'nullable|max:255',
            'project.*' => 'required',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:0',
            'unit_price.*' => 'required|numeric|min:0',
            'date' => 'required'
        ];

        if ($request->payment_type == 1) {
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        if($request->paid > 0){
            $rules['financial_year'] = 'required';
            $rules['payment_type'] = 'required';
            $rules['account'] = 'required';
        }

        $request->validate($rules);

        $available = true;
        $message = '';
        $counter = 0;

        foreach ($request->project as $reqProject) {
            $purchaseInventory = PurchaseInventory::where('project_id',$reqProject)
                ->where('purchase_product_id', $request->product[$counter])
                ->where('scrap_status', 2)
                ->first();

            $product = PurchaseProduct::where('id',$request->product[$counter])->first();

            if ($purchaseInventory){
                if ($purchaseInventory->quantity > $request->quantity) {
                    $available = false;
                    $message = 'Insufficient ' . $purchaseInventory->product->name;
                }
            }else{
                $available = false;
                $message = 'Insufficient ' . $product->name;
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $count = 0;
        $order = new ScrapSalesOrder();
        $order->order_no = random_int(1000000,9999999);
        $order->project_id = $request->project[$count];
        $order->client_id = $request->client;
        $order->date = $request->date;
        $order->discount = $request->discount;
        $order->total = 0;
        $order->paid = $request->paid;
        $order->due = 0;
        $order->note = $request->note;
        $order->save();

        $counter = 0;
        $total = 0;

        foreach ($request->project as $reqProject) {

            $purchaseInventory = PurchaseInventory::where('project_id',$reqProject)
                ->where('purchase_product_id', $request->product[$counter])
                ->where('scrap_status', 2)
                ->first();

             $purchaseInventory->decrement('quantity',$request->quantity[$counter]);

            ScrapSaleOrderProduct::create([
                'scrap_sales_order_id' => $order->id,
                'project_id' => $reqProject,
                'purchase_product_id' => $request->product[$counter],
                'quantity' => $request->quantity[$counter],
                'unit_price' => $request->unit_price[$counter],
                'total' => $request->quantity[$counter] * $request->unit_price[$counter],
            ]);

             $total += $request->quantity[$counter] * $request->unit_price[$counter];

            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->purchase_product_id = $request->product[$counter];
            $inventoryLog->project_id =  $request->project[$counter];
            $inventoryLog->type = 9;//scrap Sale
            $inventoryLog->date = $request->date;
            $inventoryLog->quantity = $request->quantity[$counter];
            $inventoryLog->unit_price = $request->unit_price[$counter];
            $inventoryLog->scrap_sales_order_id = $order->id;
            $inventoryLog->save();
            $counter++;
        }

        $order->total = $total;
        $order->due = $total - ($request->discount + $request->paid);
        $order->save();

        $client = Client::find($request->client);

        // Sales Payment
        if ($request->paid > 0) {
            //create dynamic voucher no process start
            $transactionType = 1;
            $financialYear = $request->financial_year;
            $cashBankAccountHeadId = $request->account;
            $payType = $request->payment_type;
            $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
            //create dynamic voucher no process end
            $receiptPaymentNoExplode = explode("-",$voucherNo);
            $receiptPaymentNoSl = $receiptPaymentNoExplode[1];
            $receiptPayment = new ReceiptPayment();
            $project_id = $request->project;
            $data = implode($project_id);
            $receiptPayment->project_id = $data;
            $receiptPayment->receipt_payment_no = $voucherNo;
            $receiptPayment->financial_year = financialYear($request->financial_year);
            $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
            $receiptPayment->transaction_type = 1;
            $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

            $receiptPayment->account_head_id = $request->account;
            $receiptPayment->cheque_no = $request->cheque_no;
            if ($request->payment_type == 1){
                $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
                $receiptPayment->issuing_bank_name = $request->issuing_bank_name;
                $receiptPayment->issuing_branch_name = $request->issuing_branch_name;
            }
            $receiptPayment->client_id = 0;
            $receiptPayment->customer_id = 0;
            $receiptPayment->sub_total = $request->paid;
            $receiptPayment->net_amount = $request->paid;
            $receiptPayment->scrap_sales_order_id = $order->id;
            $receiptPayment->notes = $request->note;
            $receiptPayment->save();

            //Bank/Cash Debit
            // $log = new TransactionLog();
            // $log->notes = $request->note;
            // $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
            // $log->receipt_payment_sl = $receiptPaymentNoSl;
            // $log->financial_year = $receiptPayment->financial_year;
            // $log->client_id = $receiptPayment->client_id;
            // $log->date = $receiptPayment->date;
            // $log->receipt_payment_id = $receiptPayment->id;
            // if($request->payment_type == 1){
            //     $log->cheque_no = $request->cheque_no;
            //     $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');

            // }
            // $log->transaction_type = 1;//Bank debit,Cash debit

            // $log->payment_type = $request->payment_type;
            // $log->account_head_id = $request->account;
            // $log->amount = $receiptPayment->net_amount;
            // $log->notes = $receiptPayment->notes;
            // $log->scrap_sales_order_id = $order->id;
            // $log->save();

            $receiptPaymentDetail = new ReceiptPaymentDetail();
            $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
            $receiptPaymentDetail->account_head_id = 11;
            $receiptPaymentDetail->amount = $request->paid;
            $receiptPaymentDetail->net_amount = $request->paid;
            $receiptPaymentDetail->save();

            //Credit Head Amount
            $log = new TransactionLog();
            $log->notes = $request->note;
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $receiptPaymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->client_id = 0;
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_id = $receiptPayment->id;
            $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
            $log->payment_type = $request->payment_type;
            if($request->payment_type == 1){
                $log->cheque_no = $request->cheque_no;
                $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
            }
            $log->transaction_type = 2;//Account Head Credit
            $project_id = $request->project;
            $data = implode($project_id);
            $log->project_id = $data;
            // dd($log->project_id);
            $log->account_head_id = 11;
            $log->scrap_sales_order_id = $order->id;
            $log->amount = $request->paid;
            $log->notes = $request->note;
            $log->save();
        }
        return redirect()->route('scrap_sale_receipt.details', ['order' => $order->id]);

    }
    public function salesOrderPost(Request $request) {
        // dd($request->all());
        $rules = [
            'client' => 'required|integer',
            'date' => 'required|date',
            'note' => 'nullable|max:255',
            'project' => 'required',
            'flat' => 'required',
            'floor' => 'required',
            'price' => 'required|numeric|min:0',
            'car' => 'nullable|numeric|min:0',
            'other' => 'nullable|numeric|min:0',
            'uty' => 'nullable|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
        ];


        if ($request->payment_type == 1) {
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        if($request->paid > 0){
            $rules['financial_year'] = 'required';
            $rules['payment_type'] = 'required';
            $rules['account'] = 'required';
        }

        $request->validate($rules);

        $available = true;
        $message = '';

        if ($request->flat) {
            $flat = Flat::find($request->flat);
                if ($flat->status == 0) {
                    $available = false;
                    $message = 'Insufficient ' . $flat->name;
                }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $order = new SalesOrder();
        $order->order_no = random_int(1000000,9999999);
        $order->client_id = $request->client;
        $order->project_id = $request->project;
        $order->floor_id = $request->floor;
        $order->flat_id = $request->flat;
        $order->date = $request->date;
        $order->note = $request->note;
        $order->vat_percent = $request->vat;
        $order->payment_step = 0;
        $order->save();

        if($request->flat){

            SaleInventory::where('flat_id',$request->flat)
                ->update([
                'status'=> 2,
            ]);

            $flat = Flat::find($request->flat);

            $order->flats()->attach($request->flat, [
                'flat_name' => $flat->name,
                'price' => $request->price,
                'car' => $request->car ?? 0,
                'utility' => $request->uty ?? 0,
                'other' => $request->other ?? 0,
                'total' => $request->price+($request->car ?? 0)+($request->uty ?? 0)+($request->other ?? 0),
            ]);

        }

        $total =  $request->price + $request->car + $request->uty + $request->other;

        $client = Client::find($request->client);

        $order->discount = $request->discount;
        $vat = ($total * $request->vat) / 100;
        $order->vat = $vat;
        $totalAndVat = $total + $vat;
        $order->total = $totalAndVat;

        if ($request->closing_balance && $request->payment) {
            $order->closing_amount = $request->payment ?? 0;
            $order->paid = $request->payment;
            $order->due = ($totalAndVat - $request->discount) - $request->payment;
            $order->closing_balance = 1;
            // Client update
            $client->increment('total', ($totalAndVat - $request->discount));
            $client->increment('paid', $request->payment);
            $client->due = $client->total - $client->paid;
            $client->save();
        }else {
            $order->due = $totalAndVat - $request->discount;
            $client->increment('total',($totalAndVat - $request->discount));
            $client->increment('due',($totalAndVat - $request->discount));
        }

        $order->save();

        // Sales Payment
        if ($request->paid > 0) {

            $order->increment('paid', $request->paid);
            $order->decrement('due', $request->paid);
            $client->increment('paid', $request->paid);
            $client->decrement('due', $request->paid);
            $order->payment_step = 1;
            $order->save();

            //create dynamic voucher no process start
            $transactionType = 1;
            $financialYear = $request->financial_year;
            $cashBankAccountHeadId = $request->account;
            $payType = $request->payment_type;
            $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
            //create dynamic voucher no process end
            $receiptPaymentNoExplode = explode("-",$voucherNo);

            $receiptPaymentNoSl = $receiptPaymentNoExplode[1];
            $receiptPayment = new ReceiptPayment();
            $receiptPayment->project_id = $request->project;
            $receiptPayment->receipt_payment_no = $voucherNo;
            $receiptPayment->financial_year = financialYear($request->financial_year);
            $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
            $receiptPayment->transaction_type = 1;
            $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

            $receiptPayment->account_head_id = $request->account;
            $receiptPayment->cheque_no = $request->cheque_no;
            if ($request->payment_type == 1){
                $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
                $receiptPayment->issuing_bank_name = $request->issuing_bank_name;
                $receiptPayment->issuing_branch_name = $request->issuing_branch_name;
            }
            $receiptPayment->client_id = $client->id;
            $receiptPayment->customer_id = $client->id_no;
            $receiptPayment->sub_total = $request->paid;
            $receiptPayment->net_amount = $request->paid;
            $receiptPayment->sales_order_id = $order->id;
            $receiptPayment->notes = $request->note;
            $receiptPayment->save();

            //Bank/Cash Debit
            // $log = new TransactionLog();
            // $log->notes = $request->note;
            // $log->project_id = $request->project;
            // $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
            // $log->receipt_payment_sl = $receiptPaymentNoSl;
            // $log->financial_year = $receiptPayment->financial_year;
            // $log->client_id = $receiptPayment->client_id;
            // $log->date = $receiptPayment->date;
            // $log->receipt_payment_id = $receiptPayment->id;
            // if($request->payment_type == 1){
            //     $log->cheque_no = $request->cheque_no;
            //     $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');

            // }
            // $log->transaction_type = 1;//Bank debit,Cash debit

            // $log->payment_type = $request->payment_type;
            // $log->account_head_id = $request->account;
            // $log->amount = $receiptPayment->net_amount;
            // $log->notes = $receiptPayment->notes;
            // $log->sales_order_id = $order->id;
            // $log->save();

            $receiptPaymentDetail = new ReceiptPaymentDetail();
            $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
            $receiptPaymentDetail->account_head_id = $request->account;
            $receiptPaymentDetail->amount = $request->paid;
            $receiptPaymentDetail->net_amount = $request->paid;
            $receiptPaymentDetail->save();

            // Credit Head Amount
            $log = new TransactionLog();
            $log->notes = $request->note;
            $log->project_id = $request->project;
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $receiptPaymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->client_id = $client->id;
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_id = $receiptPayment->id;
            $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
            $log->payment_type = $request->payment_type;
            if($request->payment_type == 1){
                $log->cheque_no = $request->cheque_no;
                $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
            }
            $log->transaction_type = 2;//Account Head Credit
            $log->account_head_id = 9;
            $log->sales_order_id = $order->id;
            $log->amount = $request->paid;
            $log->notes = $request->note;
            $log->save();

        }

        return redirect()->route('sale_receipt.details', ['order' => $order->id]);

    }

    public function salesOrderEdit(SalesOrder $order){

        $clients = Client::where('status', 1)->orderBy('name')->get();
        $projects = Project::where('status', 1)->orderBy('name')->get();

        return view('sale.sales_order.edit', compact('clients','projects','order'));
    }

    public function salesOrderEditPost(SalesOrder $order,Request $request)
    {
        $rules = [
            'client' => 'required|integer',
            'date' => 'required|date',
            'note' => 'nullable|max:255',
            'project' => 'required',
            'flat' => 'required',
            'floor' => 'required',
            'price' => 'required|numeric|min:0',
            'car' => 'nullable|numeric|min:0',
            'other' => 'nullable|numeric|min:0',
            'uty' => 'nullable|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
        ];


        if ($request->payment_type == 1) {
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        if($request->paid > 0){
            $rules['financial_year'] = 'required';
            $rules['payment_type'] = 'required';
            $rules['account'] = 'required';
        }

        $request->validate($rules);

        $available = true;
        $message = '';

        if ($request->flat != $order->flat_id) {
            $flat = Flat::find($request->flat);
            $flatCheck = SaleInventory::where('flat_id',$request->flat)->first();
            if ($flat->status == 0 || $flatCheck->status == 2) {
                $available = false;
                $message = 'Insufficient ' . $flat->name;
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        //dd($order->flat_id);

        if ($request->flat != $order->flat_id) {

            SaleInventory::where('flat_id', $order->flat_id)
                ->update([
                    'status' => 1,
                ]);
        }

        $flat = Flat::find($request->flat);

        SalesOrder::where('id',$order->id)
            ->update([
                'client_id'=> $request->client,
                'project_id'=> $request->project,
                'floor_id'=> $request->floor,
                'flat_id'=> $flat->id,
                'date'=> $request->date,
                'note'=> $request->note,
                'vat_percent'=> $request->vat,
                'payment_step'=> 0,
            ]);

            SaleInventory::where('flat_id',$request->flat)
                ->update([
                    'status'=> 2,
                ]);


            DB::table('flat_sales_order')
                ->where('sales_order_id', $order->id)
                ->update([
                    'flat_id' => $flat->id,
                    'flat_name' => $flat->name,
                    'price' => $request->price,
                    'car' => $request->car ?? 0,
                    'utility' => $request->uty ?? 0,
                    'other' => $request->other ?? 0,
                    'total' => $request->price+($request->car ?? 0)+($request->uty ?? 0)+($request->other ?? 0),
                ]);

        $total =  $request->price + $request->car + $request->uty + $request->other;

        $client = Client::find($request->client);

        $order->discount = $request->discount;
        $vat = ($total * $request->vat) / 100;
        $order->vat = $vat;
        $totalAndVat = $total + $vat;
        $order->total = $totalAndVat;

        $newDue = $totalAndVat - ($request->discount + $order->paid);

        if ($newDue > $order->due){
            $increment = $newDue - $order->due;
            $client->increment('total', $increment);
            $client->increment('due', $increment);

        }elseif ($newDue < $order->due){
            $decrement = $order->due - $newDue;
            $client->decrement('total', $decrement);
            $client->decrement('due', $decrement);
        }else{

        }

        $client->save();
        $order->due = $request->due_total??0;
        $order->save();

        return redirect()->route('sale_receipt.details', ['order' => $order->id]);


    }



    public function salesOrderDelete(Request $request){

        $order = SalesOrder::find($request->orderId);
        //$id=$order->flats[0]->pivot->flat_id;
        SaleInventory::where('flat_id',$order->flat_id)->update(['status'=> 1]);
        DB::table('flat_sales_order')->where('sales_order_id',$order->id)->delete();
        $client = Client::find($order->client_id);
        $client->decrement('total', $order->total);
        //$client->decrement('paid', $order->paid);
        $client->decrement('due', $order->total);
        $receiptPayments = ReceiptPayment::where('sales_order_id',$order->id)->get();
        foreach ($receiptPayments as $receiptPayment){
            ReceiptPaymentDetail::where('receipt_payment_id',$receiptPayment->id)->delete();
            $receiptPayment->delete();
        }
        TransactionLog::where('sales_order_id',$order->id)->delete();
        $order->delete();


        return response()->json(['success' => true, 'message' => 'Sales Order Deleted.', 'redirect_url' => route('sale_receipt.all')]);

    }
    public function getFlat(Request $request) {

        $availableIds = SaleInventory::where('status', 1)->pluck('id')->toArray();
        // dd($availableIds);
        // $item = Flat::where('floor_id', $request->floorId)->get();
        // dd($item);
        // $flats = Flat::whereIn('id', $availableIds)
        //         ->where('floor_id', $request->floorId)
        //         ->orderBy('name')
        //         ->get()
        //         ->toArray();
        $flats = Flat::where('floor_id', $request->floorId)
                ->orderBy('name')
                ->get()
                ->toArray();
            // dd($flats);
        return response()->json($flats);
    }

    public function getAllFlat(Request $request){

        $flats = SaleInventory::where('project_id',$request->projectId)
            ->with('flat')
            ->where('floor_id',$request->floorId)
            ->get()
            ->toArray();

        //dd($flats);

        return response()->json($flats);
    }

    public function scrapSaleReceipt(){
        return view('sale.scrap_receipt.all');
    }

    public function scrapSaleReceiptDetails(ScrapSalesOrder $order){
        return view('sale.scrap_receipt.details', compact('order'));
    }
    public function saleInventoryFloorWiseView(Request $request){

        $floorWiseProjects = [];
        $projectName = '';

        $projects = SaleInventory::select('project_id')
//            ->orderBy('floor_id')
//            ->orderBy('flat_id')
            ->groupBy('project_id')
            ->with('project')
            ->get();

        if ($request->project){
            $floorWiseProjects = SaleInventory::where('project_id',$request->project)
//                ->orderBy('floor_id')
//                ->orderBy('flat_id')
                ->select('floor_id')
                ->groupBy('floor_id')
                ->with('project')
                ->get();

            $projectName = SaleInventory::where('project_id',$request->project)
//                ->orderBy('floor_id')
//                ->orderBy('flat_id')
                ->with('project')
                ->first();
        }

        return view('sale.inventory.floor_wise_view', compact('projects','floorWiseProjects','projectName'));
    }

    public function scrapSaleReceiptPrint(ScrapSalesOrder $order) {
        return view('sale.scrap_receipt.print', compact('order'));
    }

    public function saleReceipt() {
        $clients = Client::where('status',1)->get();
        $projects = Project::where('status',1)->get();
        return view('sale.receipt.all',compact('clients','projects'));
    }

    public function saleReceiptDetails(SalesOrder $order) {
        return view('sale.receipt.details', compact('order'));
    }

    public function saleReceiptPrint(SalesOrder $order) {
        return view('sale.receipt.print', compact('order'));
    }

    public function salePaymentDetails(SalePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('sale.receipt.payment_details', compact('payment'));
    }
    public function transactionDetails(SalesOrder $order){
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        return view('sale.receipt.details', compact('order'));
    }

    public function salePaymentPrint(SalePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('sale.receipt.payment_print', compact('payment'));
    }
    public function saleMoneyReceiptPrint(SalePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('sale.receipt.money_receipt', compact('payment'));
    }

    public function projectWiseClient(Request $request) {
        $projects = Project::where('status',1)->get();
        $salepayment = null;
        $customers = [];
        $project_single  = null;

        if ($request->project !=null && $request->start !=null && $request->end !=null) {
            $project_single = Project::find($request->project);
            $customers = SalesOrder::where('project_id', $request->project)->whereBetween('date', [$request->start, $request->end])->get();
            $salepayment = Project::where('id',$request->project)->first();
        }elseif($request->project !=null && $request->start ==null && $request->end ==null) {
            $customers = SalesOrder::where('project_id', $request->project)->get();
            $salepayment = Project::where('id',$request->project)->first();
        }
        return view('sale.project_wise_client.all',compact('customers','projects','salepayment','project_single'));
    }

    public function clientPayment() {
        $projects = Project::where('status',1)->get();
        return view('sale.client_payment.all',compact('projects'));
    }

    public function salesDeliveryLog(Request $request)
    {
        $rules = [
            'date' => 'required',
        ];

        $order=SalesOrder::find($request->sales_id);
        $rules['quantity'] = 'required|numeric|min:0|max:'.$order->remain_quantity;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $log = new DeliveryLog();
        $log->sales_order_id=$request->sales_id;
        $log->quantity=$request->quantity;
        $log->date=$request->date;
        $log->save();


        $order->decrement('remain_quantity',$request->quantity);
        $order->increment('delivery_quantity',$request->quantity);

        return response()->json(['success' => true, 'message' => 'Delivery Completed.', 'redirect_url' => route('sale_receipt.all')]);

    }

    public function clientPaymentDelete(Request $request)
    {

       $payment = SalePayment::where('id',$request->paymentId)->first();

       $log = TransactionLog::where('sale_payment_id',$request->paymentId)->first();
       if ($log->transaction_method == 1) {
            Cash::first()->decrement('amount',$log->amount);
       }elseif ($log->transaction_method == 4) {
           Cheeque::first()->decrement('amount',$log->amount);
       }
       $order = SalesOrder::where('id',$payment->sales_order_id)->first();

        $order->decrement('paid',$log->amount);
        $order->increment('due',$log->amount);

        $log->delete();
        $payment->delete();
        return response()->json(['success' => true, 'message' => 'Client received has been deleted.']);

    }
    public function makePayment(Request $request) {
        // dd($request->all());
        $rules = [
            'financial_year' => 'required',
            'order_no' => 'required',
            'payment_type' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'next_date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ];
//        if ($request->payment_step_no < 4){
//            $rules['amount'] = 'required|numeric|min:1';
//        }

        if ($request->payment_type == 1) {
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
        }
//        if ($request->payment_step_no == 3){
//            $rules['installments'] = 'required|integer|min:1';
//        }
        $client = Client::find($request->client_id);


        if ($request->order_no != '') {
            $order = SalesOrder::find($request->order_no);
//            if ($request->payment_step_no < 3){
            if ($order){
                $rules['amount'] = 'required|numeric|min:0|max:' . $order->due;
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = SalesOrder::find($request->order_no);
        //$order->payment_step = $request->payment_step_no == 4 ? 4 : $request->payment_step_no;
        $order->save();

        $payAmount = $request->amount ?? 0;

//        if ($request->payment_step_no == 4){
//            $payAmount = $order->per_installment_amount;
//            $order->increment('last_installment',1);
//        }


        $order_flat = $order->flats()->first();

        $order->increment('paid', $payAmount);
        $order->decrement('due', $payAmount);
        $client->increment('paid', $payAmount);
        $client->decrement('due',$payAmount);

//        if ($request->payment_step_no == 3){
//            $order->total_installment = $request->installments;
//            $order->per_installment_amount = round($order->due / $request->installments,2);
//            $order->save();
//        }

        //create dynamic voucher no process start
        $transactionType = 1;
        $financialYear = $request->financial_year;
        $cashBankAccountHeadId = $request->account;
        $payType = $request->payment_type;
        $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
        //create dynamic voucher no process end
        $receiptPaymentNoExplode = explode("-",$voucherNo);

        $receiptPaymentNoSl = $receiptPaymentNoExplode[1];
        $receiptPayment = new ReceiptPayment();

        $receiptPayment->project_id = $order->project_id;
        $receiptPayment->payment_step = $order->payment_step;
        $receiptPayment->installment_no = $order->payment_step == 4 ? $order->last_installment : null;

        $receiptPayment->receipt_payment_no = $voucherNo;
        $receiptPayment->financial_year = financialYear($request->financial_year);
        $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $receiptPayment->transaction_type = 1;
        $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

        $receiptPayment->account_head_id = $request->account;
        $receiptPayment->cheque_no = $request->cheque_no;
        if ($request->payment_type == 1){
            $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
            $receiptPayment->issuing_bank_name = $request->issuing_bank_name;
            $receiptPayment->issuing_branch_name = $request->issuing_branch_name;
        }
        $receiptPayment->client_id = $client->id;
        $receiptPayment->customer_id = $client->id_no;
        $receiptPayment->sub_total = $payAmount;
        $receiptPayment->net_amount = $payAmount;
        $receiptPayment->sales_order_id = $order->id;
        $receiptPayment->notes = $request->note;
        $receiptPayment->save();

        //Bank/Cash Debit
        // $log = new TransactionLog();
        // $log->notes = $request->note;
        // $log->project_id = $order->project_id;
        // $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
        // $log->receipt_payment_sl = $receiptPaymentNoSl;
        // $log->financial_year = $receiptPayment->financial_year;
        // $log->client_id = $receiptPayment->client_id;
        // $log->date = $receiptPayment->date;
        // $log->receipt_payment_id = $receiptPayment->id;
        // if($request->payment_type == 1){
        //     $log->cheque_no = $request->cheque_no;
        //     $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');

        // }
        // $log->transaction_type = 1;//Bank debit,Cash debit

        // $log->payment_type = $request->payment_type;
        // $log->account_head_id = $request->account;
        // $log->amount = $receiptPayment->net_amount;
        // $log->notes = $receiptPayment->notes;
        // $log->sales_order_id = $order->id;
        // $log->save();

        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->account_head_id = $request->account;
        $receiptPaymentDetail->amount = $payAmount;
        $receiptPaymentDetail->net_amount = $payAmount;
        $receiptPaymentDetail->save();

        //Credit Head Amount
        $log = new TransactionLog();
        $log->notes = $request->note;
        $log->project_id = $order->project_id;
        $log->receipt_payment_no = $voucherNo;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = financialYear($request->financial_year);
        $log->client_id = $client->id;
        $log->date = Carbon::parse($request->date)->format('Y-m-d');
        $log->receipt_payment_id = $receiptPayment->id;
        $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
        $log->payment_type = $request->payment_type;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $log->transaction_type = 2;//Account Head Credit
        $log->account_head_id = $request->account;
        $log->sales_order_id = $order->id;
        $log->amount = $payAmount;
        $log->notes = $request->note;
        $log->save();

     return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('receipt_details', ['receiptPayment' => $receiptPayment->id])]);

    }
    public function customerMakeRefund(Request $request) {
        $rules = [
            'financial_year' => 'required',
            'order' => 'required',
            'payment_type' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == 1) {
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_no'] = 'required|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        if ($request->order != '') {
            $order = SalesOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:' . $order->refund;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = SalesOrder::find($request->order);

        $order_flat= $order->flats()->first();

       $order->decrement('refund', $request->amount);

        //create dynamic voucher no process start
        $transactionType = 2;
        $financialYear = $request->financial_year;
        $cashBankAccountHeadId = $request->account;
        $payType = $request->payment_type;
        $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
        //create dynamic voucher no process end
        $receiptPaymentNoExplode = explode("-",$voucherNo);

        $receiptPaymentNoSl = $receiptPaymentNoExplode[1];
        $receiptPayment = new ReceiptPayment();

        $receiptPayment->project_id = $order->project_id;
        $receiptPayment->receipt_payment_no = $voucherNo;
        $receiptPayment->financial_year = financialYear($request->financial_year);
        $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $receiptPayment->transaction_type = 2;
        $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

        $receiptPayment->account_head_id = $request->account;
        $receiptPayment->cheque_no = $request->cheque_no;
        if ($request->payment_type == 1){
            $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $receiptPayment->client_id = $order->client_id;
        $receiptPayment->customer_id = $order->client->id_no ?? '';
        $receiptPayment->sub_total = $request->amount;
        $receiptPayment->net_amount = $request->amount;
        $receiptPayment->sales_order_id = $order->id;
        $receiptPayment->notes = $request->note;
        $receiptPayment->save();

        //Bank/Cash Credit
        $log = new TransactionLog();
        $log->notes = $request->note;
        $log->project_id = $order->project_id;
        $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = $receiptPayment->financial_year;
        $log->client_id = $receiptPayment->client_id;
        $log->date = $receiptPayment->date;
        $log->receipt_payment_id = $receiptPayment->id;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');

        }
        $log->transaction_type = 2;//Bank Credit,Cash Credit

        $log->payment_type = $request->payment_type;
        $log->account_head_id = $request->account;
        $log->amount = $receiptPayment->net_amount;
        $log->notes = $receiptPayment->notes;
        $log->sales_order_id = $order->id;
        $log->save();

        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->account_head_id = 10;
        $receiptPaymentDetail->amount = $request->amount;
        $receiptPaymentDetail->net_amount = $request->amount;
        $receiptPaymentDetail->save();

        //Debit Head Amount
        $log = new TransactionLog();
        $log->notes = $request->note;
        $log->project_id = $order->project_id;
        $log->receipt_payment_no = $voucherNo;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = financialYear($request->financial_year);
        $log->client_id = $receiptPayment->client_id;
        $log->date = Carbon::parse($request->date)->format('Y-m-d');
        $log->receipt_payment_id = $receiptPayment->id;
        $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
        $log->payment_type = $request->payment_type;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $log->transaction_type = 1;//Account Head debit
        $log->account_head_id = 10;
        $log->sales_order_id = $order->id;
        $log->amount = $request->amount;
        $log->notes = $request->note;
        $log->save();

        return response()->json(['success' => true, 'message' => 'Refund has been completed.', 'redirect_url' => route('voucher_details', ['receiptPayment' => $receiptPayment->id])]);
    }

    public function stockIndex() {
        return view('sale.add_to_stock.all');
    }

    public function stockAdd() {
        $products = SaleProduct::where('status', 1)
            ->orderBy('name')->get();

        return view('sale.add_to_stock.add', compact('products'));
    }

    public function stockAddPost(Request $request) {
        $request->validate([
            'product' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'date|date',
            'note' => 'nullable|string|max:255',
        ]);


        $inventoryLog = new SaleInventoryLog();
        $inventoryLog->sale_product_id = $request->product;
        $inventoryLog->type = 1;
        $inventoryLog->date = $request->date;
        $inventoryLog->quantity = $request->amount;
        $inventoryLog->note = $request->note;
        $inventoryLog->save();

        $utilize = new SaleProductStock();
        $utilize->sale_product_id = $request->product;
        $utilize->amount = $request->amount;
        $utilize->date = $request->date;
        $utilize->note = $request->note;
        $utilize->sale_inventory_log_id = $inventoryLog->id;
        $utilize->save();

        SaleProduct::where('id', $request->product)
            ->increment('quantity', $request->amount);

        return redirect()->route('sale_product.stock.all')->with('message', 'Add to stock successfully.');
    }

    public function saleInventory() {
        $projects = Project::get();
        return view('sale.inventory.all',compact('projects'));
    }

    public function saleInventoryDetails(SaleProduct $product) {
        return view('sale.inventory.details', compact('product'));
    }

    public function orderDelivery(Request $request) {
        $available = true;
        $message = '';

        $order = SalesOrder::where('id', $request->orderId)->with('products')->first();

        foreach ($order->products as $product) {
            if ($product->pivot->quantity > $product->quantity) {
                $available = false;
                $message = 'Insufficient '.$product->name;
                break;
            }
        }

        if ($available) {
            foreach ($order->products as $product) {
                $product->decrement('quantity', $product->pivot->quantity);
            }

            $order->delivery_at = $request->date;
            $order->save();

            $inventoryLog = new SaleInventoryLog();
            $inventoryLog->sale_product_id = $product->id;
            $inventoryLog->type = 2;
            $inventoryLog->date = $request->date;
            $inventoryLog->quantity = $product->pivot->quantity;
            $inventoryLog->unit_price = $product->pivot->unit_price;
            $inventoryLog->client_id = $order->client_id;
            $inventoryLog->save();

            return response()->json(['success' => true, 'message' => 'Order has been delivered.']);
        } else {
            return response()->json(['success' => false, 'message' => $message]);
        }

    }

    public function clientPaymentGetOrders(Request $request) {
        $orders = SalesOrder::where('client_id', $request->clientId)
            ->where('due', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function customerPaymentGetRefundOrders(Request $request) {
        $orders = SalesOrder::where('client_id', $request->customerId)
            ->where('refund', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }
    public function saleDeliveryInfo(Request $request)
    {
        $orders = SalesOrder::find($request->orderId);

        return response()->json($orders);
    }

    public function clientPaymentOrderDetails(Request $request) {
        $order = SalesOrder::where('id', $request->orderId)
            ->first()->toArray();

        return response()->json($order);
    }
    public function clientOrderPaymentStep(Request $request) {
        $order = SalesOrder::where('id', $request->orderId)
            ->first();
        $per_installment_amount = 0;

        if ($order->payment_step == 0){
            $response = 'Booking Money';
            $payment_step_status = 1;
        }elseif ($order->payment_step == 1){
            $response = 'Down Payment-1';
            $payment_step_status = 2;
        }elseif ($order->payment_step == 2){
            $response = 'Down Payment-2';
            $payment_step_status = 3;
        }elseif ($order->payment_step == 3){
            $installment = $order->last_installment + 1;
            $response = 'Installment-'.$installment;
            $per_installment_amount = $order->per_installment_amount;
            $payment_step_status = 4;
        }elseif ($order->payment_step == 4){
            $installment = $order->last_installment + 1;
            $response = 'Installment-'.$installment;
            $per_installment_amount = $order->per_installment_amount;
            $payment_step_status = 4;
        }

        $data = [
            'payment_step'=>$response,
            'per_installment_amount'=>$per_installment_amount,
            'payment_step_status'=>$payment_step_status,
        ];

        return response()->json($data);
    }

    public function clientPaymentDatatable(Request $request) {

        $query = Client::where('type',1)
            ->with('saleOrder');

        if ($request->has('project_name') && $request->project_name !== 'all') {
            //$query->where('company_name', $request->company_name);
            $query->saleOrder->project->name??'';
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function(Client $client) {
                $btns = '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$client->id.'" data-name="'.$client->name.'">Pay</a> <a href="'.route('client_payment_details', ['client' => $client->id]).'" class="btn btn-primary btn-sm">Details</a>';
                if($client->refund > 0)
                    $btns .= ' <a class="btn btn-danger btn-sm btn-refund" role="button" data-id="'.$client->id.'" data-name="'.$client->name.'">Refund</a>';

                return $btns;
            })

            ->addColumn('project', function(Client $client) {
                return $client->saleOrder->project->name??'';
            })
            ->addColumn('paid', function(Client $client) {
                return ' '.number_format($client->order_paid, 2);
            })
            ->addColumn('due', function(Client $client) {
                return ' '.number_format($client->order_due, 2);
            })
            ->addColumn('total', function(Client $client) {
                return ' '.number_format($client->order_total, 2);
            })
            ->addColumn('refund', function(Client $client) {
                return ' '.number_format($client->refund, 2);
            })
            ->addColumn('discount', function(Client $client) {
                return ' '.number_format($client->order_discount, 2);
            })
            ->rawColumns(['action','project'])
            ->toJson();
    }

    public function clientPaymentDetails(Client $client)
    {
        $receipt_payment = ReceiptPayment::where('client_id',$client->id)->with('project')->first();
        return view('sale.client_payment.client_payment_details',compact('client','receipt_payment'));
    }

    public function saleReceiptDatatable() {
        $query = SalesOrder::with('client','project')->whereNotNull('project_id');

        return DataTables::eloquent($query)
            ->addColumn('client', function(SalesOrder $order) {
                return $order->client->name;
            })
            ->addColumn('action', function(SalesOrder $order) {
                //return '<a href="'.route('sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('sales_order_edit', ['order' => $order->id]).'" class="btn btn-info btn-sm">Edit</a>  <a role="button" data-id="'.$order->id.'" class="btn btn-danger btn-sm btn-delete">Delete</a>';
                if($order->paid == 0)
                    return '<a href="'.route('sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('sales_order_edit', ['order' => $order->id]).'" class="btn btn-info btn-sm">Edit</a>  <a role="button" data-id="'.$order->id.'" class="btn btn-danger btn-sm btn-delete">Delete</a>';
                else
                    return '<a href="'.route('sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('sales_order_edit', ['order' => $order->id]).'" class="btn btn-info btn-sm">Edit</a>';

            })
            ->editColumn('date', function(SalesOrder $order) {
                return $order->date;
            })
            ->editColumn('project', function(SalesOrder $order) {
                return $order->project->name??'';
            })
            ->editColumn('total', function(SalesOrder $order) {
                return ''.number_format($order->total, 2);
            })
            ->editColumn('paid', function(SalesOrder $order) {
                return ''.number_format($order->paid, 2);
            })
            ->editColumn('due', function(SalesOrder $order) {
                return ''.number_format($order->due, 2);
            })
            ->editColumn('discount', function(SalesOrder $order) {
                return ''.number_format($order->discount, 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function scrapSaleReceiptDatatable() {
        $query = ScrapSalesOrder::with('client');

        return DataTables::eloquent($query)
            ->addColumn('client', function(ScrapSalesOrder $order) {
                return $order->client->name ?? '';
            })
            ->addColumn('action', function(ScrapSalesOrder $order) {
                $btns = '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$order->id.'" data-name="'.$order->name.'">Pay</a> <a href="'.route('scrap_sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a>';

                // $btns= '<a href="'.route('scrap_sale_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a>';
                // $btns .= '<a href="'.route('scrap_sale_receipt.details', ['order' => $order->id]).'" class="btn btn-success btn-sm px-2" style="margin-left:5px;">Pay</a>';
                return $btns;

            })
            ->editColumn('date', function(ScrapSalesOrder $order) {
                return $order->date;
            })
            ->editColumn('total', function(ScrapSalesOrder $order) {
                return ''.number_format($order->total, 2);
            })
            ->editColumn('paid', function(ScrapSalesOrder $order) {
                return ''.number_format($order->paid, 2);
            })
            ->editColumn('due', function(ScrapSalesOrder $order) {
                return ''.number_format($order->due, 2);
            })
            ->editColumn('discount', function(ScrapSalesOrder $order) {
                return ''.number_format($order->discount, 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getScrapData(Request $request){
        $data = ScrapSalesOrder::with('products')->where('id', $request->clientId)->first();
        return response()->json($data);
    }

    public function scrapMakePayment(Request $request){
        // dd($request->all());
        $rules = [
            'amount' => 'required|numeric|min:1',
        ];
        $item = ScrapSalesOrder::find($request->client);
        $rules['amount'] = 'required|numeric|min:0|max:'.$item->due;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $item->decrement('due', $request->amount);
        $item->increment('paid', $request->amount);
        $order = ScrapSalesOrder::where('project_id', $request->projects_id)->first();
        // dd($order);
        // $rules = [
        //     'financial_year' => 'required',
        //     'order' => 'required',
        //     'project' => 'nullable',
        //     'payment_type' => 'required',
        //     'account' => 'required',
        //     'amount' => 'required|numeric|min:1',
        //     'date' => 'required|date',
        //     'note' => 'nullable|string|max:255',
        // ];

        // if ($request->payment_type == 1) {
        //     $rules['cheque_date'] = 'required';
        //     $rules['cheque_no'] = 'nullable|string|max:255';
        //     $rules['cheque_image'] = 'nullable|image';
        // }
        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        // }
        // $order =PurchaseOrder::find($request->order);
        // $supplier = Client::find($request->supplier);
        // $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;


        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        // }

        // $order = PurchaseOrder::find($request->order);

        // $supplier->decrement('due', $request->amount);
        // $supplier->decrement('paid', $request->amount);
        // $order->increment('paid', $request->amount);
        // $order->decrement('due', $request->amount);

        //create dynamic voucher no process start
        $transactionType = 1;
        $financialYear = $request->financial_year;
        $cashBankAccountHeadId = $request->account;
        $payType = $request->payment_type;
        $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
        //create dynamic voucher no process end
        $receiptPaymentNoExplode = explode("-",$voucherNo);

        $receiptPaymentNoSl = $receiptPaymentNoExplode[1];
        $receiptPayment = new ReceiptPayment();

        $receiptPayment->project_id = $order->project_id;
        $receiptPayment->receipt_payment_no = $voucherNo;
        $receiptPayment->financial_year = financialYear($request->financial_year);
        $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $receiptPayment->transaction_type = 1;
        $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

        $receiptPayment->account_head_id = $request->account;
        $receiptPayment->cheque_no = $request->cheque_no;
        if ($request->payment_type == 1){
            $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        // $receiptPayment->client_id = $supplier->id;
        // $receiptPayment->customer_id = $supplier->id_no;
        $receiptPayment->sub_total = $request->amount;
        $receiptPayment->net_amount = $request->amount;
        // $receiptPayment->purchase_order_id = $order->id;
        $receiptPayment->notes = $request->note;
        $receiptPayment->save();

        //Bank/Cash Credit
        // $log = new TransactionLog();
        // $log->notes = $request->note;
        // $log->project_id = $order->project_id;
        // $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
        // $log->receipt_payment_sl = $receiptPaymentNoSl;
        // $log->financial_year = $receiptPayment->financial_year;
        // $log->client_id = $receiptPayment->client_id;
        // $log->date = $receiptPayment->date;
        // $log->receipt_payment_id = $receiptPayment->id;

        // if($request->payment_type == 1){
        //     $log->cheque_no = $request->cheque_no;
        //     $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');

        // }
        // $log->transaction_type = 2;//Bank Credit,Cash credit

        // $log->payment_type = $request->payment_type;
        // $log->account_head_id = $request->account;
        // $log->amount = $receiptPayment->net_amount;
        // $log->notes = $receiptPayment->notes;
        // $log->purchase_order_id = $order->id;
        // $log->save();

        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->account_head_id = 12;
        $receiptPaymentDetail->account_head_id = $request->account;
        $receiptPaymentDetail->amount = $request->amount;
        $receiptPaymentDetail->net_amount = $request->amount;
        $receiptPaymentDetail->save();

        //Debit Head Amount
        $log = new TransactionLog();
        $log->notes = $request->note;
        $log->project_id =  $order->project_id;
        $log->receipt_payment_no = $voucherNo;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = financialYear($request->financial_year);
        $log->client_id = 0;
        $log->date = Carbon::parse($request->date)->format('Y-m-d');
        $log->receipt_payment_id = $receiptPayment->id;
        $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
        $log->payment_type = $request->payment_type;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $log->transaction_type = 2;//Account Head Credit
        $log->account_head_id = $request->account;
        $log->scrap_sales_order_id = $order->id;
        $log->purchase_order_id = 2;
        $log->amount = $request->amount;
        $log->notes = $request->note;
        $log->save();


        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('scrap_sale_receipt.all')]);

        // $rules = [
        //     'amount' => 'required|numeric|min:1',
        // ];
        // $item = ScrapSalesOrder::find($request->client);
        // $rules['amount'] = 'required|numeric|min:0|max:'.$item->due;
        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        // }
        // $item->decrement('due', $request->amount);
        // $item->increment('paid', $request->amount);
        // return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('scrap_sale_receipt.all')]);
    }

    public function stockDatatable() {
        $query = SaleProductStock::with('product');

        return DataTables::eloquent($query)
            ->addColumn('product', function(SaleProductStock $stock) {
                return $stock->product->name;
            })
            ->editColumn('amount', function(SaleProductStock $stock) {
                return number_format($stock->amount, 2).' '. $stock->product->unit->name;
            })
            ->editColumn('date', function(SaleProductStock $stock) {
                return $stock->date->format('j F, Y');
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->toJson();
    }

    public function saleInventoryDatatable() {
        $query = SaleInventory::with('project','floor','flat');

        if (request()->has('project') && request('project') != '') {
            $query->where('project_id',(int)request('project'));
        }

        return DataTables::eloquent($query)

            ->editColumn('status', function(SaleInventory $inventory) {
                if ($inventory->status == 1)
                    return '<span class="label label-success">Available</span>';
                else
                    return '<span class="label label-danger">Sold Out</span>';
            })
            ->addColumn('project', function(SaleInventory $inventory) {
                return $inventory->project->name ?? '';
            })
            ->addColumn('floor', function(SaleInventory $inventory) {
                return $inventory->floor->name ?? '';
            })
            ->addColumn('flat', function(SaleInventory $inventory) {
                return $inventory->flat->name ?? '';
            })
            ->rawColumns(['status'])
            ->toJson();
    }

    public function saleInventoryDetailsDatatable() {
        $query = SaleInventoryLog::where('sale_product_id', request('product_id'))
            ->with('product', 'client');

        return DataTables::eloquent($query)
            ->editColumn('date', function(SaleInventoryLog $log) {
                return $log->date->format('j F, Y');
            })
            ->editColumn('type', function(SaleInventoryLog $log) {
                if ($log->type == 1)
                    return '<span class="label label-success">In</span>';
                else
                    return '<span class="label label-danger">Out</span>';
            })
            ->editColumn('quantity', function(SaleInventoryLog $log) {
                return number_format($log->quantity, 2).' '. $log->product->unit->name;
            })
            ->editColumn('unit_price', function(SaleInventoryLog $log) {
                if ($log->unit_price)
                    return ''.number_format($log->unit_price, 2);
                else
                    return '';
            })
            ->editColumn('client', function(SaleInventoryLog $log) {
                if ($log->client)
                    return $log->client->name;
                else
                    return '';
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['type'])
            ->filter(function ($query) {
                if (request()->has('date') && request('date') != '') {
                    $dates = explode(' - ', request('date'));
                    if (count($dates) == 2) {
                        $query->where('date', '>=', $dates[0]);
                        $query->where('date', '<=', $dates[1]);
                    }
                }

                if (request()->has('type') && request('type') != '') {
                    $query->where('type', request('type'));
                }
            })
            ->toJson();
    }

    public function saleProductJson(Request $request) {
        if (!$request->searchTerm) {
            $products = SaleProduct::where('status', 1)->orderBy('name')->limit(10)->get();
        } else {
            $products = SaleProduct::where('status', 1)->where('name', 'like', '%'.$request->searchTerm.'%')->orderBy('name')->limit(10)->get();
        }

        $data = array();

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'text' => $product->name
            ];
        }

        echo json_encode($data);
    }

    public function saleProductDetails(Request $request) {
        $product = SaleProduct::find($request->productId);

        return response()->json($product);
    }

    public function clientPaymentEdit(SalePayment $payment){
       // dd($payment);
       $banks = Bank::where('status', 1)->get();
        $orders = SalesOrder::where('client_id',$payment->client_id)->where('due','>=',0)->get();
        return view('sale.client_payment.edit',compact("payment","orders",'banks'));
    }
    public function clientPaymentEditPost(Request $request,SalePayment $payment){
       //dd($request->all());
        $rules = [
            'order_no' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'next_date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
            $rules['cheque_date'] = 'required|date';
        }


        if ($request->order_no != '') {
            $order = SalesOrder::find($request->order_no);
            $rules['amount'] = 'required|numeric|min:0|max:' . $order->due;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }


        $order = SalesOrder::where('order_no',$payment->sales_order_id)->first();

        $client=Client::find($payment->client_id);

        if ($payment->transaction_method == 1 ) {
            $log =  TransactionLog::where('sale_payment_id',$payment->id)->first();
            $log->delete();
            $order->decrement('paid', $payment->amount);
            $order->increment('due', $payment->amount);
            $client->decrement('paid', $payment->amount);
            $client->increment('due', $payment->amount);
            $payment->delete();
            Cash::first()->decrement('amount', $payment->amount);
        }
        elseif($payment->transaction_method == 2 ) {
            $log =  TransactionLog::where('sale_payment_id',$payment->id)->first();
            $log->delete();
            $order->decrement('paid', $payment->amount);
            $order->increment('due', $payment->amount);
            $client->decrement('paid', $payment->amount);
            $client->increment('due', $payment->amount);
            Cheeque::first()->decrement('amount', $payment->amount);
            $payment->delete();
        }elseif($payment->transaction_method == 3 ){
            $order->increment('due', $payment->amount);
            $client->decrement('discount', $payment->amount);
            $client->increment('due', $payment->amount);
            $payment->delete();
        }
//        update
//


        $order_flat= $order->flats()->first();
        $payCount = SalePayment::count();
        if ($request->payment_type == 1 ) {
            $payment = new SalePayment();
            $payment->sales_order_id = $order->order_no;
            $payment->receipt_no = str_pad($payCount+1000, 4, '0', STR_PAD_LEFT);
            $payment->flat_id = $order_flat->pivot->flat_id;
            $payment->project_id = $order_flat->project_id;
            $payment->client_id = $client->id;
            $payment->transaction_method = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->next_date = $request->next_date;
            $payment->note = $request->note;
            $payment->save();

            Cash::first()->increment('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->client_id = $client->id;
            $log->project_id = $order_flat->project_id;
            $log->flat_id = $order_flat->pivot->flat_id;
            $log->particular = 'Payment from '.$client->name;
            $log->transaction_type = 1;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->save();
            $order->increment('paid', $request->amount);
            $client->increment('paid', $request->amount);


        } elseif($request->payment_type == 2 ) {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/sales_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/sales_payment_cheque/'.$filename;
            }

            $payment = new SalePayment();
            $payment->client_id = $client->id;
            $payment->sales_order_id = $order->order_no;
            $payment->flat_id = $order_flat->pivot->flat_id;
            $payment->project_id = $order_flat->project_id;
            $payment->transaction_method = 2;
            $payment->receipt_no = str_pad($payCount+1000, 4, '0', STR_PAD_LEFT);
            $payment->bank = $request->bank;
            $payment->branch = $request->branch;
            $payment->bank_account = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->cheque_date = $request->cheque_date;
            $payment->cheque_image = $image;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->next_date = $request->next_date;
            $payment->note = $request->note;
            $payment->save();
            Cheeque::first()->increment('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->client_id = $client->id;
            $log->project_id = $order_flat->project_id;
            $log->flat_id = $order_flat->pivot->flat_id;
            $log->particular = 'Payment from '.$client->name;
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 2;
            $log->account_head_sub_type_id = 2;
            $log->bank = $request->bank;
            $log->branch = $request->branch;
            $log->bank_account = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->sale_payment_id = $payment->id;
            $log->save();

            $order->increment('paid', $request->amount);
            $client->increment('paid', $request->amount);


        }elseif($request->payment_type == 3 ){
            $payment = new SalePayment();
            $payment->sales_order_id = $order->order_no;
            $payment->receipt_no = str_pad($payCount+1000, 4, '0', STR_PAD_LEFT);
            $payment->flat_id = $order_flat->pivot->flat_id;
            $payment->project_id = $order_flat->project_id;
            $payment->client_id = $client->id;
            $payment->transaction_method = $request->payment_type;
            $payment->discount = $request->amount;
            $payment->date = $request->date;
            $payment->next_date = $request->next_date;
            $payment->note = $request->note;
            $payment->save();
            $client->increment('discount', $request->amount);

        }
        $order->decrement('due', $request->amount);
        $client->decrement('due', $request->amount);

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('sale_receipt.payment_print', ['payment' => $payment->id])]);

    }
}
