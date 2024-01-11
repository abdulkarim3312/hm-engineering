<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Models\AccountHead;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use App\Models\ReceiptPaymentFile;
use App\Model\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class JournalVoucherController extends Controller
{
    public function datatable(Request $request) {

        $query = JournalVoucher::orderBy('date')->with('client');
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function(JournalVoucher $journalVoucher) {
                $btn = '';
                $btn .= '<a target="_blank" href="'.route('journal_voucher_print',['journalVoucher'=>$journalVoucher->id]).'" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a> <a href="'.route('journal_voucher_details',['journalVoucher'=>$journalVoucher->id]).'" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i></a>';
                $btn .= ' <a href="'.route('journal_voucher.edit',['journalVoucher'=>$journalVoucher->id]).'" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i></a>';

                return $btn;
            })
            ->addColumn('debit_codes', function(JournalVoucher $journalVoucher) {

                $codes = '<ul style="text-align: left;">';
                foreach ($journalVoucher->journalVoucherDebitDetails as $journalVoucherDebitDetail){
                    $codes .= '<li>'.($journalVoucherDebitDetail->accountHead->account_code ?? '').'</li>';
                }
                $codes .= '</ul>';

                return $codes;
            })
            ->addColumn('credit_codes', function(JournalVoucher $journalVoucher) {

                $codes = '<ul style="text-align: left;">';
                foreach ($journalVoucher->journalVoucherCreditDetails as $journalVoucherCreditDetail){
                    $codes .= '<li>'.($journalVoucherCreditDetail->accountHead->account_code ?? '').'</li>';
                }
                $codes .= '</ul>';

                return $codes;
            })
            ->addColumn('client_name', function(JournalVoucher $journalVoucher) {
                return $journalVoucher->client->name ?? '';
            })
            ->editColumn('debit_total', function(JournalVoucher $journalVoucher) {
                return number_format($journalVoucher->debit_total,2);
            })
            ->editColumn('credit_total', function(JournalVoucher $journalVoucher) {
                return number_format($journalVoucher->credit_total,2);
            })
            ->editColumn('date', function(JournalVoucher $journalVoucher) {
                return $journalVoucher->date->format('d-m-Y');
            })
            ->filter(function ($query) {
                if (request()->has('start_date') && request('start_date') != '' && request()->has('end_date') && request('end_date') != '') {
                        $query->where('date', '>=', Carbon::parse(request('start_date'))->format('Y-m-d'));
                        $query->where('date', '<=', Carbon::parse(request('end_date'))->format('Y-m-d'));
                }

            })
            ->rawColumns(['action','reconciliation','debit_codes','credit_codes'])
            ->toJson();
    }

    public function index()
    {
        return view('accounts.journal_voucher.all');
    }
    public function details(JournalVoucher $journalVoucher)
    {

        $journalVoucher->amount_in_word = DecimalToWords::convert($journalVoucher->debit_total,'Taka',
            'Poisa');

        return view('accounts.journal_voucher.details',compact('journalVoucher'));
    }
    public function print(JournalVoucher $journalVoucher)
    {

        $journalVoucher->amount_in_word = DecimalToWords::convert($journalVoucher->debit_total,'Taka',
            'Poisa');

        return view('accounts.journal_voucher.print',compact('journalVoucher'));
    }


    public function create()
    {
        return view('accounts.journal_voucher.add');
    }


    public function createPost(Request $request)
    {
        $rules = [
            'client_type'=>'required',
            'financial_year'=>'required',
            'date'=>'required|date',
            'e_tin'=>'nullable|max:255',
            'address'=>'nullable|max:255',
            'nature_of_organization'=>'nullable|max:255',
            'designation'=>'nullable|max:255',
            'customer_id'=>'nullable|max:255',
            'account_head_code.*'=>'required',
            'debit_amount.*'=>'required|numeric',
            'debit_cost_center.*'=>'required',
            'credit_cost_center.*'=>'required',
            'notes'=>'nullable|max:255',
        ];


        if ($request->client_type == 1){
            $rules['employee_party'] = 'required';

        }else{
            $rules['name']= 'required|unique:clients|max:255';
            $rules['customer_id']= 'nullable|max:255';
        }

        if ($request->other_account_head_code){
            $rules['other_account_head_code.*']='required';
            $rules['credit_amount.*']='required|numeric';
        }
        $request->validate($rules);

        $totalCreditAmount = 0;
        $counter = 0;
        if ($request->other_account_head_code) {
            foreach ($request->other_account_head_code as $reqOtherAccountHeadCode) {
                $totalCreditAmount += $request->credit_amount[$counter];
                $counter++;
            }
        }

        $totalDebitAmount = 0;
        $counter = 0;
        if ($request->account_head_code){
            foreach ($request->account_head_code as $reqAccountHeadCode){
                $totalDebitAmount += $request->debit_amount[$counter];
                $counter++;
            }

        }
        if ($totalCreditAmount != $totalDebitAmount){
            return redirect()->back()
                ->with('error','Debit and Credit amount is not equal !')
                ->withInput();
        }

        $financialYear = financialYear($request->financial_year);
        $journalVoucherCheck = JournalVoucher::where('financial_year',$financialYear)
            ->orderBy('id','desc')->first();

        if ($journalVoucherCheck){

            $getJVLastNo = explode("-",$journalVoucherCheck->jv_no);
            $jvNo = 'JV-'.($getJVLastNo[1]+1);
        }else{
            $jvNo = 'JV-1';
        }


        $journalVoucher = new JournalVoucher();
        $journalVoucher->jv_no = $jvNo;
        $journalVoucher->financial_year = financialYear($request->financial_year);
        $journalVoucher->date = Carbon::parse($request->date)->format('Y-m-d');

        if($request->client_type == 1){
            $clientId = $request->employee_party;
            $clientCheck = Client::find($clientId);
            $journalVoucher->customer_id = $clientCheck->id_no;
            if ($request->address == null)
                $journalVoucher->address = $clientCheck->address;
            else
                $journalVoucher->address = $request->address;

            if ($request->mobile_no == null)
                $journalVoucher->mobile_no = $clientCheck->mobile_no;
            else
                $journalVoucher->mobile_no = $request->mobile_no;

            if ($request->email == null)
                $journalVoucher->email = $clientCheck->email;
            else
                $journalVoucher->email = $request->email;
        }else{
            $client = Client::where('name',$request->name)->first();
            if (!$client){
                $maxClientId = Client::max('id_no');
                $client = new Client();
                $client->id_no = $maxClientId + 1;
                $client->name = $request->name;
                $client->designation = $request->designation;
                $client->address = $request->address;
                $client->email = $request->email;
                $client->mobile_no = $request->mobile_no;
                $client->save();
            }

            $clientId = $client->id;

            $journalVoucher->customer_id = $client->id_no;
            $journalVoucher->address = $request->address;
            $journalVoucher->mobile_no = $request->mobile_no;
            $journalVoucher->email = $request->email;
        }

        $journalVoucher->client_id = $clientId;

        $journalVoucher->e_tin = $request->e_tin;
        $journalVoucher->nature_of_organization = $request->nature_of_organization;

        $journalVoucher->notes = $request->notes;
        $journalVoucher->debit_total= $totalDebitAmount;
        $journalVoucher->credit_total = $totalCreditAmount;
        $journalVoucher->save();

        $counter = 0;
        foreach ($request->account_head_code as $reqAccountHeadCode){


            $detail = new JournalVoucherDetail();
            $detail->type = 1;
            $detail->journal_voucher_id = $journalVoucher->id;
            $detail->account_head_id = $request->account_head_code[$counter];
            $detail->amount = $request->debit_amount[$counter];
            $detail->save();

            //Debit Head Amount
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->client_id = $clientId;
            $log->receipt_payment_no = $jvNo;
            $log->jv_no = $jvNo;
            $log->financial_year = financialYear($request->financial_year);
            $log->jv_type = 1;
            $log->journal_voucher_id = $journalVoucher->id;
            $log->journal_voucher_detail_id = $detail->id;
            $log->transaction_type = 1;//debit
            $log->account_head_id = $request->account_head_code[$counter];
            $log->amount = $request->debit_amount[$counter];
            $log->notes = $request->notes;
            $log->save();

            $counter++;
        }

        if ($request->other_account_head_code){
            $counter = 0;
            foreach ($request->other_account_head_code as $reqOtherAccountHeadCode){


                $detail = new JournalVoucherDetail();
                $detail->type = 2;
                $detail->journal_voucher_id = $journalVoucher->id;
                $detail->account_head_id = $request->other_account_head_code[$counter];
                $detail->amount = $request->credit_amount[$counter];
                $detail->save();

                //Credit Head Amount
                $log = new TransactionLog();
                $log->client_id = $clientId;
                $log->date = Carbon::parse($request->date)->format('Y-m-d');
                $log->receipt_payment_no = $jvNo;
                $log->jv_no = $jvNo;
                $log->financial_year = financialYear($request->financial_year);
                $log->jv_type = 2;
                $log->journal_voucher_id = $journalVoucher->id;
                $log->journal_voucher_detail_id = $detail->id;
                $log->transaction_type = 2;//credit
                $log->account_head_id = $request->other_account_head_code[$counter];
                $log->amount = $request->credit_amount[$counter];
                $log->notes = $request->notes;
                $log->save();

                $counter++;
            }
        }
        if ($request->supporting_document) {

            foreach ($request->file('supporting_document') as $key => $file) {

                // Upload Image

                $filename = Uuid::uuid1()->toString() . '.' . $file->extension();
                $destinationPath = 'uploads/supporting_document';
                $file->move($destinationPath, $filename);
                $path = 'uploads/supporting_document/' . $filename;

                $receiptPaymentFile = new ReceiptPaymentFile();
                $receiptPaymentFile->journal_voucher_id = $journalVoucher->id;
                $receiptPaymentFile->file = $path;
                $receiptPaymentFile->save();

            }
        }

        return redirect()->route('journal_voucher_details',['journalVoucher'=>$journalVoucher->id])
            ->with('message','Journal Voucher(JV) created');

    }
    public function edit(JournalVoucher $journalVoucher)
    {
        return view('accounts.journal_voucher.edit',compact('journalVoucher'));
    }
    public function editPost(JournalVoucher $journalVoucher,Request $request)
    {

        $rules = [
            'client_type'=>'required',
            'date'=>'required|date',
            'e_tin'=>'nullable|max:255',
            'address'=>'nullable|max:255',
            'nature_of_organization'=>'nullable|max:255',
            'designation'=>'nullable|max:255',
            'customer_id'=>'nullable|max:255',
            'account_head_code.*'=>'required',
            'debit_amount.*'=>'required|numeric',
            'debit_cost_center.*'=>'required',
            'notes'=>'nullable|max:255',
        ];
        if ($request->client_type == 1){
            $rules['employee_party'] = 'required';

        }else{
            $clientCheckValidate = Client::where('name',$request->name)->first();
            if ($clientCheckValidate)
                $rules['name']= 'required|unique:clients,name,'.$clientCheckValidate->id;
            else
                $rules['name']= 'required';

            $rules['customer_id']= 'nullable|max:255';
        }
        if ($request->other_account_head_code){
            $rules['other_account_head_code.*']='required';
            $rules['credit_amount.*']='required|numeric';
        }
        $request->validate($rules);

        $totalCreditAmount = 0;
        $counter = 0;
        if ($request->other_account_head_code) {
            foreach ($request->other_account_head_code as $reqOtherAccountHeadCode) {
                $totalCreditAmount += $request->credit_amount[$counter];
                $counter++;
            }
        }
        $totalDebitAmount = 0;
        $counter = 0;
        if ($request->account_head_code){
            foreach ($request->account_head_code as $reqAccountHeadCode){
                $totalDebitAmount += $request->debit_amount[$counter];
                $counter++;
            }

        }
        if ($totalCreditAmount != $totalDebitAmount){
            return redirect()->back()
                ->with('error','Debit and Credit amount is not equal !')
                ->withInput();
        }

        $journalVoucher->date = Carbon::parse($request->date)->format('Y-m-d');

        if($request->client_type == 1){
            $clientId = $request->employee_party;
            $clientCheck = Client::find($clientId);
            $journalVoucher->customer_id = $clientCheck->id_no;

            if ($request->mobile_no == null)
                $journalVoucher->mobile_no = $clientCheck->mobile_no;
            else
                $journalVoucher->mobile_no = $request->mobile_no;

            if ($request->email == null)
                $journalVoucher->email = $clientCheck->email;
            else
                $journalVoucher->email = $request->email;
        }else{
            $client = Client::where('name',$request->name)->first();
            if (!$client){
                $maxClientId = Client::max('id_no');
                $client = new Client();
                $client->id_no = $maxClientId  + 1;
                $client->name = $request->name;
                $client->designation = $request->designation;
                $client->address = $request->address;
                $client->email = $request->email;
                $client->mobile_no = $request->mobile_no;
                $client->save();
            }
            $clientId = $client->id;

            $journalVoucher->customer_id = $client->id_no;
            $journalVoucher->address = $request->address;
            $journalVoucher->mobile_no = $request->mobile_no;
            $journalVoucher->email = $request->email;
        }

        $journalVoucher->client_id = $clientId;

        $journalVoucher->e_tin = $request->e_tin;
        $journalVoucher->nature_of_organization = $request->nature_of_organization;

        $journalVoucher->notes = $request->notes;
        $journalVoucher->debit_total= $totalDebitAmount;
        $journalVoucher->credit_total = $totalCreditAmount;
        $journalVoucher->save();

        $jvNo = $journalVoucher->jv_no;
        $request->financial_year = financialYearToYear($request->financial_year);

        JournalVoucherDetail::where('journal_voucher_id',$journalVoucher->id)->delete();
        TransactionLog::where('journal_voucher_id',$journalVoucher->id)->delete();


        $counter = 0;
        foreach ($request->account_head_code as $reqAccountHeadCode){
            $detail = new JournalVoucherDetail();
            $detail->type = 1;
            $detail->journal_voucher_id = $journalVoucher->id;
            $detail->account_head_id = $request->account_head_code[$counter];
            $detail->amount = $request->debit_amount[$counter];
            $detail->save();

            //debit Head Amount
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->client_id = $clientId;
            $log->receipt_payment_no = $jvNo;
            $log->jv_no = $jvNo;
            $log->financial_year = financialYear($request->financial_year);
            $log->jv_type = 1;
            $log->journal_voucher_id = $journalVoucher->id;
            $log->journal_voucher_detail_id = $detail->id;
            $log->transaction_type = 1;//debit
            $log->account_head_id = $request->account_head_code[$counter];
            $log->amount = $request->debit_amount[$counter];
            $log->notes = $request->notes;
            $log->save();

            $counter++;
        }

        if ($request->other_account_head_code){
            $counter = 0;
            foreach ($request->other_account_head_code as $reqOtherAccountHeadCode){

                $detail = new JournalVoucherDetail();
                $detail->type = 2;
                $detail->journal_voucher_id = $journalVoucher->id;
                $detail->account_head_id = $request->other_account_head_code[$counter];
                $detail->amount = $request->credit_amount[$counter];
                $detail->save();

                //Credit Head Amount
                $log = new TransactionLog();
                $log->client_id = $clientId;
                $log->date = Carbon::parse($request->date)->format('Y-m-d');
                $log->receipt_payment_no = $jvNo;
                $log->jv_no = $jvNo;
                $log->financial_year = financialYear($request->financial_year);
                $log->jv_type = 2;
                $log->journal_voucher_id = $journalVoucher->id;
                $log->journal_voucher_detail_id = $detail->id;
                $log->transaction_type = 2;//credit
                $log->account_head_id = $request->other_account_head_code[$counter];
                $log->amount = $request->credit_amount[$counter];
                $log->notes = $request->notes;
                $log->save();

                $counter++;
            }
        }
        if ($request->supporting_document) {

            foreach ($request->file('supporting_document') as $key => $file) {

                // Upload Image

                $filename = Uuid::uuid1()->toString() . '.' . $file->extension();
                $destinationPath = 'uploads/supporting_document';
                $file->move($destinationPath, $filename);
                $path = 'uploads/supporting_document/' . $filename;

                $receiptPaymentFile = new ReceiptPaymentFile();
                $receiptPaymentFile->journal_voucher_id = $journalVoucher->id;
                $receiptPaymentFile->file = $path;
                $receiptPaymentFile->save();

            }
        }

        return redirect()->route('journal_voucher_details',['journalVoucher'=>$journalVoucher->id])
            ->with('message','Journal Voucher(JV) Updated');

    }
}
