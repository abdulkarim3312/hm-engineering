<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\EstimateProduct;
use App\Model\ProductRequisition;
use App\Model\Project;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrder;
use App\Model\PurchaseProduct;
use App\Model\PurchaseProductUtilize;
use App\Model\Requisition;
use App\Model\SalesOrder;
use App\Model\Supplier;
use App\Model\TransactionLog;
use App\Model\Warehouse;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class TradingPurchaseController extends Controller
{
    public function tradingPurchaseOrder() {
        $suppliers = Client::where('type', 2)->where('status', 1)->get();
        $projects = Project::where('status', 1)->get();
        // dd($projects);
        return view('trading_purchase.purchase_order.create', compact('suppliers', 'projects'));
    }

    public function tradingPurchaseOrderPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'supplier' => 'required',
            'note' => 'nullable|max:255',
            'supplier_invoice' => 'nullable|image',
            'date' => 'required|date',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:.01',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        // Upload Image
        if ($request->supplier_invoice){
            $file = $request->file('supplier_invoice');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/purchase';
            $file->move($destinationPath, $filename);

            $imagePath = 'uploads/purchase/'.$filename;
        }

        $order = new PurchaseOrder();
        $order->supplier_id = $request->supplier;
        $order->project_id = $request->project;
        $order->purchase_type = 3;
        $order->date = $request->date;
        $order->supplier_invoice = $imagePath??'';
        $order->note = $request->note;
        $order->total = 0;
        $order->save();
        $order->order_no = 'HM-B-' . date('M-Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $order->save();

        $counter = 0;
        $total = 0;

        foreach ($request->product as $reqProduct) {

            $product = PurchaseProduct::find($reqProduct);

            $order->products()->attach($reqProduct, [
                'name' => $product->name,
                'unit' => $product->unit->name,
                'quantity' => $request->quantity[$counter],
                'unit_price' => $request->unit_price[$counter],
                'total' => $request->quantity[$counter] * $request->unit_price[$counter],
            ]);

            $total += $request->quantity[$counter] * $request->unit_price[$counter];
            $counter++;
        }
        $order->total = $total;
        $order->due = $total;
        $order->save();

        return redirect()->route('purchase_receipt.details', ['order' => $order->id]);
    }

    public function tradingPurchaseOrderEdit(PurchaseOrder $order){
        $products = PurchaseProduct::where("status",1)->get();
        $suppliers = Client::where('type',2)->where('status', 1)->orderBy('name')->get();
        $projects = Project::where('status', 1)->orderBy('name')->get();

        return view('trading_purchase.purchase_order.edit', compact('suppliers','projects','order','products'));
    }

    public function tradingPurchaseOrderEditPost(Request $request,PurchaseOrder $order){

        $request->validate([
            'project' => 'required',
            //'segment' => 'required',
            'requisition' => 'required',
            'supplier' => 'required',
            'supplier_invoice' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'note' => 'nullable|max:255',
            'date' => 'required|date',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:.01',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $firsCounter = 0;

        foreach ($order->products as $product){
            $productRequisition = ProductRequisition::where('requisition_id',$request->requisition)
                ->where('purchase_product_id',$product->id)
                ->first();

            if ($productRequisition->approved_quantity < $request->quantity[$firsCounter]){
                return redirect()->back()->withInput()->with('error', 'Insufficient Requisition Approved Quantity ');
            }
            $firsCounter++;
        }

        $previousSerials = [];

        foreach ($order->products as $product){
            $previousSerials[] = $product->pivot->purchase_product_id;
        }


        $counter = 0;
        $total = 0;
        if ($request->product) {
            foreach ($request->product as $serial) {

                if (in_array($serial, $previousSerials)) {

                    // Old Item
                    $product = PurchaseProduct::find($request->product[$counter]);

                    $purchaseProduct = DB::table('purchase_order_purchase_product')
                        ->where('purchase_order_id',$order->id)
                        ->where('purchase_product_id', $serial)->first();

                    DB::table('purchase_order_purchase_product')
                        ->where('purchase_order_id',$order->id)
                        ->where('purchase_product_id', $serial)
                        ->update([
                            'project_id' => $request->project,
                            //'segment_id' => $request->segment,
                            'requisition_id' => $request->requisition,
                            'purchase_product_id' => $request->product[$counter],
                            'name' => $product->name,
                            'unit' => $product->unit->name,
                            'quantity' => $request->quantity[$counter],
                            'unit_price' => $request->unit_price[$counter],
                            'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                        ]);
                    $total += $request->quantity[$counter] * $request->unit_price[$counter];
                    if ($order->received_at) {
                        // Inventory
                        $totalPrice = DB::table('purchase_order_purchase_product')
                            ->where('purchase_product_id', $product->id)
                            ->sum('total');

                        $totalQuantity = DB::table('purchase_order_purchase_product')
                            ->where('purchase_product_id', $product->id)
                            ->sum('quantity');

                        $avgPrice = $totalPrice / $totalQuantity;

                        $inventory = PurchaseInventory::where('project_id', $request->project)
                            ->where('purchase_product_id', $serial)->first();

                        $inventory->avg_unit_price = $avgPrice;
                        $inventory->last_unit_price = $request->unit_price[$counter];
                        $inventory->quantity = $request->quantity[$counter];
                        $inventory->save();

                        if ($request->quantity[$counter] != $purchaseProduct->quantity) {
                            $inventoryLog = new PurchaseInventoryLog();
                            $inventoryLog->project_id = $request->project;
                            $inventoryLog->purchase_product_id = $product->id;

                            if ($request->quantity[$counter] > $purchaseProduct->quantity) {

                                $inventoryLog->type = 3;
                                $inventoryLog->quantity = $request->quantity[$counter] - $purchaseProduct->quantity;
                            } else {

                                $inventoryLog->type = 4;
                                $inventoryLog->quantity = $purchaseProduct->quantity - $request->quantity[$counter];
                            }

                            $inventoryLog->date = date('Y-m-d');
                            $inventoryLog->unit_price = $request->unit_price[$counter];
                            $inventoryLog->supplier_id = $request->supplier;
                            $inventoryLog->purchase_order_id = $order->id;
                            $inventoryLog->save();
                        }
                    }

                    if (($key = array_search($serial, $previousSerials)) !== false) {
                        unset($previousSerials[$key]);
                    }
                } else {
                    // New Item
                    $product = Product::find($request->product[$counter]);

                    $order->products()->attach($product->id, [
                        'project_id' => $request->project_id,
                        //'segment_id' => $request->segment_id,
                        'requisition_id' => $request->requisition_id,
                        'name' => $product->name,
                        'unit' => $product->unit->name,
                        'quantity' => $request->quantity[$counter],
                        'unit_price' => $request->unit_price[$counter],
                        'total' => $request->quantity[$counter] * $request->unit_price[$counter],
                    ]);

                    $total += $request->quantity[$counter] * $request->unit_price[$counter];
                    if ($order->received_at) {
                        // Inventory
                        $exist = PurchaseInventory::where('project_id', $request->project)
                            ->where('purchase_product_id', $product->id)
                            ->first();
                        $totalPrice = DB::table('purchase_order_purchase_product')
                            ->where('purchase_product_id', $product->id)
                            ->sum('total');
                        $totalQuantity = DB::table('purchase_order_purchase_product')
                            ->where('purchase_product_id', $product->id)
                            ->sum('quantity');

                        $avgPrice = $totalPrice / $totalQuantity;

                        if ($exist) {
                            $inventory = PurchaseInventory::where('project_id', $request->project)
                                ->where('purchase_product_id', $product->id)->first();

                            $inventory->purchase_product_id = $product->id;
                            $inventory->avg_unit_price = $avgPrice;
                            $inventory->save();
                            $inventory->increment('quantity', $request->quantity[$counter]);

                        } else {
                            $inventory = new PurchaseInventory();
                            $inventory->purchase_product_id = $product->id;
                            $inventory->purchase_order_id = $order->id;
                            $inventory->quantity = $request->quantity[$counter];
                            $inventory->avg_unit_price = $avgPrice;
                            $inventory->project_id = $request->project;
                            $inventory->save();
                        }

                        $inventoryLog = new PurchaseInventoryLog();
                        $inventoryLog->purchase_product_id = $product->id;
                        $inventoryLog->type = 3;
                        $inventoryLog->date = date('Y-m-d');
                        $inventory->project_id = $request->company;
                        $inventoryLog->quantity = $request->quantity[$counter];
                        $inventoryLog->unit_price = $request->unit_price[$counter];
                        $inventoryLog->supplier_id = $request->supplier;
                        $inventoryLog->save();
                    }
                }

                $counter++;
            }
        }

        // Delete items
        foreach ($previousSerials as $serial) {

            $purchaseProduct = DB::table('purchase_order_purchase_product')
                ->where('purchase_order_id', $order->id)
                ->where('purchase_product_id', $serial)->first();
            if ($order->received_at) {
                $inventory = PurchaseInventory::where('project_id',$request->project)
                    ->where('purchase_product_id',$purchaseProduct->purchase_product_id)->first();

                $inventoryLog = new PurchaseInventoryLog();
                $inventoryLog->purchase_product_id = $purchaseProduct->purchase_product_id;
                $inventoryLog->project_id = $request->project;
                $inventoryLog->type = 4;
                $inventoryLog->quantity = $purchaseProduct->quantity;
                $inventoryLog->date = date('Y-m-d');
                $inventoryLog->unit_price = $purchaseProduct->unit_price;
                $inventoryLog->supplier_id = $request->supplier;
                $inventoryLog->purchase_order_id = $order->id;
                $inventoryLog->save();

                $inventory->delete();
            }

            DB::table('purchase_order_purchase_product')->where('purchase_order_id', $order->id)
                ->where('purchase_product_id', $serial)->delete();

        }

        // Update Order
        if ($request->supplier_invoice) {
            $imagePath = $order->supplier_invoice;
            if(file_exists($imagePath)){

                // Previous Photo
                $previousPhoto = public_path($order->supplier_invoice);
                unlink($previousPhoto);

                // Upload Image
                $file = $request->file('supplier_invoice');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/purchase';
                $file->move($destinationPath, $filename);
                $imagePath = 'uploads/purchase/'.$filename;
                $order->supplier_invoice = $imagePath;
            }
            else {

                // Upload Image
                $file = $request->file('supplier_invoice');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/purchase';
                $file->move($destinationPath, $filename);
                $imagePath = 'uploads/purchase/'.$filename;
                $order->supplier_invoice = $imagePath;
            }
        }

        $order->supplier_id = $request->supplier;
        $order->project_id = $request->project;
        //$order->segment_id = $request->segment;
        $order->requisition_id = $request->requisition;
        $order->note = $request->note;
        $order->date = $request->date;

        if ($total > $order->total) {
            if ($order->refund > 0) {
                if ($order->refund > $total - $order->total) {
                    $order->decrement('refund', $total - $order->total);
                } else  {
                    $previousRefund = $order->refund;
                    $order->decrement('refund', $order->refund);
                    $order->increment('due', $total - $order->total- $previousRefund);
                }
            } else {
                $order->increment('due', $total - $order->total);
            }

        } elseif($order->total > $total) {
            if ($order->due >= 0) {
                if ($order->due > $order->total - $total) {
                    $order->decrement('due', $order->total - $total);
                } else {
                    $previousDue = $order->due;
                    $order->decrement('due', $order->due);
                    $order->increment('refund', $order->total - $total - $previousDue);
                }
            } else {
                $order->increment('refund', $order->total - $total);
            }
        }

        $order->total = $total;
        $order->save();

        return redirect()->route('purchase_receipt.details', ['order' => $order->id]);

    }

    public function tradingPurchaseReceipt() {
        $warehouses = Warehouse::where('status',1)->get();
        return view('trading_purchase.receipt.all',compact('warehouses'));
    }

    public function tradingPurchaseReceiptDetails(PurchaseOrder $order) {
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        return view('trading_purchase.receipt.details', compact('order'));
    }

    public function tradingPurchaseReceiptPrint(PurchaseOrder $order) {
        return view('trading_purchase.receipt.print', compact('order'));
    }

    public function tradingPurchasePaymentDetails(PurchasePayment $payment) {
        if($payment->amount){
            $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
                'Poisa');
        }else {
            $payment->amount_in_word = DecimalToWords::convert($payment->discount,'Taka',
                'Poisa');
        }


        return view('trading_purchase.receipt.payment_details', compact('payment'));
    }

    public function tradingPurchasePaymentPrint(PurchasePayment $payment) {
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('trading_purchase.receipt.payment_print', compact('payment'));
    }

    public function supplierPayment() {
        $suppliers = Client::where('type',2)->where('project_type',3)->get();
        // dd($suppliers);
        return view('trading_purchase.supplier_payment.all', compact('suppliers'));
    }

    public function supplierPaymentDatatable() {
        $query = Client::where('type',2);

        return DataTables::eloquent($query)
            ->addColumn('action', function(Supplier $supplier) {
                if ( $supplier->due > 0) {
                    return '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$supplier->id.'" data-name="'.$supplier->name.'">Pay</a>
                         <a href="'.route('supplier_payment_details',['supplier'=>$supplier->id]).'" class="btn btn-primary btn-sm" >Details</a>';
                }else{
                    return '<a href="'.route('supplier_payment_details',['supplier'=>$supplier->id]).'" class="btn btn-primary btn-sm" >Details</a>';
                }
            })
            ->editColumn('total', function(Supplier $supplier) {
                return ' '.number_format($supplier->total, 2);
            })
            ->editColumn('paid', function(Supplier $supplier) {
                return ' '.number_format($supplier->paid, 2);
            })
            ->editColumn('due', function(Supplier $supplier) {
                return ' '.number_format($supplier->due, 2);
            })

            ->rawColumns(['action'])
            ->toJson();
    }

    public function supplierPaymentDetails(Client $client)
    {
        return view('trading_purchase.supplier_payment.supplier_payment_details',compact('client'));
    }

    public function supplierPaymentDelete(Request $request)
    {
        $payment = PurchasePayment::where('id',$request->paymentId)->first();
        if ($payment->type == 1) {
            $log = TransactionLog::where('purchase_payment_id',$request->paymentId)->first();
            if ($log->transaction_method == 1) {
                Cash::first()->increment('amount',$log->amount);
            }elseif ($log->transaction_method == 2) {
                BankAccount::where('id',$log->bank_account_id)->first()
                    ->increment('balance',$log->amount);
            }
            $order = PurchaseOrder::where('id',$payment->purchase_order_id)->first();

            $order->decrement('paid',$log->amount);
            $order->increment('due',$log->amount);

            $log->delete();
            $payment->delete();
            return response()->json(['success' => true, 'message' => 'Client received has been deleted.']);

        }

    }

    public function makePayment(Request $request) {
        // dd($request->all());

        $rules = [
            'financial_year' => 'required',
            'order' => 'required',
            'project' => 'required',
            'payment_type' => 'required',
            'account' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == 1) {
            $rules['cheque_date'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $order =PurchaseOrder::find($request->order);
        $supplier = Client::find($request->supplier);

        $rules['amount'] = 'required|numeric|min:0|max:'.$order->due;


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = PurchaseOrder::find($request->order);

        $supplier->decrement('due', $request->amount);
        $supplier->decrement('paid', $request->amount);
        $order->increment('paid', $request->amount);
        $order->decrement('due', $request->amount);

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
        $receiptPayment->client_id = $supplier->id;
        $receiptPayment->customer_id = $supplier->id_no;
        $receiptPayment->sub_total = $request->amount;
        $receiptPayment->net_amount = $request->amount;
        $receiptPayment->purchase_order_id = $order->id;
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
            $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');

        }
        $log->transaction_type = 2;//Bank Credit,Cash credit

        $log->payment_type = $request->payment_type;
        $log->account_head_id = $request->account;
        $log->amount = $receiptPayment->net_amount;
        $log->notes = $receiptPayment->notes;
        $log->purchase_order_id = $order->id;
        $log->save();

        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->account_head_id = 12;
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
        $log->client_id = $supplier->id;
        $log->date = Carbon::parse($request->date)->format('Y-m-d');
        $log->receipt_payment_id = $receiptPayment->id;
        $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
        $log->payment_type = $request->payment_type;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $log->transaction_type = 1;//Account Head Debit
        $log->account_head_id = 12;
        $log->purchase_order_id = $order->id;
        $log->amount = $request->amount;
        $log->notes = $request->note;
        $log->save();


        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('voucher_details', ['receiptPayment' => $receiptPayment->id])]);
    }

    public function makeRefund(Request $request) {
        $rules = [
            'order' => 'required',
            'account' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == 1) {
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        if ($request->order != '') {
            $order = PurchaseOrder::find($request->order);
            $rules['amount'] = 'required|numeric|min:0|max:'.$order->refund;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order = PurchaseOrder::find($request->order);
        $order->decrement('refund', $request->amount);

        $supplier = Client::find($order->supplier_id);

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
            $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)
                ->format('Y-m-d');
            $receiptPayment->issuing_bank_name = $request->issuing_bank_name;
            $receiptPayment->issuing_branch_name = $request->issuing_branch_name;
        }
        $receiptPayment->client_id = $supplier->id;
        $receiptPayment->customer_id = $supplier->id_no;
        $receiptPayment->sub_total = $request->amount;
        $receiptPayment->net_amount = $request->amount;
        $receiptPayment->purchase_order_id = $order->id;
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
            $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');

        }
        $log->transaction_type = 1;//Bank Debit,Cash Debit

        $log->payment_type = $request->payment_type;
        $log->account_head_id = $request->account;
        $log->amount = $receiptPayment->net_amount;
        $log->notes = $receiptPayment->notes;
        $log->purchase_order_id = $order->id;
        $log->save();

        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->account_head_id = 13;
        $receiptPaymentDetail->amount = $request->amount;
        $receiptPaymentDetail->net_amount = $request->amount;
        $receiptPaymentDetail->save();

        //Credit Head Amount
        $log = new TransactionLog();
        $log->notes = $request->note;
        $log->project_id = $order->project_id;
        $log->receipt_payment_no = $voucherNo;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = financialYear($request->financial_year);
        $log->client_id = $supplier->id;
        $log->date = Carbon::parse($request->date)->format('Y-m-d');
        $log->receipt_payment_id = $receiptPayment->id;
        $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
        $log->payment_type = $request->payment_type;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $log->transaction_type = 2;//Account Head credit
        $log->account_head_id = 13;
        $log->purchase_order_id = $order->id;
        $log->amount = $request->amount;
        $log->notes = $request->note;
        $log->save();

        return response()->json(['success' => true, 'message' => 'Refund has been completed.', 'redirect_url' => route('receipt_details', ['receiptPayment' => $receiptPayment->id])]);
    }

    public function purchaseInventory() {
        return view('trading_purchase.inventory.all');
    }

    public function purchaseInventoryDetails($project, $product) {

        $product=PurchaseProduct::find($product);
        $project=Project::find($project);

        return view('trading_purchase.inventory.details', compact('product','project'));
    }

    public function utilizeIndex() {
        return view('trading_purchase.utilize.all');
    }

    public function utilizeAdd() {
        $products = PurchaseProduct::where('status', 1)
            ->orderBy('name')->get();
        $projects = Project::where('status', 1)
            ->orderBy('name')->get();
        $warehouses = Warehouse::where('status', 1)
            ->orderBy('name')->get();
        return view('trading_purchase.utilize.add', compact('warehouses','products','projects'));
    }

    public function utilizeAddPost(Request $request) {

        $validator = Validator::make($request->all(), [
            'financial_year' => 'required',
            'project' => 'required',
            //'segment' => 'required',
            'warehouse' => 'required',
            'product' => 'required',
            'quantity' => 'required|numeric|min:0.01',
            'date' => 'date|date',
            'note' => 'nullable|string|max:255',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request) {
            $inventory = PurchaseInventory::where('project_id', $request->project)
                // ->where('segment_id', $request->segment)
                ->where('purchase_product_id', $request->product)
                ->where('warehouse_id', $request->warehouse)
                ->first();
            if ($inventory) {
                if ($inventory->quantity < $request->quantity)
                    $validator->errors()->add('quantity', 'Insufficient stock.');
            } else {
                $validator->errors()->add('quantity', 'Insufficient stock.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $inventory = PurchaseInventory::where('project_id', $request->project)
            // ->where('segment_id', $request->segment)
            ->where('purchase_product_id', $request->product)
            ->where('warehouse_id', $request->warehouse)
            ->first();

        $inventoryLog = new PurchaseInventoryLog();
        $inventoryLog->warehouse_id = $request->warehouse;
        $inventoryLog->purchase_product_id = $request->product;
        $inventoryLog->project_id = $request->project;
        //$inventoryLog->segment_id = $request->segment;
        $inventoryLog->type = 2;
        $inventoryLog->date = $request->date;
        $inventoryLog->unit_price = $inventory->avg_unit_price;
        $inventoryLog->quantity = $request->quantity;
        $inventoryLog->note = $request->note;
        $inventoryLog->save();

        $utilize = new PurchaseProductUtilize();
        $utilize->warehouse_id = $request->warehouse;
        $utilize->purchase_product_id = $request->product;
        $utilize->project_id = $request->project;
        //$utilize->segment_id = $request->segment;
        $utilize->quantity = $request->quantity;
        $utilize->unit_price = $inventory->avg_unit_price;
        $utilize->date = $request->date;
        $utilize->note = $request->note;
        $utilize->purchase_inventory_log_id = $inventoryLog->id;
        $utilize->save();

        $inventory->decrement('quantity', $request->quantity);

//        $log = new TransactionLog();
//        $log->date = $request->date;
//        $log->particular = 'Purchase Product Utilize';
//        $log->project_id = $request->project;
//        $log->transaction_type = 4;
//        $log->account_head_type_id = 6;
//        $log->account_head_sub_type_id = 6;
//        $log->amount = $request->quantity * $inventory->avg_unit_price;
//        $log->note = $request->note;
//        $log->purchase_product_utilize_id = $utilize->id;
//        $log->save();



        return redirect()->route('purchase_product.utilize.all')
            ->with('message', 'Utilize add successfully.');
    }

    public function supplierPaymentGetOrders(Request $request) {
        $orders = PurchaseOrder::where('supplier_id', $request->supplierId)
            ->with('project')
            ->where('due', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function supplierPaymentGetRefundOrders(Request $request) {
        $orders = PurchaseOrder::where('supplier_id', $request->supplierId)
            ->where('refund', '>', 0)
            ->orderBy('order_no')
            ->get()->toArray();

        return response()->json($orders);
    }

    public function supplierPaymentOrderDetails(Request $request) {
        $order = PurchaseOrder::with('project')->where('id', $request->orderId)
            ->first()->toArray();

        return response()->json($order);
    }

    public function tradingOrderReceive(Request $request) {
//        dd($request->all());
        $request->validate([
            'warehouse' => 'required',
            'date' => 'required'
        ]);

        $order = PurchaseOrder::where('id', $request->orderId)
            ->with('products')->first();

        $order->warehouse_id = $request->warehouse;
        $order->received_at = $request->date;
        $order->save();


        foreach ($order->products as $product) {

            $exist = PurchaseInventory::where('warehouse_id',$request->warehouse)
                ->whereNull('project_id')
                ->whereNull('segment_id')
                ->where('purchase_product_id', $product->id)
                ->first();

            if ($exist) {
                $totalQuantity = $product->pivot->quantity + $exist->quantity;
                $totalExitPrice = $exist->avg_unit_price * $exist->quantity;
                $totalNewPrice = $product->pivot->quantity * $product->pivot->unit_price;

                $totalExitNewPrice = $totalExitPrice + $totalNewPrice;
                $avgPrice = $totalExitNewPrice / $totalQuantity;
                $exist->increment('quantity', $product->pivot->quantity);
                $exist->avg_unit_price = $avgPrice;
                $exist->last_unit_price = $product->pivot->unit_price;
                $exist->save();
            } else {
                $inventory = new PurchaseInventory();
                $inventory->warehouse_id = $request->warehouse;
                $inventory->purchase_product_id = $product->id;
                $inventory->quantity = $product->pivot->quantity;
                $inventory->unit = $product->pivot->unit;
                $inventory->avg_unit_price = $product->pivot->unit_price;
                $inventory->last_unit_price = $product->pivot->unit_price;
                $inventory->save();
            }

            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->warehouse_id = $request->warehouse;
            $inventoryLog->purchase_product_id = $product->id;
            $inventoryLog->purchase_order_id =  $order->id;
            $inventoryLog->requisition_id =  $order->requisition_id;
            $inventoryLog->supplier_id = $order->supplier_id;
            $inventoryLog->type = 1;
            $inventoryLog->date = $request->date? Carbon::parse($request->date)->format('Y-m-d') : null;
            $inventoryLog->quantity = $product->pivot->quantity;
            $inventoryLog->unit = $product->pivot->unit;
            $inventoryLog->unit_price = $product->pivot->unit_price;
            $inventoryLog->save();
        }
    }

    public function tradingPurchaseReceiptDatatable() {
        $query = PurchaseOrder::with('supplier')->where('purchase_type',3);

        return DataTables::eloquent($query)
            ->addColumn('supplier', function(PurchaseOrder $order) {
                return $order->supplier->name ?? '';
            })
            ->addColumn('action', function(PurchaseOrder $order) {
                if ($order->received_at ==null && $order->paid ==0 )
                    return '<a href="'.route('trading_purchase_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a class="btn btn-success btn-sm btn-receive" role="button" data-id="'.$order->id.'">Receive</a> <a href="'.route('purchase_order_edit', ['order' => $order->id]).'" class="btn btn-info btn-sm">Edit</a> <a role="button" data-id="'.$order->id.'" class="btn btn-danger btn-sm btn-delete">Delete</a>';
                elseif ($order->received_at !=null)
                    return '<a href="'.route('trading_purchase_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('purchase_order_edit', ['order' => $order->id]).'" class="btn btn-info btn-sm">Edit</a>';
                else
                    return '<a href="'.route('trading_purchase_receipt.details', ['order' => $order->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('purchase_order_edit', ['order' => $order->id]).'" class="btn btn-info btn-sm">Edit</a> <a class="btn btn-success btn-sm btn-receive" role="button" data-id="'.$order->id.'">Receive</a>';
            })
            ->editColumn('date', function(PurchaseOrder $order) {
                return $order->date->format('d-m-Y');
            })
            ->editColumn('total', function(PurchaseOrder $order) {
                return ' '.number_format($order->total, 2);
            })
            ->editColumn('paid', function(PurchaseOrder $order) {
                return ' '.number_format($order->paid, 2);
            })
            ->editColumn('due', function(PurchaseOrder $order) {
                return ' '.number_format($order->due, 2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','requisition_no'])
            ->toJson();
    }

    public function purchaseInventoryDatatable() {
        $query = PurchaseInventory::with('product','project','segment','warehouse')
            ->select(DB::raw('sum(quantity) as quantity, avg(avg_unit_price) as avg_unit_price,warehouse_id,purchase_product_id,project_id,segment_id'))
            ->groupBy('warehouse_id','project_id','segment_id','purchase_product_id');

        return DataTables::eloquent($query)
            ->addColumn('product', function(PurchaseInventory $inventory) {
                return $inventory->product->name;
            })
            ->addColumn('warehouse_name', function(PurchaseInventory $inventory) {
                return $inventory->warehouse->name ?? '';
            })
            ->addColumn('project', function(PurchaseInventory $inventory) {
                return $inventory->project->name ?? '';
            })
            ->addColumn('segment_name', function(PurchaseInventory $inventory) {
                return $inventory->segment->name ?? '';
            })

            ->addColumn('action', function(PurchaseInventory $inventory) {
                return '<a href="'.route('purchase_inventory.details', ['project' => $inventory->project_id,'product' => $inventory->purchase_product_id]).'" class="btn btn-primary btn-sm">Details</a>';

            })

            ->editColumn('quantity', function(PurchaseInventory $inventory) {
                return number_format($inventory->quantity, 2).' '. $inventory->product->unit->name;
            })
            ->editColumn('avg_unit_price', function(PurchaseInventory $inventory) {
                return ' '.number_format($inventory->avg_unit_price, 2);
            })

            ->addColumn('total', function(PurchaseInventory $inventory) {
                return ' '.number_format($inventory->last_unit_price * $inventory->quantity, 2);

            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function utilizeDatatable() {
        $query = PurchaseProductUtilize::with('product','warehouse','project','segment');

        return DataTables::eloquent($query)
            ->addColumn('warehouse', function(PurchaseProductUtilize $utilize) {
                return $utilize->warehouse->name ?? '';
            })
            ->addColumn('product', function(PurchaseProductUtilize $utilize) {
                return $utilize->product->name ?? '';
            })
            ->addColumn('project', function(PurchaseProductUtilize $utilize) {
                return $utilize->project->name ?? '';
            })
            ->addColumn('segment', function(PurchaseProductUtilize $utilize) {
                return $utilize->segment->name ?? '';
            })
            ->editColumn('quantity', function(PurchaseProductUtilize $utilize) {
                return number_format($utilize->quantity, 2).' '. $utilize->product->unit->name;
            })
            ->editColumn('date', function(PurchaseProductUtilize $utilize) {
                return $utilize->date->format('d-m-Y');
            })
            ->editColumn('unit_price', function(PurchaseProductUtilize $utilize) {
                return ' '.number_format($utilize->unit_price,2);
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->toJson();
    }

    public function purchaseInventoryDetailsDatatable() {
        $query = PurchaseInventoryLog::where('project_id', request('project_id'))
            ->where('purchase_product_id', request('product_id'))
            ->with('warehouse','product', 'supplier','purchase_order','project','requisition','segment');


        return DataTables::eloquent($query)
            ->addColumn('purchase_order', function(PurchaseInventoryLog $log) {
                if ($log->purchase_order)
                    return '<a target="_blank" href="'.route('purchase_receipt.details',['order'=>$log->purchase_order->id]).'" class="btn-link">'.$log->purchase_order->order_no.'</a>';
                else
                    return '';
            })
            ->addColumn('requisition_no', function(PurchaseInventoryLog $log) {
                if ($log->requisition)
                    return '<a target="_blank" href="'.route('requisition.details',['requisition'=>$log->requisition_id]).'" class="btn-link">'.$log->requisition->requisition_no.'</a>';
                else
                    return '';
            })
            ->editColumn('date', function(PurchaseInventoryLog $log) {
                return $log->date->format('d-m-Y');
            })
            ->editColumn('type', function(PurchaseInventoryLog $log) {
                if ($log->type == 1)
                    return '<span class="label label-success">In</span>';
                elseif($log->type == 2)
                    return '<span class="label label-danger">Utilized</span>';
                elseif($log->type == 6)
                    return '<span class="label label-success">Scrap In</span>';
                elseif($log->type == 7)
                    return '<span class="label label-danger">Scraped</span>';
                elseif($log->type == 9)
                    return '<span class="label label-danger">Scrap Sale</span>';
            })
            ->editColumn('quantity', function(PurchaseInventoryLog $log) {
                return number_format($log->quantity, 2).' '. $log->product->unit->name;
            })
            ->editColumn('unit_price', function(PurchaseInventoryLog $log) {
                if ($log->unit_price)
                    return ' '.number_format($log->unit_price, 2);
                else
                    return '';
            })
            ->editColumn('supplier', function(PurchaseInventoryLog $log) {
                if ($log->supplier)
                    return $log->supplier->name;
                else
                    return '';
            })
            ->addColumn('project', function(PurchaseInventoryLog $log) {
                return $log->project->name ?? '';
            })
            ->addColumn('warehouse_name', function(PurchaseInventoryLog $log) {
                return $log->warehouse->name ?? '';
            })
            ->addColumn('segment_name', function(PurchaseInventoryLog $log) {
                return $log->segment->name ?? '';
            })
            ->orderColumn('date', function ($query, $order) {
                $query->orderBy('date', $order)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['type','purchase_order','requisition_no'])
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

    public function purchaseProductJson(Request $request) {
        if (!$request->searchTerm) {
            $products = PurchaseProduct::where('status', 1)->orderBy('name')->limit(10)->get();
        } else {
            $products = PurchaseProduct::where('status', 1)->where('name', 'like', '%'.$request->searchTerm.'%')->orderBy('name')->limit(10)->get();
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

    public function estimateProductJson(Request $request) {
        if (!$request->searchTerm) {
            $products = EstimateProduct::where('status', 1)->orderBy('name')->limit(10)->get();
        } else {
            $products = EstimateProduct::where('status', 1)->where('name', 'like', '%'.$request->searchTerm.'%')->orderBy('name')->limit(10)->get();
        }

        $data = array();

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'text' => $product->name,
                'unit_name' => $product->unit->name,
            ];
        }

        echo json_encode($data);
    }

    public function productGetPurchaseOrder(Request $request) {


        $orders =  PurchaseInventory::with('purchase_order')
            ->where('purchase_product_id',$request->product)
            ->where('quantity','>',0)
            ->get()->toArray();
        //dd($orders);


//        $orders = PurchaseOrder::whereHas('products', function($q) use ($request) {
//            $q->where('purchase_product_id', '=', $request->product);
//        })->get()->toArray();

        return response()->json($orders);
    }

    public function getInventoryStockDetails(Request $request)
    {
//
//        $stock =  PurchaseInventory::with('purchase_order')->where('purchase_order_id', $request->orderId)
//            ->where('purchase_product_id',$request->product_id)
//            ->where('quantity','>',0)
//            ->first()->toArray();
//        dd($stock);

        $stock = PurchaseInventory::where('purchase_order_id', $request->orderId)
            ->where('purchase_product_id',$request->product_id)
            ->first()->toArray();


        return response()->json($stock);

    }

    public function purchaseOrderDelete(Request $request){
        $order =  PurchaseOrder::find($request->orderId);
        if($order->supplier_invoice){
            unlink(public_path($order->supplier_invoice));
        }
        DB::table('purchase_order_purchase_product')->where('purchase_order_id',$order->id)->delete();
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Purchase Order Deleted.', 'redirect_url' => route('purchase_receipt.all')]);

    }

    public function supplierPaymentEdit(PurchasePayment $payment){
        // dd($payment);
        $orders= PurchaseOrder::where('supplier_id',$payment->supplier_id)->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        return view('trading_purchase.supplier_payment.edit', compact( 'banks','payment','orders'));

    }

    public function supplierPaymentEditPost(Request $request,PurchasePayment $payment){
//        dd($payment);
        $rules = [
            'order' => 'required',
            'project' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $supplier=Supplier::find($payment->supplier_id);

        $rules['amount'] = 'required|numeric|min:0|max:'.$supplier->due;


        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            if ($request->payment_type == 1) {
                $cash = Cash::first();

                if ($request->amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }elseif ($request->payment_type == 2) {
                if ($request->account != '') {
                    $account = BankAccount::find($request->account);

                    if ($request->amount > $account->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
            }

        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $order=PurchaseOrder::find($payment->purchase_order_id);


        if ($payment->transaction_method == 1 ) {

            Cash::first()->increment('amount', $payment->amount);
            TransactionLog::where("purchase_payment_id",$payment->id)->delete();
            $supplier->decrement('paid', $payment->amount);
            $supplier->increment('due', $payment->amount);
            $order->decrement('paid', $payment->amount);
            $order->increment('due', $payment->amount);
            $payment->delete();

        } elseif($payment->transaction_method == 2 ) {

            BankAccount::find($payment->bank_account_id)->increment('balance', $payment->amount);
            TransactionLog::where("purchase_payment_id",$payment->id)->delete();
            $supplier->decrement('paid', $payment->amount);
            $supplier->increment('due', $payment->amount);
            $order->decrement('paid', $payment->amount);
            $order->increment('due', $payment->amount);
            $payment->delete();

        }else{

            $supplier->decrement('discount', $payment->amount);
            $supplier->increment('due', $payment->amount);
            $order->increment('due', $payment->amount);
            $payment->delete();
        }

//        update
        if ($request->payment_type == 1 ) {
            $payment = new PurchasePayment();
            $payment->purchase_order_id = $request->order;
            $payment->project_id = $request->project;
            $payment->supplier_id = $request->supplier;
            $payment->transaction_method = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();

            Cash::first()->decrement('amount', $request->amount);


            $log = new TransactionLog();
            $log->date = $request->date;
            $log->project_id = $request->project;
            $log->supplier_id = $request->supplier;
            $log->particular = 'Paid to '.$supplier->name;
            $log->transaction_type = 3;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 1;
            $log->account_head_sub_type_id = 1;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->purchase_payment_id = $payment->id;
            $log->save();
            $supplier->increment('paid', $request->amount);
            $order->increment('paid', $request->amount);
            $supplier->decrement('due', $request->amount);
            $order->decrement('due', $request->amount);

        } elseif($request->payment_type == 2 ) {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/purchase_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/purchase_payment_cheque/'.$filename;
            }

            $payment = new PurchasePayment();
            $payment->purchase_order_id = $request->order;
            $payment->project_id = $request->project;
            $payment->supplier_id = $request->supplier;
            $payment->transaction_method = 2;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->cheque_image = $image;
            $payment->amount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();

            BankAccount::find($request->account)->decrement('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->project_id = $request->project;
            $log->supplier_id = $request->supplier;
            $log->particular = 'Paid to '.$supplier->name;
            $log->transaction_type = 3;
            $log->transaction_method = 2;
            $log->account_head_type_id = 1;
            $log->account_head_sub_type_id = 1;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->purchase_payment_id = $payment->id;
            $log->save();
            $supplier->increment('paid', $request->amount);
            $order->increment('paid', $request->amount);
            $supplier->decrement('due', $request->amount);
            $order->decrement('due', $request->amount);
        }else{
            $payment = new PurchasePayment();
            $payment->purchase_order_id = $request->order;
            $payment->project_id = $request->project;
            $payment->supplier_id = $request->supplier;
            $payment->transaction_method = $request->payment_type;
            $payment->discount = $request->amount;
            $payment->date = $request->date;
            $payment->note = $request->note;
            $payment->save();
            $supplier->increment('discount', $request->amount);
            $supplier->decrement('due', $request->amount);
            $order->decrement('due', $request->amount);
        }

        return response()->json(['success' => true, 'message' => 'Payment Update has been completed.', 'redirect_url' => route('purchase_receipt.payment_details', ['payment' => $payment->id])]);

    }
}
