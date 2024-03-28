<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Model\TransactionLog;
use App\Models\BillStatement;
use App\Models\BillStatementDescription;
use App\Models\Contractor;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContractorController extends Controller
{
    public function contractorAll() {
        $contractors = Contractor::where('status', 1)->get();
        return view('labour.contractor.all',compact('contractors'));
    }

    public function contractorAdd() {
        $projects = Project::where('status', 1)->get();
        return view('labour.contractor.add', compact('projects'));
    }

    public function contractorAddPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable',
            'contractor_id' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'nid' => 'nullable',
            'mobile' => 'required|digits:11',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $imagePath = null;
        if ($request->image) {
            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/contractor';
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/contractor/'.$filename;
        }

        $contractor = new Contractor();
        $contractor->name = $request->name;
        $contractor->contractor_id = $request->contractor_id;
        $contractor->project_id = $request->project_id;
        $contractor->trade = $request->trade;
        $contractor->image = $imagePath;
        $contractor->mobile = $request->mobile;
        $contractor->nid = $request->nid;
        $contractor->email = $request->email;
        $contractor->due = $request->total;
        $contractor->address = $request->address;
        $contractor->status = $request->status;
        $contractor->save();

        return redirect()->route('contractor.all')->with('message', 'Contractor add successfully.');
    }

    public function contractorEdit(Contractor $contractor) {
        $projects = Project::where('status', 1)->get();
        return view('labour.contractor.edit', compact('contractor','projects'));
    }

    public function contractorEditPost(Contractor $contractor, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable',
            'contractor_id' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'nid' => 'nullable',
            'mobile' => 'required|digits:11',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $imagePath = $contractor->image;

        if ($request->image) {

            if ($contractor->image){
                // Previous Photo
                $previousPhoto = public_path($contractor->image);
                unlink($previousPhoto);
            }

            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getcontractorOriginalExtension();
            $destinationPath = 'public/uploads/contractor';
            $file->move($destinationPath, $filename);

            $imagePath = 'uploads/contractor/'.$filename;
        }
        $contractor->name = $request->name;
        $contractor->contractor_id = $request->contractor_id;
        $contractor->project_id = $request->project_id;
        $contractor->trade = $request->trade;
        $contractor->image = $imagePath;
        $contractor->mobile = $request->mobile;
        $contractor->email = $request->email;
        $contractor->nid = $request->nid;
        $contractor->due = $request->total;
        $contractor->address = $request->address;
        $contractor->status = $request->status;
        $contractor->save();


        return redirect()->route('contractor.all')->with('message', 'Contractor edit successfully.');
    }
    public function contractorDelete(Contractor $contractor){
        Contractor::find($contractor->id)->delete();
        return redirect()->route('contractor.all')->with('message', 'Contractor Info Deleted Successfully.');
    }

    public function contractorPaymentDetails(Contractor $contractor)
    {
        return view('labour.contractor.contractor_payment_details',compact('contractor'));
    }

    public function datatable() {
        $query = Contractor::with('projects');

        return DataTables::eloquent($query)
            ->addColumn('project', function(Contractor $contractor) {
                return $contractor->projects->name ?? '';
            })
            ->addColumn('action', function(Contractor $contractor) {
                $btn = '';
                $btn = '<a class="btn btn-info btn-sm" href="'.route('contractor.edit', ['contractor' => $contractor->id]).'">Edit</a>';
                $btn .= '<a href="'.route('contractor.delete', ['contractor' => $contractor->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('image', function(Contractor $contractor) {
                return '<img width="50px" heigh="50px" src="'.asset($contractor->image ?? 'img/no_image.png').'" />';
            })
            ->editColumn('total', function(Contractor $contractor) {
                return ' '.number_format($contractor->total, 2);
            })
            ->editColumn('paid', function(Contractor $contractor) {
                return ' '.number_format($contractor->paid, 2);
            })
            ->editColumn('due', function(Contractor $contractor) {
                return ' '.number_format($contractor->due, 2);
            })
            ->editColumn('trade', function (Contractor $contractor) {
                if ($contractor->trade == 1)
                    return '<span class="label label-success">Civil Contractor</span>';
                elseif($contractor->trade == 2)
                    return '<span class="label label-warning">Paint Contractor</span>';
                elseif($contractor->trade == 3)
                    return '<span class="label label-primary">Sanitary Contractor</span>';
                elseif($contractor->trade == 4)
                    return '<span class="label label-info">Tiles Contractor</span>';
                elseif($contractor->trade == 5)
                    return '<span class="label label-info">MS Contractor</span>';
                elseif($contractor->trade == 6)
                    return '<span class="label label-info">Wood Contractor</span>';
                elseif($contractor->trade == 7)
                    return '<span class="label label-info">Electric Contractor</span>';
                elseif($contractor->trade == 8)
                    return '<span class="label label-info">Thai Contractor</span>';
                else
                    return '<span class="label label-danger">Other Contractor</span>';
            })
            ->editColumn('status', function(Contractor $contractor) {
                if ($contractor->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status','image','trade'])
            ->toJson();
    }
    public function supplierPayment() {
        $contractors = Contractor::where('status',1)->get();
        $projects = Project::where('status', 1)->get();
        return view('labour.supplier_payment.all', compact('contractors','projects'));
    }


    public function contractorList() {
        $contractors = Contractor::where('status',1)->get();
        return view('labour.contractor.contractor_list', compact('contractors'));
    }

    public function makePayment(Request $request) {
        // dd($request->all());
        $rules = [
            'financial_year' => 'required',
            'project' => 'nullable',
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
        $contractor =Contractor::find($request->supplier);

        $rules['amount'] = 'required|numeric|min:0|max:'.$contractor->due;


        $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        // }
        $contractor->increment('paid', $request->amount);
        $contractor->decrement('due', $request->amount);

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

        if($request->project){
            $receiptPayment->project_id = $request->project;
        }else{
            $receiptPayment->project_id = $contractor->project_id;
        }
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
        $receiptPayment->client_id = null;
        $receiptPayment->contractor_id = $contractor->id;
        $receiptPayment->vendor_id = null;
        $receiptPayment->customer_id = $contractor->contractor_id;
        $receiptPayment->sub_total = $request->amount;
        $receiptPayment->net_amount = $request->amount;
        $receiptPayment->purchase_order_id = $contractor->id;
        $receiptPayment->notes = $request->note;
        $receiptPayment->save();


        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->narration = $request->note;
        $receiptPaymentDetail->account_head_id = $request->account;
        $receiptPaymentDetail->amount = $request->amount;
        $receiptPaymentDetail->net_amount = $request->amount;
        $receiptPaymentDetail->save();

        //Debit Head Amount
        $log = new TransactionLog();
        $log->notes = $request->note;
        $log->project_id = $contractor->project_id;
        $log->receipt_payment_no = $voucherNo;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = financialYear($request->financial_year);
        $log->client_id = null;
        $receiptPayment->vendor_id = null;
        if($request->project){
            $receiptPayment->contractor_id = $request->project;
        }else{
            $receiptPayment->contractor_id = $contractor->id;
        }
        $receiptPayment->vendor_id = null;
        $log->date = Carbon::parse($request->date)->format('Y-m-d');
        $log->receipt_payment_id = $receiptPayment->id;
        $log->receipt_payment_detail_id = $receiptPaymentDetail->id;
        $log->payment_type = $request->payment_type;
        if($request->payment_type == 1){
            $log->cheque_no = $request->cheque_no;
            $log->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        $log->transaction_type = 1;//Account Head Debit
        $log->account_head_id = $request->account;
        $log->purchase_order_id = $contractor->id;
        $log->amount = $request->amount;
        $log->notes = $request->note;
        $log->save();


        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('voucher_details', ['receiptPayment' => $receiptPayment->id])]);
    }

    public function contractorPaymentDatatable() {
        $query = ReceiptPayment::with('accountHead','receiptPaymentDetails','contractor')
            ->whereIn('transaction_type',[1,2])
            ->where('contractor_id',request('contractor_id'))
            ->whereNotNull('purchase_order_id')
            ->orderBy('date');
            // dd($query);
        return DataTables::eloquent($query)

            ->addColumn('action', function(ReceiptPayment $receiptPayment) {
                $btn = '';
                if ($receiptPayment->transaction_type == 2){
                    $btn .= ' <a target="_blank" href="'.route('voucher_print',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> ';
                    $btn .=' <a href="'.route('voucher_details',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> ';

                }else{
                    $btn .= ' <a target="_blank" href="'.route('receipt_details',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> ';
                    $btn .=' <a href="'.route('receipt_print',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> ';

                }

                return $btn;
            })

            ->editColumn('transaction_type', function(ReceiptPayment $receiptPayment) {
                if ($receiptPayment->transaction_type == 2)
                    return '<span class="label label-success">Payment</span>';
                else
                    return '<span class="label label-danger">Refund</span>';
            })
            ->addColumn('account_head', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->accountHead->name ?? '';
            })
            ->addColumn('contractor_name', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->contractor->name ?? '';
            })
            ->addColumn('expenses_code', function(ReceiptPayment $receiptPayment) {

                $codes = '<ul style="text-align: left;">';
                foreach ($receiptPayment->receiptPaymentDetails as $receiptPaymentDetails){
                    $codes .= '<li>'.($receiptPaymentDetails->accountHead->account_code ?? '').'</li>';
                }
                $codes .= '</ul>';

                return $codes;
            })
            ->editColumn('net_amount', function(ReceiptPayment $receiptPayment) {
                return number_format($receiptPayment->net_amount,2);
            })
            ->editColumn('date', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->date->format('d-m-Y');
            })
            ->rawColumns(['action','expenses_code','transaction_type'])
            ->toJson();
    }

    public function billStatement(Contractor $contractor){
        $projects = Project::where('status',1)->get();
        return view('labour.contractor.bill_statement', compact('projects','contractor'));
    }
    public function billStatementPost(Request $request){
        // dd($request->all());
        $request->validate([
            'estimate_project' => 'required',
            'cheque_holder_name' => 'required',
            'address' => 'required',
            'trade' => 'required',
        ]);

        $billStatement = new BillStatement();
        $billStatement->estimate_project = $request->estimate_project;
        $billStatement->trade = $request->trade;
        $billStatement->contractor = $request->contractor;
        $billStatement->address = $request->address;
        $billStatement->cheque_holder_name = $request->cheque_holder_name;
        $billStatement->date = $request->date;
        // dd($billStatement->date);
        // $billStatement->total_putty_area = 0;
        // $billStatement->total_outside_area = 0;
        // $billStatement->total_inside_area = 0;
        // $billStatement->total_polish_area = 0;
        $billStatement->save();



        $counter = 0;
        $totalPolishArea = 0;
        $totalPolishLiter = 0;
        foreach ($request->item_code as $key => $reqProduct) {
            // dd($request->item_code);
            BillStatementDescription::create([
                'bill_statements_id' => $billStatement->id,
                'estimate_project_id' => $request->estimate_project,
                'item_code' => $reqProduct,
                'work_description' => $request->work_description[$counter],
                'bill_no' =>  $request->bill_no[$counter],
                'quantity' => $request->quantity[$counter],
                'unit' => $request->unit[$counter],
                'rate' => $request->rate[$counter],
                't_amount' => $request->t_amount[$counter],
                'payable' => $request->payable[$counter],
                'payable_a' => $request->payable_a[$counter],
                'deduct_money' => $request->deduct_money[$counter],
                'n_amount' => $request->n_amount[$counter],
                'advance_amount' => 0,
                'approve_amount' => $request->approve_amount[$counter],
            ]);

            $totalPolishArea += $request->t_amount[$counter];
            $counter++;
        }
        return redirect()->route('bill_statement.details', ['billStatement' => $billStatement->id]);
    }

    public function billStatementList(BillStatement $billStatement){
        return view('labour.contractor.bill_list', compact('billStatement'));
    }
    public function billStatementDetails(BillStatement $billStatement){
        return view('labour.contractor.details',compact('billStatement'));
    }
    public function billStatementDetail(BillStatement $billStatement){
        return view('labour.contractor.detail',compact('billStatement'));
    }

    public function billStatementDataTable() {
        $query = BillStatement::with('project');

        return DataTables::eloquent($query)
            ->addColumn('project', function(BillStatement $billStatement) {
                return $billStatement->project->name ?? '';
            })
            ->addColumn('action', function(BillStatement $billStatement) {
                if ($billStatement->status == 0){
                    return '<a href="' . route('bill_statement.details', ['billStatement' => $billStatement->id]) . '" class="btn btn-primary btn-sm">Details</a>
                     <a href="' . route('bill.approved', ['billStatement' => $billStatement->id]) . '" class="btn btn-warning btn-sm">Approve</a>';
                }else{
                    return '<a href="' . route('bill_statement.details', ['billStatement' => $billStatement->id]) . '" class="btn btn-primary btn-sm">Details</a> <a href="' . route('bill_statement.detail', ['billStatement' => $billStatement->id]) . '" class="btn btn-primary btn-sm">View</a>';
                }
            })

            ->editColumn('trade', function (BillStatement $contractor) {
                if ($contractor->trade == 1)
                    return '<span class="label label-success">Civil Contractor</span>';
                elseif($contractor->trade == 2)
                    return '<span class="label label-warning">Paint Contractor</span>';
                elseif($contractor->trade == 3)
                    return '<span class="label label-primary">Sanitary Contractor</span>';
                elseif($contractor->trade == 4)
                    return '<span class="label label-info">Tiles Contractor</span>';
                elseif($contractor->trade == 5)
                    return '<span class="label label-info">MS Contractor</span>';
                elseif($contractor->trade == 6)
                    return '<span class="label label-info">Wood Contractor</span>';
                elseif($contractor->trade == 7)
                    return '<span class="label label-info">Electric Contractor</span>';
                elseif($contractor->trade == 8)
                    return '<span class="label label-info">Thai Contractor</span>';
                else
                    return '<span class="label label-danger">Other Contractor</span>';
            })
            ->editColumn('status', function(BillStatement $billStatement) {
                if ($billStatement->status == 0)
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000;">Pending</span>';
                else
                    return '<span class="badge badge-success" style="background: #04D89D;">Approved</span>';
            })
            ->rawColumns(['action', 'status','image','trade'])
            ->toJson();
    }

    public function billStatementApproval(BillStatement $billStatement){
        $projects = Project::where('status',1)->get();
        return view('labour.contractor.bill_approval',compact('billStatement','projects'));
    }
    public function billStatementApprovalPost(BillStatement $billStatement, Request $request){
        // dd($request->all());
        $rules = [
            'approved_quantity.*' => 'required|numeric|min:.0',
        ];
        $request->validate($rules);

        $available = true;
        $message = '';
        $first_counter = 0;

         foreach ($billStatement->billStatementDescription as $requisitionProduct) {

                $requisitionQuantity = BillStatementDescription::where('id', $requisitionProduct->id)
                    ->first();

                if ($request->approved_quantity[$first_counter] > $requisitionQuantity->quantity) {
                    $available = false;
                    $message = 'Approved Quantity Not Greater Than Requisition Quantity ' . $requisitionQuantity->quantity;
                    break;
                }
                if ($request->app_payable[$first_counter] > $requisitionQuantity->payable) {
                    $available = false;
                    $message = 'Approved Quantity Not Greater Than Requisition Quantity ' . $requisitionQuantity->quantity;
                    break;
                }

             $first_counter++;
            }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $counter = 0;
        $totalApprovedQuantity = 0;

        foreach ($billStatement->billStatementDescription as $requisitionProduct) {

            $requisitionProduct->increment('app_quantity', $request->approved_quantity[$counter]);
            $requisitionProduct->increment('app_t_amount', $request->approved_quantity[$counter] * $request->rate[$counter]);
            $requisitionProduct->increment('app_payable_a', $request->t_amount[$counter] * ($request->app_payable[$counter] / 100));
            $requisitionProduct->increment('app_deduct_money', $request->payable_a[$counter] * (5/100));
            $requisitionProduct->increment('app_n_amount', $request->payable_a[$counter] - $request->deduct_money[$counter]);
            $requisitionProduct->increment('advance_amount', $request->advance_amount[$counter]);
            $requisitionProduct->increment('approve_amount', $request->n_amount[$counter] - $request->advance_amount[$counter]);

            $requisitionProduct->increment('app_payable', $request->app_payable[$counter]);

            if ($requisitionProduct->approved_quantity > 0){
                $requisitionProduct->status = 1;
                $requisitionProduct->save();
            }
            if ($requisitionProduct->app_payable > 0){
                $requisitionProduct->status = 1;
                $requisitionProduct->save();
            }
            $counter++;
        }

        $billStatement->update([
            'approved_note' => $request->approved_note,
        ]);

        $billStatement->approved_date = $request->approved_date;
        $billStatement->status = 1;
        $billStatement->save();

        return redirect()->route('bill_statement.details', ['billStatement' => $billStatement->id]);
    }

    public function billStatementPrint(BillStatement $billStatement){
        return view('labour.contractor.print',compact('billStatement'));
    }
}
