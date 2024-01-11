<?php

namespace App\Http\Controllers;

use App\Model\BankAccount;
use App\Model\ProductPurchaseOrder;
use App\Model\ProductRequisitionsDetail;
use App\Model\Project;
use App\Model\ProjectCash;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrder;
use App\Model\PurchasePayment;
use App\Model\PurchaseProduct;
use App\Model\Requisition;
use App\Model\Supplier;
use App\Model\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use function Sodium\increment;

class RequisitionPurchaseControlller extends Controller
{
    public function requisitionPurchaseOrder(Requisition $requisition){
        $suppliers = Supplier::where('status', 1)->orderBy('name')->get();
        return view('requisition.purchase.create',compact('requisition','suppliers'));
    }

    public function requisitionPurchaseOrderPost(Requisition $requisition,Request $request){
//        dd($request->all());

//        try{
//            DB::beginTransaction(); // Tell all the code beneath this is a transaction

        $rules = [
            'supplier' => 'required',
            'date' => 'required|date',
            /*'purchase_quantity.*' => 'required|numeric|min:.1',*/
            // 'purchase_quantity.*'=>'required|numeric|min:.1|max:'. $request->approved_quantity,
            'purchase_unit_price.*' => 'required|numeric|min:1',
            'vat' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0|max:' . $request->total,
        ];

        $request->validate($rules);

        //Supplier Payment balance check
        if ($request->paid > 0) {
            $cash = ProjectCash::where('project_id',$requisition->project_id)->first();
            if ($request->paid > $cash->amount) {
                return redirect()->back()
                    ->withInput()
                    ->with('message', 'Insufficient balance');
            }
        }

        $previousProductsId = [];

        foreach ($requisition->requisitionDetails as $requisitionDetail){
            $previousProductsId[] = $requisitionDetail->id;
        }

        $orderId = PurchaseOrder::count();
        if($orderId == 0) {
            $orderNo = 1000001;
        }else{
            $orderNo = 1000000;
            $orderNo = $orderId + 100000 + 1;
        }

        $order = new PurchaseOrder();
        $order->order_no = $orderNo;
        $order->supplier_id = $request->supplier;
        $order->requisition_id = $requisition->id;
        $order->project_id = $requisition->project_id;
        $order->segment_id = $requisition->product_segment_id;
        $order->date = $request->date;
        // $order->vat_percentage = $request->vat;  
        $order->vat = 0;
        // $order->discount_percentage = $request->discount;
        $order->discount = 0;
        $order->paid = $request->paid;
        $order->total = 0;
        $order->due = 0;
        // $order->user_id = Auth::id();
        $order->save();


        $counter = 0;
        $subTotal = 0;
        foreach ($request->purchase_product as $reqProduct) {

            $product = PurchaseProduct::find($reqProduct);

            $order->products()->attach($reqProduct, [
                'name' => $product->name,
                // 'unit' => $product->unit->name,
                'quantity' => $request->quantity[$counter],
                // 'unit_price' => $request->unit_price[$counter],
                // 'total' => $request->quantity[$counter] * $request->unit_price[$counter],
            ]);


            // $product = PurchaseProduct::where('id', $reqProduct)->first();

            // $productPurchaseOrder = new ProductPurchaseOrder();

            // $productPurchaseOrder->purchase_order_id = $order->id;
            // $productPurchaseOrder->name = $product->name;
            // $productPurchaseOrder->product_id = $product->id;
            // $productPurchaseOrder->project_id = $requisition->project_id;
            // $productPurchaseOrder->segment_id = $requisition->product_segment_id;
            // $productPurchaseOrder->quantity = $request->purchase_quantity[$counter];
            // $productPurchaseOrder->unit_price = $request->purchase_unit_price[$counter];
            // $productPurchaseOrder->total = $request->purchase_quantity[$counter] * $request->purchase_unit_price[$counter];
            // $productPurchaseOrder->save();

            // $total += $request->quantity[$counter] * $request->unit_price[$counter];
            // $counter++;

            $subTotal += $request->purchase_quantity[$counter] * $request->purchase_unit_price[$counter];

            // Inventory
            $inventoryCheck = PurchaseInventory::where('purchase_product_id', $product->id)
                ->where('purchase_product_id', $requisition->project_id)
                ->where('segment_id', $requisition->product_segment_id)
                ->first();
            if ($inventoryCheck) {

                $inventoryCheck->increment('quantity', $request->purchase_quantity[$counter]);
                $inventoryCheck->last_unit_price = $request->purchase_unit_price[$counter];
                $inventoryCheck->avg_unit_price = $request->purchase_unit_price[$counter];
                $inventoryCheck->save();
                // $inventoryCheck->total = $inventoryCheck->unit_price * $inventoryCheck->quantity;
                $inventoryCheck->save();
            } else {
                $inventory = new PurchaseInventory();
                $inventory->purchase_product_id = $product->id;
                $inventory->project_id = $requisition->project_id;
                $inventory->segment_id = $requisition->product_segment_id;
                $inventory->quantity = $request->purchase_quantity[$counter];
                $inventory->last_unit_price = $request->purchase_unit_price[$counter];
                $inventory->avg_unit_price = $request->purchase_unit_price[$counter];
                // $inventory->total = $request->purchase_quantity[$counter] * $request->purchase_unit_price[$counter];
                $inventory->save();
            }

            // Inventory Log
            $inventoryLog = new PurchaseInventoryLog();
            $inventoryLog->purchase_product_id = $product->id;
            $inventoryLog->project_id = $requisition->project_id;
            $inventoryLog->segment_id = $requisition->product_segment_id;
            $inventoryLog->supplier_id = $request->supplier;
            $inventoryLog->purchase_order_id = $order->id;
            $inventoryLog->requisition_id = $requisition->id;
            $inventoryLog->type = 1;
            $inventoryLog->date = $request->date;
            $inventoryLog->quantity = $request->purchase_quantity[$counter];
            $inventoryLog->unit_price = $request->purchase_unit_price[$counter];
            $inventoryLog->note = $request->note;
            // $inventoryLog->total = $request->purchase_quantity[$counter] * $request->purchase_unit_price[$counter];

            $inventoryLog->save();

            $counter++;
        }

        $order->sub_total = $subTotal;
        $vat = ($subTotal * $request->vat) / 100;
        $discount = ($subTotal * $request->discount) / 100;
        $order->vat = $vat;
        $order->discount = $discount;
        $total = $subTotal + $vat - $discount;
        $order->total = $total;
        $due = $total - $request->paid;
        $order->due = $due;
        $order->save();

        // Supplier Payment
        if ($request->paid > 0) {
            $payment = new PurchasePayment();
            $payment->supplier_id = $request->supplier;
            $payment->purchase_order_id = $order->id;
            $payment->transaction_method = 4;//project cash
            $payment->type = 1; //payment
            $payment->amount = $request->paid;
            $payment->date = $request->date;
            $payment->note = 'Payment for order no-' . $order->order_no;
            $payment->save();

            ProjectCash::first()->decrement('amount', $request->paid);

            $log = new TransactionLog();
            $log->project_id = $requisition->project_id;
            $log->segment_id = $requisition->product_segment_id;
            $log->supplier_id = $request->supplier;
            $log->requisition_id = $requisition->id;
            $log->date = $request->date;
            $log->particular = 'Paid to order no-' . $order->id;
            $log->transaction_type = 2;  //Expense
            $log->transaction_method = 4; // project cash
            $log->account_head_type_id = 1;
            $log->account_head_sub_type_id = 1;
            $log->amount = $request->paid;
            $log->purchase_payment_id = $payment->id;

            $log->save();
        }

            DB::commit(); //  this transacion's all good and it can persist to DB
        return redirect()->route('purchase_receipt.details', ['order' => $order->id]);
//        }
//        catch(\Exception $e){
//
//            DB::rollBack(); //  "It's not you, it's me. Please don't persist to DB"
//            return redirect()->back()->with('message',$e->getMessage());
//        }
    }

    public function projectProductInventory(){
        return view('requisition.inventory.all_project');
    }
    public function inventoryViewDatatable()
    {
        if (Auth::user()->role == 1){
            $query = Project::where('status',1);
        }else{
            $query = Project::where('id',Auth::user()->project_id);
        }

        return \Yajra\DataTables\Facades\DataTables::eloquent($query)
            ->addColumn('action', function (Project $project) {
                return '<a class="btn btn-info btn-sm" href="' . route('requisition.purchase.inventory.all', ['project' => $project->id]) . '">View Inventory</a>';
            })
            ->editColumn('status', function (Project $project) {
                if ($project->status == 1)
                    return '<span class="badge badge-success">Active</span>';
                else
                    return '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function requisitionPurchaseInventory(Project $project)
    {
        $inventories = PurchaseInventory::where('project_id',$project->id)->get();

        return view('requisition.inventory.all', compact('inventories','project'));
    }

}
