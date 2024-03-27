<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Cash;
use App\Model\Costing;
use App\Model\CostingSegment;
use App\Model\EstimateProduct;
use App\Model\EstimateProject;
use App\Model\MobileBanking;
use App\Model\ProductPurchaseOrder;
use App\Model\ProductRequisition;
use App\Model\ProductRequisitionLog;
use App\Model\ProductRequisitionsDetail;
use App\Model\ProductSegment;
use App\Model\Project;
use App\Model\ProjectCash;
use App\Model\PurchaseInventory;
use App\Model\PurchasePayment;
use App\Model\PurchaseProduct;
use App\Model\PurchaseProductProductCosting;
use App\Model\Requisition;
use App\Model\Supplier;
use App\Model\TransactionLog;
use App\Model\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;
use Ramsey\Uuid\Uuid;
use function Sodium\increment;

class RequisitionController extends Controller
{
    public function index(){
        return view('requisition.receipt.all');
    }

    public function add(){
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $projects = Project::where('status', 1)->get();
        $products = PurchaseProduct::where('status', 1)->get();
        return view('requisition.create_requisition.create', compact(
            'warehouses',  'projects', 'products'));
    }

    public function addPost(Request $request)
    {
        // dd($request->all());
        $rules = [
            'project' => 'required',
            //'segment' => 'required',
            'date' => 'required|date',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:.1',
        ];
        $request->validate($rules);

        $requisition = new Requisition();
        $requisition->project_id = $request->project;
        //$requisition->segment_id = $request->segment;
        $requisition->date = $request->date;
        $requisition->total_quantity = 0;
        $requisition->note = $request->note;
        $requisition->user_id = Auth::id();
        $requisition->status = 0;
        $requisition->save();
        $requisition->requisition_no = str_pad($requisition->id, 8, 0, STR_PAD_LEFT);
        $requisition->save();

        $counter = 0;
        $subTotal = 0;
        foreach ($request->product as $reqProduct) {
            $product = PurchaseProduct::where('id', $reqProduct)->first();

            ProductRequisition::create([
                'requisition_id' => $requisition->id,
                'purchase_product_id' => $product->id,
                'name' => $product->name,
                'unit_id' => $product->unit_id,
                'quantity' => $request->quantity[$counter],
                'approved_quantity' => 0,
                'status' => 0,
            ]);
            $subTotal += $request->quantity[$counter];
            $counter++;
        }

        $requisition->total_quantity = $subTotal;
        $requisition->save();

        return redirect()->route('requisition.details', ['requisition' => $requisition->id]);
    }

    public function requisitionReceiptDetails(Requisition $requisition)
    {
        return view('requisition.receipt.details', compact('requisition'));
    }

    public function requisitionApproved(Requisition $requisition){

        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();
        $projects = Project::where('status', 1)->get();
        $products = PurchaseProduct::where('status', 1)->get();

        return view('requisition.receipt.approved',
            compact('requisition','warehouses','projects','products'));

    }

    public function requisitionApprovedPost(Requisition $requisition, Request $request){
        // dd($request->all());
        $rules = [
            'approved_quantity.*' => 'required|numeric|min:.0',
            'approved_note' => 'nullable',
        ];
        $request->validate($rules);

        $available = true;
        $message = '';
        $first_counter = 0;
        // $requisitionQuantity = Requisition::where('project_id', $request->id)
        // ->first();
        // $requisitionQuantity->requisition_status = 1;
         foreach ($requisition->requisitionProducts as $requisitionProduct) {

                $requisitionQuantity = ProductRequisition::where('id', $requisitionProduct->id)
                    ->first();
                if ($request->approved_quantity[$first_counter] > $requisitionQuantity->quantity) {
                    $available = false;
                    $message = 'Approved Quantity Not Greater Than Requisition Quantity ' . $requisitionQuantity->name;
                    break;
                }

             $first_counter++;
            }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $counter = 0;
        $totalApprovedQuantity = 0;

        foreach ($requisition->requisitionProducts as $requisitionProduct) {

            $requisitionProduct->increment('approved_quantity', $request->approved_quantity[$counter]);
            $requisitionProduct->increment('purchase_due_quantity', $request->approved_quantity[$counter]);

            if ($requisitionProduct->approved_quantity > 0){
                $requisitionProduct->status = 1;
                $requisitionProduct->save();
            }
            $totalApprovedQuantity += $request->approved_quantity[$counter];

            $counter++;
        }

        $requisition->increment('total_approved_quantity', $totalApprovedQuantity);

        $requisition->update([
            'approved_note' => $request->approved_note,
        ]);

        $requisition->status = 1;
        $requisition->save();

        return redirect()->route('requisition.details', ['requisition' => $requisition->id]);

    }


    public function datatable()
    {
        $query = Requisition::with('project','segment');

        return DataTables::eloquent($query)

            ->addColumn('action', function (Requisition $requisition) {

                if ($requisition->status == 0){
                    return '<a href="' . route('requisition.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm">Details</a>
                     <a href="' . route('requisition.approved', ['requisition' => $requisition->id]) . '" class="btn btn-warning btn-sm">Approve</a>';
                }else{
                    return '<a href="' . route('requisition.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm">Details</a>';
                }

            })

            ->addColumn('project_name', function (Requisition $requisition) {
                return $requisition->project->name ?? '';
            })
            ->addColumn('segment_name', function (Requisition $requisition) {
                return $requisition->segment->name ?? '';
            })
            ->editColumn('date', function (Requisition $requisition) {
                return $requisition->date->format('d-m-Y');
            })
            ->editColumn('requisition_no', function (Requisition $requisition) {
                return $requisition->requisition_no;
            })
            ->editColumn('total_quantity', function (Requisition $requisition) {
                return  $requisition->total_quantity;
            })
            ->editColumn('total_approved_quantity', function (Requisition $requisition) {
                return  $requisition->approvedQuantity();
            })
            ->editColumn('status', function (Requisition $requisition) {
                if ($requisition->status == 0)
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>';
                else{
                    return '<span class="badge badge-success" style="background: #04D89D;">Approved</span>';

                }
            })
            ->orderColumn('date', function ($query, $requisition) {
                $query->orderBy('date', $requisition)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }

    public function projectRequisitionAllDatatable()
    {
        $query = Requisition::with('project','segment');

        return DataTables::eloquent($query)

            ->addColumn('action', function (Requisition $requisition) {
                if($requisition->status == 0){
                    return '
                    <a href="' . route('product_requisition_approve', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm btn-sm mb-1">Approve</a>
                    <a href="' . route('requisition_receipt.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm" title="view"><i class="fa fa-eye"></i></a> ';

                }elseif($requisition->status == 1){
                    return '
                    <a href="' . route('requisition_receipt.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm" title="view"><i class="fa fa-eye"></i></a>
                    <a href="' . route('project_requisition_payment.details', ['requisition' => $requisition->id]) . '" class="btn btn-info btn-sm" title="details"><i class="fa fa-solid fa-list"></i></a>
                    ';
                }
                else{
                    return '<a href="' . route('requisition_receipt.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a> ';
                }

            })

            ->editColumn('date', function (Requisition $requisition) {
                return $requisition->date;
            })
            ->editColumn('requisition_no', function (Requisition $requisition) {
                return $requisition->requisition_no;
            })
            ->editColumn('total', function (Requisition $requisition) {
                return '৳' . number_format($requisition->total, 2);
            })
            ->editColumn('status', function (Requisition $requisition) {
                if ($requisition->status == 0)
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>';
                else{
                    return '<span class="badge badge-success" style="background: #04D89D;">Approved</span>';

                }
            })
            ->editColumn('account_status', function (Requisition $requisition) {
                if ($requisition->accounts_status == 0)
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>';
                else{
                    return '<span class="badge badge-success" style="background: #04D89D;">Approved</span>';

                }
            })
            ->orderColumn('date', function ($query, $requisition) {
                $query->orderBy('date', $requisition)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','status','project_name','segment_name','account_status'])
            ->toJson();
    }

    public function projectRequisitionAllDatatableAccounts()   //Complete
    {
        $query = Requisition::where('status',1)->with('project','segment');

        return DataTables::eloquent($query)

            ->addColumn('action', function (Requisition $requisition) {
                if($requisition->status == 0){
                    return '
                    <a href="' . route('product_requisition_approve', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm" style="background: #5A6268; color:#fff;">Approve</a>
                    <a href="' . route('requisition_receipt.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm" title="view"><i class="fa fa-eye"></i></a>
                    ';

                }elseif($requisition->status == 1 && $requisition->accounts_status == 0){
                    return '
                    <a href="' . route('requisition_receipt.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm" title="view"><i class="fa fa-eye"></i></a>
                    <a href="' . route('product_requisition_approve_account', ['requisition' => $requisition->id]) . '" class="btn btn-secondary btn-sm" style="background: #5A6268; color:#fff;">Approve</a>
                    <a href="' . route('project_requisition_payment.details', ['requisition' => $requisition->id]) . '" class="btn btn-info btn-sm" title="details"><i class="fa fa-solid fa-list"></i></a>
                    ';
                }
                else{
                    return '<a href="' . route('requisition_receipt.details', ['requisition' => $requisition->id]) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="' . route('project_requisition_payment.details', ['requisition' => $requisition->id]) . '" class="btn btn-info btn-sm" title="details"><i class="fa fa-solid fa-list"></i></a>

                    ';
                }

            })

            ->editColumn('date', function (Requisition $requisition) {
                return $requisition->date;
            })
            ->editColumn('requisition_no', function (Requisition $requisition) {
                return $requisition->requisition_no;
            })
            ->editColumn('total', function (Requisition $requisition) {
                return '৳' . number_format($requisition->total, 2);
            })
            ->editColumn('status', function (Requisition $requisition) {
                if ($requisition->status == 0)
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>';
                else{
                    return '<span class="badge badge-success" style="background: #04D89D;">Approved</span>';

                }
            })
            ->editColumn('account_status', function (Requisition $requisition) {
                if ($requisition->accounts_status == 0)
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>';
                else{
                    return '<span class="badge badge-success" style="background: #04D89D;">Approved</span>';

                }
            })
            ->orderColumn('date', function ($query, $requisition) {
                $query->orderBy('date', $requisition)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action','status','project_name','segment_name','account_status'])
            ->toJson();
    }

//    public function requisitionReceiptDetails(Requisition $requisition)    //Complete
//    {
//        return view('requisition.product_requisition_details', compact('requisition'));
//    }

    public function productRequisitionAll(){    //Complete
        return view('requisition.project_requisition');
    }

    public function accountProductRequisitionAll(){     //Complete
        return view('requisition.project_requisition_account');
    }

    public function productRequisitionApprove(Requisition $requisition){   //Complete
        $purchaseProducts  = PurchaseProduct::where('status', 1)->get();

        return view('requisition.product_requisition_approve',compact('requisition','purchaseProducts'));
    }

    public function productRequisitionApproveAccount(Requisition $requisition){   //Complete
        $purchaseProducts  = PurchaseProduct::where('status', 1)->get();
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        return view('requisition.account_requisition_approve',compact('requisition','purchaseProducts','banks'));
    }

    public function productRequisitionApprovePost(Requisition $requisition,Request $request){
        $rules = [
            'quantity.*' => 'required|numeric|min:1',
            'unit_price.*' => 'required|numeric|min:1',
        ];
        $request->validate($rules);

        $requisition->approved_id = Auth::id();
        $requisition->approved_note = $request->note;
        $requisition->approved_at = Carbon::now();
        $requisition->status = 1; //1=confirm,0=pending,
        $requisition->save();

        $previousProductsId = [];

        foreach ($requisition->requisitionDetails as $requisitionDetail){
            $previousProductsId[] = $requisitionDetail->id;
        }

        $counter = 0;
        if ($request->product_requisition_id){
            foreach ($request->product_requisition_id as $productRequisition) {
                if (in_array($productRequisition, $previousProductsId)) {
                    $requisitionDetail = ProductRequisitionsDetail::where('id', $productRequisition)->first();
                    $requisitionDetail->approved_quantity = $request->quantity[$counter];
                    $requisitionDetail->approved_unit_price = $request->unit_price[$counter];
                    $requisitionDetail->approved_at = Carbon::now();
                    $requisitionDetail->save();
                }else{
                    ProductRequisitionsDetail::where('id',$productRequisition)->update([
                        'cancel_user_id' => Auth::id()
                    ]);
                }

                $counter++;
            }
        }

        return redirect()->route('requisition_receipt.details', ['requisition' => $requisition->id]);

    }
    public function productRequisitionApproveAccountPost(Requisition $requisition,Request $request){

        try {
            DB::beginTransaction(); // db transaction
            $rules = [
                'payment_type' => 'required',
                'date' => 'required',
            ];

            if ($request->payment_type == '2') {
                $rules['bank'] = 'required';
                $rules['branch'] = 'required';
                $rules['account'] = 'required';
                $rules['cheque_no'] = 'nullable|string|max:255';
                $rules['cheque_image'] = 'nullable|image';
            }

            $request->validate($rules);
            //Requisition Payment balance check
            if ($requisition->total > 0) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();

                    if ($requisition->total > $cash->amount) {
                        return redirect()->back()
                            ->withInput()
                            ->with('message', 'Insufficient balance');
                    }
                } elseif ($request->payment_type == 3) {
                    $mobileBanking = MobileBanking::first();
                    if ($requisition->total > $mobileBanking->amount) {
                        return redirect()->back()
                            ->withInput()
                            ->with('message', 'Insufficient balance');
                    }
                } else {
                    $bankAccount = BankAccount::find($request->account);
                    if ($requisition->total > $bankAccount->balance) {
                        return redirect()->back()->withInput()->with('message', 'Insufficient balance');
                    }
                }
            }

            $requisition->account_note = $request->note;
            $requisition->accounts_status = 1;//1=approved
            $requisition->save();

            // Requisition Payment
            if ($requisition->total > 0) {
                if ($request->payment_type == 1 || $request->payment_type == 3) {
                    $payment = new PurchasePayment();
                    $payment->requisition_id = $requisition->id;
                    $payment->transaction_method = $request->payment_type;
                    // $payment->received_type = 1;
                    $payment->type = 1; //payment
                    $payment->amount = $requisition->total;
                    $payment->date = $request->date;
                    $payment->note = 'Payment for requisition no-' . $requisition->requisition_no;
                    $payment->save();

                    if ($request->payment_type == 1)
                        Cash::first()->decrement('amount', $requisition->total);
                    elseif ($request->payment_type == 3)
                        MobileBanking::first()->decrement('amount', $requisition->total);

                    $log = new TransactionLog();
                    $log->project_id = $requisition->project_id;
                    // $log->product_segment_id = $requisition->product_segment_id;
                    $log->date = $request->date;
                    $log->particular = 'Paid to requisition no-' . $requisition->requisition_no;
                    $log->transaction_type = 2;  //Expense
                    $log->transaction_method = $request->payment_type;
                    $log->account_head_type_id = 1;
                    $log->account_head_sub_type_id = 1;
                    $log->amount = $requisition->total;
                    $log->requisition_id = $requisition->id;

                    $log->save();
                } else {
                    $image = 'img/no_image.png';

                    if ($request->cheque_image) {
                        // Upload Image
                        $file = $request->file('cheque_image');
                        $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = 'public/uploads/purchase_payment_cheque';
                        $file->move($destinationPath, $filename);

                        $image = 'uploads/purchase_payment_cheque/' . $filename;
                    }

                    $payment = new PurchasePayment();
                    $payment->requisition_id = $requisition->id;
                    $payment->transaction_method = $request->payment_type;
                    $payment->type = 1; //payment
                    $payment->bank_id = $request->bank;
                    $payment->branch_id = $request->branch;
                    $payment->bank_account_id = $request->account;
                    $payment->cheque_no = $request->cheque_no;
                    $payment->cheque_image = $image;
                    $payment->amount = $requisition->total;
                    $payment->date = $request->date;
                    $payment->note = 'Payment for requisition no-' . $requisition->requisition_no;
                    $payment->save();

                    BankAccount::find($request->account)->decrement('balance', $requisition->total);

                    $log = new TransactionLog();
                    $log->project_id = $requisition->project_id;
                    // $log->product_segment_id = $requisition->product_segment_id;
                    $log->date = $request->date;
                    $log->particular = 'Paid to requisition no-' . $requisition->requisition_no;
                    $log->transaction_type = 2;  //Expense
                    $log->transaction_method = $request->payment_type;
                    $log->account_head_type_id = 1;
                    $log->account_head_sub_type_id = 1;
                    $log->bank_id = $request->bank;
                    $log->branch_id = $request->branch;
                    $log->bank_account_id = $request->account;
                    $log->cheque_no = $request->cheque_no;
                    $log->cheque_image = $image;
                    $log->amount = $requisition->total;
                    $log->purchase_payment_id = $payment->id;
                    $log->save();
                }
            }

            ProjectCash::find($requisition->project_id)->increment('amount', $requisition->total);

            DB::commit();
            return redirect()->route('requisition_receipt.payment_details', ['payment' => $payment->id]);
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
    public function requisitionPaymentDetails(PurchasePayment $payment)
    {
        $payment->amount_in_word = DecimalToWords::convert(
            $payment->amount,
            'Taka',
            'Poisa'
        );
        return view('requisition.receipt.payment_details', compact('payment'));
    }
    public function requisitionPaymentPrint(PurchasePayment $payment)
    {
        $payment->amount_in_word = DecimalToWords::convert(
            $payment->amount,
            'Taka',
            'Poisa'
        );
        return view('requisition.receipt.payment_print', compact('payment'));
    }
    public function projectRequisitionPaymentDetails(Requisition $requisition){
        return view('requisition.receipt.payment_details_all',compact('requisition'));
    }
}
