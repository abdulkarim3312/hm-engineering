<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Model\TransactionLog;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index() {
        $contractors = Vendor::where('status', 1)->get();
        return view('vendor.all',compact('contractors'));
    }

    public function add() {
        $projects = Project::where('status', 1)->get();
        return view('vendor.add', compact('projects'));
    }

    public function addPost(Request $request) {
    //    dd($request->all());
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

        $vendor = new Vendor();
        $vendor->name = $request->name;
        $vendor->vendor_id = $request->vendor_id;
        $vendor->purpose = $request->contractor_id;
        $vendor->image = $imagePath;
        $vendor->mobile = $request->mobile;
        $vendor->email = $request->email;
        $vendor->nid = $request->nid;
        $vendor->total = $request->total;
        $vendor->due = $request->total;
        $vendor->address = $request->address;
        $vendor->status = $request->status;
        $vendor->save();

        return redirect()->route('vendor.all')->with('message', 'Vendor add successfully.');
    }

    public function edit(Vendor $vendor) {
        $projects = Project::where('status', 1)->get();
        return view('vendor.edit', compact('vendor','projects'));
    }

    public function editPost(Vendor $vendor, Request $request) {
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

        $imagePath = $vendor->image;

        if ($request->image) {

            if ($vendor->image){
                // Previous Photo
                $previousPhoto = public_path($vendor->image);
                unlink($previousPhoto);
            }

            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getcontractorOriginalExtension();
            $destinationPath = 'public/uploads/contractor';
            $file->move($destinationPath, $filename);

            $imagePath = 'uploads/contractor/'.$filename;
        }
        $vendor->name = $request->name;
        $vendor->vendor_id = $request->vendor_id;
        $vendor->purpose = $request->contractor_id;
        $vendor->image = $imagePath;
        $vendor->mobile = $request->mobile;
        $vendor->email = $request->email;
        $vendor->nid = $request->nid;
        $vendor->total = $request->total;
        $vendor->due = $request->total;
        $vendor->address = $request->address;
        $vendor->status = $request->status;
        $vendor->save();


        return redirect()->route('vendor.all')->with('message', 'Vendor edit successfully.');
    }
    public function vendorDelete(Vendor $vendor){
        Vendor::find($vendor->id)->delete();
        return redirect()->route('vendor.all')->with('message', 'Vendor Info Deleted Successfully.');
    }


    public function datatable() {
        $query = Vendor::with('projects');

        return DataTables::eloquent($query)
            ->addColumn('project', function(Vendor $vendor) {
                return $vendor->projects->name ?? '';
            })
            ->addColumn('action', function(Vendor $vendor) {
                $btn = '';
                $btn = '<a class="btn btn-info btn-sm" href="'.route('vendor.edit', ['vendor' => $vendor->id]).'">Edit</a>';
                $btn .= '<a href="'.route('vendor.delete', ['vendor' => $vendor->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('image', function(Vendor $vendor) {
                return '<img width="50px" heigh="50px" src="'.asset($vendor->image ?? 'img/no_image.png').'" />';
            })
            ->editColumn('total', function(Vendor $vendor) {
                return ' '.number_format($vendor->total, 2);
            })
            ->editColumn('paid', function(Vendor $vendor) {
                return ' '.number_format($vendor->paid, 2);
            })
            ->editColumn('due', function(Vendor $vendor) {
                return ' '.number_format($vendor->due, 2);
            })
            ->editColumn('trade', function (Vendor $vendor) {
                return $vendor->purpose;
            })
            ->editColumn('status', function(Vendor $vendor) {
                if ($vendor->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status','image','trade'])
            ->toJson();
    }
    public function vendorPayment() {
        $vendors = Vendor::where('status',1)->get();
        $projects = Project::where('status', 1)->get();
        return view('vendor.payment_all', compact('vendors','projects'));
    }
    public function vendorList() {
        $vendors = Vendor::where('status',1)->get();
        return view('vendor.vendor_list', compact('vendors'));
    }
    public function makePayment(Request $request) {
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
        $vendor =Vendor::find($request->supplier);
       
        $rules['amount'] = 'required|numeric|min:0|max:'.$vendor->due;


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $vendor->increment('paid', $request->amount);
        $vendor->decrement('due', $request->amount);

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

        $receiptPayment->project_id = $request->project;
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
        $receiptPayment->contractor_id = null;
        $receiptPayment->vendor_id = $vendor->id;
        $receiptPayment->customer_id = $vendor->vendor_id;
        $receiptPayment->sub_total = $request->amount;
        $receiptPayment->net_amount = $request->amount;
        $receiptPayment->purchase_order_id = $vendor->id;
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
        $log->project_id = $vendor->project_id;
        $log->receipt_payment_no = $voucherNo;
        $log->receipt_payment_sl = $receiptPaymentNoSl;
        $log->financial_year = financialYear($request->financial_year);
        $log->client_id = null;
        $receiptPayment->contractor_id = null;
        $receiptPayment->vendor_id = $vendor->id;
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
        $log->purchase_order_id = $vendor->id;
        $log->amount = $request->amount;
        $log->notes = $request->note;
        $log->save();


        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('voucher_details', ['receiptPayment' => $receiptPayment->id])]);
    }

    public function vendorPaymentDetails(Vendor $vendor)
    {
        return view('vendor.vendor_payment_details',compact('vendor'));
    }

    public function vendorPaymentDatatable() {
        $query = ReceiptPayment::with('accountHead','receiptPaymentDetails','vendor')
            ->whereIn('transaction_type',[1,2])
            ->where('vendor_id',request('vendor_id'))
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
            ->addColumn('vendor_name', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->vendor->name ?? '';
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
}
