<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\Project;
use App\Model\SalesOrder;
use App\Model\TransactionLog;
use App\Models\AccountHead;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use App\Models\ReceiptPaymentFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class ReceiptController extends Controller
{
    public function datatable() {
        $query = ReceiptPayment::with('accountHead','receiptPaymentDetails','client')
            ->where('transaction_type',1);
            // dd($query);
        return DataTables::eloquent($query)

            ->addColumn('action', function(ReceiptPayment $receiptPayment) {
                $btn = '';
                $btn .= ' <a target="_blank" href="'.route('receipt_print',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> ';
                $btn .=' <a href="'.route('receipt_details',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> ';
               if ($receiptPayment->sales_order_id == null)
                $btn .=' <a href="'.route('receipt.edit',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a> ';

                return $btn;
            })

            ->addColumn('account_head', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->accountHead->name ?? '';
            })
            ->addColumn('client_name', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->client->name ?? '';
            })
            ->addColumn('expenses_head', function(ReceiptPayment $receiptPayment) {

                $codes = '<ul style="text-align: left;">';
                foreach ($receiptPayment->receiptPaymentDetails as $receiptPaymentDetails){
                    $codes .= '<li>'.($receiptPaymentDetails->accountHead->name ?? '').'</li>';
                }
                $codes .= '</ul>';

                return $codes;
            })
            ->addColumn('expenses_code', function(ReceiptPayment $receiptPayment) {

                $codes = '<ul style="text-align: left;">';
                foreach ($receiptPayment->receiptPaymentDetails as $receiptPaymentDetails){
                    $codes .= '<li>'.($receiptPaymentDetails->accountHead->account_code ?? '').'</li>';
                }
                $codes .= '</ul>';

                return $codes;
            })
            ->addColumn('narrations', function(ReceiptPayment $receiptPayment) {

                $codes = '<ul style="text-align: left;list-style: none">';
                foreach ($receiptPayment->receiptPaymentDetails as $receiptPaymentDetails){
                    $codes .= '<li>'.($receiptPaymentDetails->narration ?? '').'</li>';
                }
                $codes .= '</ul>';

                return $codes;
            })
            ->addColumn('expenses_head', function(ReceiptPayment $receiptPayment) {

                $codes = '<ul style="text-align: left;">';
                foreach ($receiptPayment->receiptPaymentDetails as $receiptPaymentDetails){
                    $codes .= '<li>'.($receiptPaymentDetails->accountHead->name ?? '').'</li>';
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
            ->rawColumns(['action','expenses_code','expenses_head','narrations'])
            ->toJson();
    }
    public function salePaymentDatatable() {
        $query = ReceiptPayment::with('accountHead','receiptPaymentDetails','client')
            ->whereIn('transaction_type',[1,2])
            ->where('client_id',request('client_id'))
            ->whereNotNull('sales_order_id')
            ->orderBy('date');
        return DataTables::eloquent($query)

            ->addColumn('action', function(ReceiptPayment $receiptPayment) {
                $btn = '';
                if ($receiptPayment->transaction_type == 2){
                    $btn .= ' <a target="_blank" href="'.route('voucher_print',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> ';
                    $btn .=' <a href="'.route('voucher_details',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> ';
                   // $btn .=' <a href="'.route('voucher_details.edit',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a> ';

                }else{
                    $btn .= ' <a target="_blank" href="'.route('receipt_print',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> ';
                    $btn .=' <a href="'.route('receipt_details',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-info btn-sm"><i class="fa fa-info-circle"></i></a> ';
                    $btn .=' <a href="'.route('receipt_details.edit',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a> ';
                    //$btn .=' <a href="'.route('receipt_details.delete',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-delete"></i></a> ';
                    $btn .=' <button  class="btn btn-danger btn-sm btn-delete" data-id="' . $receiptPayment->id . '"><i class="fa fa-trash"></i></button>';

                }
                return $btn;
            })

            ->editColumn('transaction_type', function(ReceiptPayment $receiptPayment) {
                if ($receiptPayment->transaction_type == 1)
                    return '<span class="label label-success">Receipt</span>';
                else
                    return '<span class="label label-danger">Refund</span>';
            })
            ->addColumn('account_head', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->accountHead->name ?? '';
            })
            ->addColumn('client_name', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->client->name ?? '';
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
            ->editColumn('notes', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->notes ?? '';
            })
            ->editColumn('date', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->date->format('d-m-Y');
            })
            ->rawColumns(['action','expenses_code','transaction_type'])
            ->toJson();
    }
    public function purchasePaymentDatatable() {
        $query = ReceiptPayment::with('accountHead','receiptPaymentDetails','client')
            ->whereIn('transaction_type',[1,2])
            ->where('client_id',request('client_id'))
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
            ->addColumn('client_name', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->client->name ?? '';
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

    public function index()
    {
        return view('accounts.receipt.all');
    }
    public function create()
    {
        $projects = Project::where('status',1)->get();
        $clients = Client::where('type', 1)->get();
        // dd($clients);
        return view('accounts.receipt.create_voucher',compact('projects', 'clients'));
    }


    public function createPost(Request $request)
    {
        // dd($request->all());
        $rules = [
            'client_type'=>'required',
            'financial_year'=>'required',
            'payment_type'=>'required',
            'date'=>'required',
            'bank_cash_account_head'=>'required',
            'e_tin'=>'nullable|max:255',
            'designation'=>'nullable|max:255',
            'address'=>'nullable|max:255',
            'mobile_no'=>'nullable|digits:11',
            'email'=>'nullable|email',
            'nature_of_organization'=>'nullable|max:255',
            'account_head_code.*'=>'required',
            'amount.*'=>'required|numeric',
            'notes'=>'nullable|max:255',
            'narration.*'=>'nullable|max:255',
            'project'=>'required',
        ];
            if ($request->payment_type == 1){
                $rules['cheque_no']='required|max:255';
                $rules['cheque_date']='required|date';
                $rules['issuing_bank_name']='nullable|max:255';
                $rules['issuing_branch_name']='nullable|max:255';
            }

        if ($request->client_type == 1){
            $rules['payee'] = 'required';

        }else{
            $rules['payee_name']= 'required|unique:clients,name|max:255';
            $rules['customer_id']= 'nullable|max:255';
        }
        if ($request->payment_type == 1)
            $rules['cheque_no'] = 'required|max:255';

        $request->validate($rules);


        $counter = 0;
        $totalAmount = 0;
        $grandTotal = 0;

        if ($request->account_head_code){
            foreach ($request->account_head_code as $reqAccountHeadCode){

                $grandTotal += $request->amount[$counter] ?? 0;
                $totalAmount += $request->amount[$counter] ?? 0;
                $counter++;
            }

        }

        //create dynamic voucher no process start
        $transactionType = 1;
        $financialYear = $request->financial_year;
        $cashBankAccountHeadId = $request->bank_cash_account_head;
        $payType = $request->payment_type;
        $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
        $receiptPaymentNoExplode = explode("-",$voucherNo);
        $receiptPaymentNoSl = $receiptPaymentNoExplode[1];

        $receiptPayment = new ReceiptPayment();
        $receiptPayment->receipt_payment_no = $voucherNo;
        $receiptPayment->project_id = $request->project;
        $receiptPayment->financial_year = financialYear($request->financial_year);
        $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $receiptPayment->transaction_type = 1;
        $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

        $receiptPayment->account_head_id = $request->bank_cash_account_head;
        $receiptPayment->cheque_no = $request->cheque_no;
        if ($request->payment_type == 1){
            $receiptPayment->cheque_date = Carbon::parse($request->cheque_date)->format('Y-m-d');
            $receiptPayment->issuing_bank_name = $request->issuing_bank_name;
            $receiptPayment->issuing_branch_name = $request->issuing_branch_name;
        }


        if($request->client_type == 1){
            $clientId = $request->payee;
            $clientCheck = Client::find($clientId);

            $receiptPayment->customer_id = $clientCheck->id_no;

            if ($request->address == null)
                $receiptPayment->address = $clientCheck->address;
            else
                $receiptPayment->address = $request->address;

            if ($request->mobile_no == null)
                $receiptPayment->mobile_no = $clientCheck->mobile_no;
            else
                $receiptPayment->mobile_no = $request->mobile_no;

            if ($request->email == null)
                $receiptPayment->email = $clientCheck->email;
            else
                $receiptPayment->email = $request->email;
        }else{

            $client = Client::where('name',$request->payee_name)->first();
            if (!$client){
                $maxClientId = Client::where('type',1)->max('id');
                $client = new Client();
                $client->type = 2;
                $client->id_no = 'C00'.($maxClientId + 1);
                $client->name = $request->payee_name;
                $client->designation = $request->designation;
                $client->address = $request->address;
                $client->email = $request->email;
                $client->mobile_no = $request->mobile_no;
                $client->status = 1;
                $client->save();
            }

            $clientId = $client->id;
            // dd($clientId);
            $receiptPayment->customer_id = $client->id_no;
            $receiptPayment->designation = $request->designation;
            $receiptPayment->address = $request->address;
            $receiptPayment->mobile_no = $request->mobile_no;
            $receiptPayment->email = $request->email;
        }

        $receiptPayment->client_id = $clientId;

        $receiptPayment->e_tin = $request->e_tin;
        $receiptPayment->nature_of_organization = $request->nature_of_organization;

        $receiptPayment->notes = $request->notes;
        $receiptPayment->sub_total = $totalAmount;
        $receiptPayment->net_amount = $grandTotal;
        $receiptPayment->save();

        //Bank/Cash Debit
        // $log = new TransactionLog();
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
        // $log->transaction_type = 1;//Bank debit,Cash debit

        // $log->project_id = $request->project;
        // $log->payment_type = $request->payment_type;
        // $log->account_head_id = $request->bank_cash_account_head;
        // $log->amount = $receiptPayment->net_amount;
        // $log->notes = $receiptPayment->notes;
        // $log->save();


        $counter = 0;
        foreach ($request->account_head_code as $reqAccountHeadCode){

            $receiptPaymentDetail = new ReceiptPaymentDetail();
            $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
            $receiptPaymentDetail->account_head_id = $request->account_head_code[$counter];
            $receiptPaymentDetail->narration = $request->narration[$counter] ?? null;
            $receiptPaymentDetail->amount = $request->amount[$counter];
            $receiptPaymentDetail->net_amount = $request->amount[$counter];
            $receiptPaymentDetail->save();

            //Credit Head Amount
            $log = new TransactionLog();
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $receiptPaymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->client_id = $clientId;
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_id = $receiptPayment->id;
            $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

            $log->payment_type = $request->payment_type;

            if($request->payment_type == 1){
                $log->cheque_no = $request->cheque_no;
                $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
            }
            $log->transaction_type = 2;//Account Head Credit
            $log->project_id = $request->project;
            $log->account_head_id = $request->account_head_code[$counter];
            $log->amount = $request->amount[$counter];
            $log->notes = $request->notes;
            $log->save();

            $counter++;
        }


        if ($request->supporting_document) {

            foreach ($request->file('supporting_document') as $key => $file) {

                // Upload Image
                $filename = Uuid::uuid1()->toString() . '.' . $file->extension();
                $destinationPath = 'public/uploads/supporting_document';
                $file->move($destinationPath, $filename);
                $path = 'uploads/supporting_document/' . $filename;

                $receiptPaymentFile = new ReceiptPaymentFile();
                $receiptPaymentFile->receipt_payment_id = $receiptPayment->id;
                $receiptPaymentFile->file = $path;
                $receiptPaymentFile->save();

            }
        }
        if ($request->payment_type == 1)
            $message = 'Cheque Receipt(CR) created';
        else
            $message = 'Cash Receipt(CR) created';


        return redirect()->route('receipt_details',['receiptPayment'=>$receiptPayment->id])
            ->with('message',$message);

    }
    public function edit(ReceiptPayment $receiptPayment)
    {

        $projects = Project::where('status',1)->get();
        $yearGet = financialYearToYear($receiptPayment->financial_year);
        $voucherExplode = voucherExplode($receiptPayment->receipt_payment_no);

        return view('accounts.receipt.edit',compact(
            'receiptPayment','yearGet','voucherExplode','projects'));
    }
    public function editPost(ReceiptPayment $receiptPayment,Request $request)
    {
        $rules = [
            'client_type'=>'required',
            'financial_year'=>'required',
            'payment_type'=>'required',
            'date'=>'required',
            'bank_cash_account_head'=>'required',
            'e_tin'=>'nullable|max:255',
            'designation'=>'nullable|max:255',
            'address'=>'nullable|max:255',
            'mobile_no'=>'nullable|digits:11',
            'email'=>'nullable|email',
            'nature_of_organization'=>'nullable|max:255',
            'account_head_code.*'=>'required',
            'amount.*'=>'required|numeric',
            'notes'=>'nullable|max:255',
            'narration.*'=>'nullable|max:255',
            'project'=>'nullable'
        ];

        if ($request->client_type == 1){
            $rules['payee'] = 'required';

        }else{
            $rules['payee_name']= 'required|unique:clients,name|max:255';
            $rules['customer_id']= 'nullable|max:255';
        }
        if ($request->payment_type == 1)
            $rules['cheque_no'] = 'required|max:255';

        $request->validate($rules);

        $counter = 0;
        $totalAmount = 0;


        if ($request->account_head_code){
            foreach ($request->account_head_code as $reqAccountHeadCode){
                $totalAmount += $request->amount[$counter];
                $counter++;
            }
            $grandTotal = $totalAmount;

        }


        $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $receiptPayment->project_id = $request->project;
        $receiptPayment->transaction_type = 1;
        $receiptPayment->payment_type = $request->payment_type;

        if ($request->payment_type == 1){
            $receiptPayment->cheque_no = $request->cheque_no;
            $receiptPayment->issuing_bank_name = $request->issuing_bank_name;
            $receiptPayment->issuing_branch_name = $request->issuing_branch_name;
            $receiptPayment->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
        }


        if($request->client_type == 1){
            $clientId = $request->payee;

            $clientCheck = Client::find($clientId);

            if ($clientCheck->id_no != null)
                $receiptPayment->customer_id = $clientCheck->id_no;
            else
                $receiptPayment->customer_id = $request->customer_id;

            if ($clientCheck->designation != null)
                $receiptPayment->designation = $clientCheck->designation;
            else
                $receiptPayment->designation = $request->designation;

            if ($request->address == null)
                $receiptPayment->address = $clientCheck->address;
            else
                $receiptPayment->address = $request->address;

            if ($request->mobile_no == null)
                $receiptPayment->mobile_no = $clientCheck->mobile_no;
            else
                $receiptPayment->mobile_no = $request->mobile_no;

            if ($request->email == null)
                $receiptPayment->email = $clientCheck->email;
            else
                $receiptPayment->email = $request->email;
        }else{
            $client = Client::where('name',$request->payee_name)->first();
            if (!$client){
                $maxClientId = Client::where('type',1)->max('id');
                $client = new Client();
                $client->type = 2;
                $client->id_no = 'C00'.($maxClientId + 1);
                $client->name = $request->payee_name;
                $client->designation = $request->designation;
                $client->address = $request->address;
                $client->email = $request->email;
                $client->mobile_no = $request->mobile_no;
                $client->status = 1;
                $client->save();
            }

            $clientId = $client->id;
            $receiptPayment->customer_id = $client->id_no;
            $receiptPayment->designation = $request->designation;

            $receiptPayment->address = $request->address;
            $receiptPayment->mobile_no = $request->mobile_no;
            $receiptPayment->email = $request->email;
        }

        $receiptPayment->client_id = $clientId;
        $receiptPayment->e_tin = $request->e_tin;
        $receiptPayment->nature_of_organization = $request->nature_of_organization;

        $receiptPayment->notes = $request->notes;
        $receiptPayment->sub_total = $totalAmount;
        $receiptPayment->net_amount = $grandTotal;
        $receiptPayment->save();

        //

        $voucherNo = $receiptPayment->receipt_payment_no;
        $receiptPaymentNoExplode = explode("-",$voucherNo);

        $receiptPaymentNoSl = $receiptPaymentNoExplode[1];

        $request->financial_year = financialYearToYear($request->financial_year);

        ReceiptPaymentDetail::where('receipt_payment_id',$receiptPayment->id)->delete();
        TransactionLog::where('receipt_payment_id',$receiptPayment->id)->delete();

        //Bank/Cash Credit
        // $log = new TransactionLog();
        // $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
        // $log->project_id = $request->project;
        // $log->receipt_payment_sl = $receiptPaymentNoSl;
        // $log->financial_year = $receiptPayment->financial_year;
        // $log->client_id = $receiptPayment->client_id;
        // $log->date = $receiptPayment->date;
        // $log->receipt_payment_id = $receiptPayment->id;
        // if($request->payment_type == 1){
        //     $log->cheque_no = $request->cheque_no;
        //     $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');

        // }
        // $log->transaction_type = 1;//Bank Debit,Cash Debit

        // $log->payment_type = $request->payment_type;
        // $log->account_head_id = $request->bank_cash_account_head;
        // $log->amount = $receiptPayment->net_amount;
        // $log->notes = $receiptPayment->notes;
        // $log->save();


        $counter = 0;
        foreach ($request->account_head_code as $reqAccountHeadCode){

            $receiptPaymentDetail = new ReceiptPaymentDetail();
            $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
            $receiptPaymentDetail->account_head_id = $request->account_head_code[$counter];
            $receiptPaymentDetail->narration = $request->narration[$counter] ?? null;
            $receiptPaymentDetail->amount = $request->amount[$counter];

            $subTotalDetail = $request->amount[$counter];
            $receiptPaymentDetail->net_amount = $subTotalDetail;
            $receiptPaymentDetail->save();

            //Credit Head Amount
            $log = new TransactionLog();
            $log->receipt_payment_no = $voucherNo;
            $log->project_id = $request->project;
            $log->receipt_payment_sl = $receiptPaymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->client_id = $clientId;
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_id = $receiptPayment->id;
            $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

            $log->payment_type = $request->payment_type;

            if($request->payment_type == 1){
                $log->cheque_no = $request->cheque_no;
                $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
            }
            $log->transaction_type = 2;//Account Head credit
            $log->account_head_id = $request->account_head_code[$counter];
            $log->amount = $request->amount[$counter];
            $log->notes = $request->notes;
            $log->save();

            $counter++;
        }


        if ($request->supporting_document) {

            foreach ($request->file('supporting_document') as $key => $file) {

                // Upload Image

                $filename = Uuid::uuid1()->toString() . '.' . $file->extension();
                $destinationPath = 'uploads/supporting_document';
                $file->move($destinationPath, $filename);
                $path = 'uploads/supporting_document/' . $filename;

                $receiptPaymentFile = new ReceiptPaymentFile();
                $receiptPaymentFile->receipt_payment_id = $receiptPayment->id;
                $receiptPaymentFile->file = $path;
                $receiptPaymentFile->save();

            }
        }

        if ($request->payment_type == 1)
            $message = 'Cheque Receipt(CR) created';
        else
            $message = 'Cash Receipt(CR) created';


        return redirect()->route('receipt_details',['receiptPayment'=>$receiptPayment->id])
            ->with('message',$message);

    }
    public function details(ReceiptPayment $receiptPayment)
    {
        $receiptPayment->amount_in_word = DecimalToWords::convert($receiptPayment->net_amount,'Taka',
            'Poisa');

        return view('accounts.receipt.details',compact('receiptPayment'));
    }
    public function receiptDetailsEdit(ReceiptPayment $receiptPayment){
        $orders = SalesOrder::where('client_id', $receiptPayment->client_id)
            ->where('due', '>', 0)
            ->orderBy('order_no')
            ->get();
        $accountHeads = AccountHead::orderBy('account_code')
            ->limit(50)
            ->get();
        return view('accounts.receipt.details_edit',compact('receiptPayment','orders','accountHeads'));
    }

    public function receiptDetailsEditPost(ReceiptPayment $receiptPayment,Request $request){

        //dd($request->all());
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

        $receiptPayment = ReceiptPayment::where('id',$receiptPayment->id)->first();

        if ($receiptPayment){

            ReceiptPaymentDetail::where('receipt_payment_id',$receiptPayment->id)
                ->delete();

           TransactionLog::where('receipt_payment_id',$receiptPayment->id)
                ->delete();

            $order = SalesOrder::find($request->order_no);
            $payAmount = $receiptPayment->net_amount ?? 0;

            $order->decrement('paid', $payAmount);
            $order->increment('due', $payAmount);
            $client->decrement('paid', $payAmount);
            $client->increment('due',$payAmount);

            $receiptPayment->delete();

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
        $log->transaction_type = 1;//Bank debit,Cash debit

        $log->payment_type = $request->payment_type;
        $log->account_head_id = $request->account;
        $log->amount = $receiptPayment->net_amount;
        $log->notes = $receiptPayment->notes;
        $log->sales_order_id = $order->id;
        $log->save();

        $receiptPaymentDetail = new ReceiptPaymentDetail();
        $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
        $receiptPaymentDetail->account_head_id = 9;
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
        $log->account_head_id = $request->account; // change karim
        $log->sales_order_id = $order->id;
        $log->amount = $payAmount;
        $log->notes = $request->note;
        $log->save();

        //return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('receipt_details', ['receiptPayment' => $receiptPayment->id])]);
        return redirect()->route('receipt_details', ['receiptPayment' => $receiptPayment->id]);
    }

    public function receiptDetailsDelete(Request $request){

        $receiptPayment = ReceiptPayment::where('id',$request->receiptPaymentId)->first();

        if ($receiptPayment){

            ReceiptPaymentDetail::where('receipt_payment_id',$receiptPayment->id)
                ->delete();

            TransactionLog::where('receipt_payment_id',$receiptPayment->id)
                ->delete();

            $order = SalesOrder::find($receiptPayment->sales_order_id);
            $client = Client::find($receiptPayment->client_id);
            $payAmount = $receiptPayment->net_amount ?? 0;

            $order->decrement('paid', $payAmount);
            $order->increment('due', $payAmount);
            $client->decrement('paid', $payAmount);
            $client->increment('due',$payAmount);

            $receiptPayment->delete();

        }

        return response()->json(['success' => true, 'message' => 'Delete Successfully.']);

    }
    public function print(ReceiptPayment $receiptPayment)
    {
        $receiptPayment->amount_in_word = DecimalToWords::convert($receiptPayment->net_amount,'Taka',
            'Poisa');

        return view('accounts.receipt.print',compact('receiptPayment'));
    }



}
