<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\Project;
use App\Model\TransactionLog;
use App\Models\AccountHead;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use App\Models\ReceiptPaymentFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    public function datatable() {
        $query = ReceiptPayment::with('accountHead','receiptPaymentDetails','client')
                    ->where('transaction_type',2);
        return DataTables::eloquent($query)

            ->addColumn('action', function(ReceiptPayment $receiptPayment) {
                $btn = '';
                $btn .= ' <a target="_blank" href="'.route('voucher_print',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-print"></i></a> ';
                $btn .=' <a href="'.route('voucher_details',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-info-circle"></i></a> ';
                $btn .=' <a href="'.route('voucher.edit',['receiptPayment'=>$receiptPayment->id]).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a> ';

                return $btn;
            })

            ->addColumn('account_head', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->accountHead->name ?? '';
            })
            ->addColumn('client_name', function(ReceiptPayment $receiptPayment) {
                return $receiptPayment->client->name ?? '';
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
            ->rawColumns(['action','expenses_code','expenses_head','narrations'])
            ->toJson();
    }

    public function index()
    {
        return view('accounts.voucher.all');
    }
    public function create()
    {
        $projects = Project::where('status',1)->get();
        return view('accounts.voucher.create_voucher',compact('projects'));
    }



    public function createPost(Request $request)
    {
        // dd($request->all());
        $rules = [
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
            'vat_base_amount.*'=>'nullable|numeric',
            'vat_rate.*'=>'nullable|numeric',
            'vat_amount.*'=>'nullable|numeric',
            'narration.*'=>'nullable|max:255',
            'notes'=>'nullable|max:255',
            'project'=>'required',
        ];
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
        $transactionType = 2;
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
        $receiptPayment->transaction_type = 2;
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

            // $receiptPayment->customer_id = $clientCheck->id_no;

            // if ($request->address == null)
            //     $receiptPayment->address = $clientCheck->address;
            // else
            //     $receiptPayment->address = $request->address;

            // if ($request->mobile_no == null)
            //     $receiptPayment->mobile_no = $clientCheck->mobile_no;
            // else
            //     $receiptPayment->mobile_no = $request->mobile_no;

            // if ($request->email == null)
            //     $receiptPayment->email = $clientCheck->email;
            // else
            //     $receiptPayment->email = $request->email;
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

        $receipt_payment_detail_id = [];
        $counter = 0;

        foreach ($request->account_head_code as $reqAccountHeadCode){

            $receiptPaymentDetail = new ReceiptPaymentDetail();
            $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
            $receiptPaymentDetail->account_head_id = $request->account_head_code[$counter];
            $receiptPaymentDetail->narration = $request->narration[$counter] ?? null;
            $receiptPaymentDetail->amount = $request->amount[$counter];

            $vatAccountHead = AccountHead::where('account_head_type_id',4)->first();
            if ($request->vat_rate[$counter] != ''){

                $receiptPaymentDetail->vat_account_head_id = $vatAccountHead->id;
                $receiptPaymentDetail->vat_base_amount = $request->vat_base_amount[$counter] ?? 0;
                $receiptPaymentDetail->vat_rate = $request->vat_rate[$counter] ?? 0;
                $receiptPaymentDetail->vat_amount = $request->vat_amount[$counter] ?? 0;
            }

            $subTotalDetail = $request->amount[$counter] - (($request->vat_amount[$counter] ?? 0));
            $receiptPaymentDetail->net_amount = $subTotalDetail;
            $receiptPaymentDetail->save();

            $receipt_payment_detail_id[] = $receiptPaymentDetail->id;

            //Debit Head Amount
            $log = new TransactionLog();
            $log->financial_year = financialYear($request->financial_year);
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_id = $receiptPayment->id;
            // dd($log->receipt_payment_id);
            $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

            $log->payment_type = $request->payment_type;

            if($request->payment_type == 1){
                $log->cheque_no = $request->cheque_no;
                $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
            }
            $log->transaction_type = 1;//Account Head Debit
            $log->project_id = $request->project;
            $log->account_head_id = $request->account_head_code[$counter];
            $log->amount = $request->amount[$counter] - ($request->vat_amount[$counter]);
            $log->notes = $request->notes;
            $log->save();

            if ($request->vat_rate[$counter] > 0){
                //Debit VAT Amount
                $log = new TransactionLog();
                $log->financial_year = financialYear($request->financial_year);
                $log->vat_chaild_account_head_id = $vatAccountHead->id;
                $log->date = Carbon::parse($request->date)->format('Y-m-d');
                $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

                $log->transaction_type = 1;//Vat Account Head Debit
                $log->payment_type = $request->payment_type;

                if ($request->payment_type == 1){
                    $log->cheque_no = $request->cheque_no;
                    $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
                }

                $log->account_head_id = $request->account_head_code[$counter];
                $log->amount = $request->vat_amount[$counter];
                $log->notes = $request->notes;
                $log->save();
            }
            $counter++;
        }

        $account_head_codes = ReceiptPaymentDetail::whereIn('id',$receipt_payment_detail_id)
            ->select('account_head_id')
            ->groupBy('account_head_id')
            ->get();

        // dd($account_head_codes);

        foreach ($account_head_codes as $account_head_code){
            $counter = 0;
            $totalAmount = 0;
            $totalVatAmount = 0;
            $grandTotal = 0;

            foreach ($receipt_payment_detail_id as $item){
                $receiptPaymentDetails = ReceiptPaymentDetail::where('id',$item)
                    ->where('account_head_id',$account_head_code->account_head_id)
                    ->get();


                foreach ($receiptPaymentDetails as $receiptPaymentDetail){
                    $totalAmount += $receiptPaymentDetail->amount;
                    $totalVatAmount += $receiptPaymentDetail->vat_amount??0;

                    $grandTotal = $totalAmount - $totalVatAmount;

                    $counter++;
                }
            }

            //create dynamic voucher no process start
            // $transactionType = 2;
            // $financialYear = $request->financial_year;
            // $cashBankAccountHeadId = $request->bank_cash_account_head;
            // $payType = $request->payment_type;
            // $voucherNo = generateVoucherReceiptNo($financialYear,$cashBankAccountHeadId,$transactionType,$payType);
            // //create dynamic voucher no process end
            // $receiptPaymentNoExplode = explode("-",$voucherNo);

            // $receiptPaymentNoSl = $receiptPaymentNoExplode[1];
            // $receiptPayment = new ReceiptPayment();
            // $receiptPayment->receipt_payment_no = $voucherNo;
            // $receiptPayment->project_id = $request->project;
            // $receiptPayment->financial_year = financialYear($request->financial_year);
            // $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
            // $receiptPayment->transaction_type = 2;
            // $receiptPayment->payment_type = $request->payment_type;//cash == 2,bank =1

            // $receiptPayment->account_head_id = $request->bank_cash_account_head;
            // $receiptPayment->cheque_no = $request->cheque_no;
            // $receiptPayment->cheque_date = Carbon::parse($request->date)->format('Y-m-d');

            // $receiptPayment->e_tin = $request->e_tin;
            // $receiptPayment->nature_of_organization = $request->nature_of_organization;

            // $receiptPayment->notes = $request->notes;
            // $receiptPayment->vat_total = $totalVatAmount;
            // $receiptPayment->sub_total = $totalAmount;
            // $receiptPayment->net_amount = $grandTotal;
            // $receiptPayment->save();


            // if($request->client_type == 1){
            //     $clientId = $request->payee;
            //     $clientCheck = Client::find($clientId);

            //     $receiptPayment->customer_id = $clientCheck->id_no;

            //     if ($request->address == null)
            //         $receiptPayment->address = $clientCheck->address;
            //     else
            //         $receiptPayment->address = $request->address;

            //     if ($request->mobile_no == null)
            //         $receiptPayment->mobile_no = $clientCheck->mobile_no;
            //     else
            //         $receiptPayment->mobile_no = $request->mobile_no;

            //     if ($request->email == null)
            //         $receiptPayment->email = $clientCheck->email;
            //     else
            //         $receiptPayment->email = $request->email;
            // }else{

            //     $client = Client::where('name',$request->payee_name)->first();
            //     if (!$client){
            //         $maxClientId = Client::where('type',2)->max('id');
            //         $client = new Client();
            //         $client->type = 2;
            //         $client->id_no = 'S00'.($maxClientId + 1);
            //         $client->name = $request->payee_name;
            //         $client->designation = $request->designation;
            //         $client->address = $request->address;
            //         $client->email = $request->email;
            //         $client->mobile_no = $request->mobile_no;
            //         $client->status = 1;
            //         $client->save();
            //     }

            //     $clientId = $client->id;
            //     $receiptPayment->customer_id = $client->id_no;
            //     $receiptPayment->designation = $request->designation;
            //     $receiptPayment->address = $request->address;
            //     $receiptPayment->mobile_no = $request->mobile_no;
            //     $receiptPayment->email = $request->email;
            // }

            // $receiptPayment->client_id = $clientId;

            // $receiptPayment->e_tin = $request->e_tin;
            // $receiptPayment->nature_of_organization = $request->nature_of_organization;

            // $receiptPayment->notes = $request->notes;
            // $receiptPayment->vat_total = $totalVatAmount;
            // $receiptPayment->sub_total = $totalAmount;
            // $receiptPayment->net_amount = $grandTotal;
            // $receiptPayment->save();

            //Bank/Cash Credit change by karim
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
            // $log->transaction_type = 2;//Bank Credit,Cash Credit
            // $log->project_id = $request->project;
            // $log->payment_type = $request->payment_type;
            // $log->account_head_id = $request->bank_cash_account_head;
            // $log->amount = $receiptPayment->net_amount;
            // $log->notes = $receiptPayment->notes;
            // $log->save();

            foreach ($receipt_payment_detail_id as $item) {
                $receiptPaymentDetails = ReceiptPaymentDetail::where('id', $item)
                    ->where('account_head_id', $account_head_code->account_head_id)
                    ->get();
                foreach ($receiptPaymentDetails as $receiptPaymentDetail) {
                    $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
                    $receiptPaymentDetail->save();

                   $log = TransactionLog::where('receipt_payment_detail_id',$receiptPaymentDetail->id)->first();
                    if ($log){
                        $log->receipt_payment_sl = $receiptPaymentNoSl;
                        $log->receipt_payment_no = $receiptPayment->receipt_payment_no;
                        $log->save();
                    }
                }
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
        }

        if ($request->payment_type == 1)
            $message = 'Bank Voucher(BV) created';
        else
            $message = 'Cash Voucher(CV) created';


//        return redirect()->route('voucher_details',['receiptPayment'=>$receiptPayment->id])
//            ->with('message',$message);

        return redirect()->route('voucher')->with('message',$message);

    }

    public function edit(ReceiptPayment $receiptPayment)
    {

        $yearGet = financialYearToYear($receiptPayment->financial_year);
        $voucherExplode = voucherExplode($receiptPayment->receipt_payment_no);
        $projects = Project::where('status',1)->get();

        return view('accounts.voucher.edit',compact(
            'receiptPayment','yearGet','voucherExplode','projects'));
    }
    public function editPost(ReceiptPayment $receiptPayment, Request $request)
    {
        // dd($request->all());
        $rules = [
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
            'vat_base_amount.*'=>'nullable|numeric',
            'vat_rate.*'=>'nullable|numeric',
            'vat_amount.*'=>'nullable|numeric',
            'narration.*'=>'nullable|max:255',
            'notes'=>'nullable|max:255',
            'project'=>'nullable',

        ];

        if ($request->payment_type == 1)
            $rules['cheque_no'] = 'required|max:255';

        $request->validate($rules);

        $counter = 0;
        $totalAmount = 0;
        $totalVatAmount = 0;
        $totalAitAmount = 0;

        if ($request->account_head_code){
            foreach ($request->account_head_code as $reqAccountHeadCode){
                $totalAmount += $request->amount[$counter];
                $totalVatAmount += $request->vat_amount[$counter] ?? 0;
                $totalAitAmount += $request->ait_amount[$counter] ?? 0;

                $counter++;
            }
            $grandTotal = $totalAmount -( $totalVatAmount + $totalAitAmount);

        }


        $receiptPayment->date = Carbon::parse($request->date)->format('Y-m-d');
        $receiptPayment->project_id = $request->project;
        $receiptPayment->transaction_type = 2;
        $receiptPayment->payment_type = $request->payment_type;
        $receiptPayment->cheque_no = $request->cheque_no;
        if ($request->payment_type == 1)
            $receiptPayment->cheque_date = Carbon::parse($request->date)->format('Y-m-d');


        $receiptPayment->e_tin = $request->e_tin;
        $receiptPayment->nature_of_organization = $request->nature_of_organization;

        if($request->notes){
            $receiptPayment->notes = $request->notes;
        }
        $receiptPayment->sub_total = $totalAmount;
        $receiptPayment->vat_total = $totalVatAmount;
        //$receiptPayment->ait_total = $totalAitAmount;
        $receiptPayment->net_amount = $grandTotal;
        $receiptPayment->save();

        //


        $voucherNo = $receiptPayment->receipt_payment_no;
        $receiptPaymentNoExplode = explode("-",$voucherNo);

        $receiptPaymentNoSl = $receiptPaymentNoExplode[1];

        $request->financial_year = financialYearToYear($request->financial_year);

        ReceiptPaymentDetail::where('receipt_payment_id',$receiptPayment->id)->delete();
        TransactionLog::where('receipt_payment_id',$receiptPayment->id)->delete();

        $counter = 0;
        foreach ($request->account_head_code as $reqAccountHeadCode){

            $receiptPaymentDetail = new ReceiptPaymentDetail();
            $receiptPaymentDetail->receipt_payment_id = $receiptPayment->id;
            $receiptPaymentDetail->account_head_id = $request->account_head_code[$counter];
            $receiptPaymentDetail->narration = $request->narration[$counter] ?? null;
            $receiptPaymentDetail->amount = $request->amount[$counter];
            $subTotalDetail = $request->amount[$counter] - (($request->vat_amount[$counter] ?? 0));
            $receiptPaymentDetail->net_amount = $subTotalDetail;
            $receiptPaymentDetail->save();



            //Debit Head Amount
            $log = new TransactionLog();
            $log->receipt_payment_no = $voucherNo;
            $log->project_id = $request->project;
            $log->receipt_payment_sl = $receiptPaymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_id = $receiptPayment->id;
            $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

            $log->payment_type = $request->payment_type;

            if($request->payment_type == 1){
                $log->cheque_no = $request->cheque_no;
                $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
            }
            $log->transaction_type = 1;//Account Head Debit
            $log->account_head_id = $request->account_head_code[$counter];
            $log->amount = $request->amount[$counter] - ($request->vat_amount[$counter]);
            $log->notes = $request->notes;
            $log->save();

            // if ($request->vat_rate[$counter] > 0){
            //     //Credit VAT Amount
            //     $log = new TransactionLog();
            //     $log->receipt_payment_no = $voucherNo;
            //     $log->project_id = $request->project;
            //     $log->receipt_payment_sl = $receiptPaymentNoSl;
            //     $log->financial_year = financialYear($request->financial_year);
            //     $log->client_id = $clientId;
            //     $log->vat_parent_account_head_id = $request->account_head_code[$counter];
            //     $log->date = Carbon::parse($request->date)->format('Y-m-d');
            //     $log->receipt_payment_id = $receiptPayment->id;
            //     $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

            //     $log->transaction_type = 2;//Vat Account Head Credit
            //     $log->payment_type = $request->payment_type;

            //     if ($request->payment_type == 1){
            //         $log->cheque_no = $request->cheque_no;
            //         $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
            //     }

            //     $log->account_head_id = $vatAccountHead->id;
            //     $log->amount = $request->vat_amount[$counter];
            //     $log->notes = $request->notes;
            //     $log->save();


            //     //Debit VAT Amount
            //     $log->receipt_payment_no = $voucherNo;
            //     $log->project_id = $request->project;
            //     $log->receipt_payment_sl = $receiptPaymentNoSl;
            //     $log->financial_year = financialYear($request->financial_year);
            //     $log->vat_chaild_account_head_id = $vatAccountHead->id;
            //     $log->date = Carbon::parse($request->date)->format('Y-m-d');
            //     $log->receipt_payment_id = $receiptPayment->id;
            //     $log->receipt_payment_detail_id = $receiptPaymentDetail->id;

            //     $log->transaction_type = 1;//Vat Account Head Debit
            //     $log->payment_type = $request->payment_type;

            //     if ($request->payment_type == 1){
            //         $log->cheque_no = $request->cheque_no;
            //         $log->cheque_date = Carbon::parse($request->date)->format('Y-m-d');
            //     }

            //     $log->account_head_id = $request->account_head_code[$counter];
            //     $log->amount = $request->vat_amount[$counter];
            //     $log->notes = $request->notes;
            //     $log->save();
            // }

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
            $message = 'Bank Voucher(BV) created';
        else
            $message = 'Cash Voucher(CV) created';


        return redirect()->route('voucher_details',['receiptPayment'=>$receiptPayment->id])
            ->with('message',$message);

    }
    public function details(ReceiptPayment $receiptPayment)
    {
        $receiptPayment->amount_in_word = DecimalToWords::convert($receiptPayment->net_amount,'Taka',
            'Poisa');

        return view('accounts.voucher.details',compact('receiptPayment'));
    }
    public function print(ReceiptPayment $receiptPayment)
    {
        $receiptPayment->amount_in_word = DecimalToWords::convert($receiptPayment->net_amount,'Taka',
            'Poisa');

        return view('accounts.voucher.print',compact('receiptPayment'));
    }
    public function rangePrint(Request $request)
    {
        $selectBank = BankAccount::find($request->bank_account_code);
        if (!$selectBank){
            abort('404','Bank Account not found!');
        }

        $from = 'BV-'.$request->from.'-'.$selectBank->existing_account_code;
        $to = 'BV-'.$request->to.'-'.$selectBank->existing_account_code;

        $voucherLists = [];
        for($i = $request->from;$i <= $request->to;$i++){
            array_push($voucherLists,'BV-'.$i.'-'.$selectBank->existing_account_code);
        }

        $receiptPayments = ReceiptPayment::where('bank_account_id',$request->bank_account_code)
            ->whereIn('receipt_payment_no',$voucherLists)
            ->orderBy('id')
            ->get();

        if (count($receiptPayments) <= 0)
            return redirect()->back()->with('error','Opps... Somethings wrong!');



        return view('bank_voucher.range_print',compact('receiptPayments',
            'from','to'));
    }
}
