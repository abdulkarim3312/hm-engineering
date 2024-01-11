<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use App\Models\BalanceTransfer;
use App\Model\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class BalanceTransferController extends Controller
{
    public function balanceTransferIndex()
    {
        return view('accounts.balance_transfer.all');
    }
    public function voucherDetails(BalanceTransfer $balanceTransfer)
    {

        $balanceTransfer->amount_in_word = DecimalToWords::convert($balanceTransfer->amount,'Taka',
            'Poisa');

        return view('accounts.balance_transfer.voucher_details',compact('balanceTransfer'));
    }
    public function voucherPrint(BalanceTransfer $balanceTransfer)
    {

        $balanceTransfer->amount_in_word = DecimalToWords::convert($balanceTransfer->amount,'Taka',
            'Poisa');

        return view('accounts.balance_transfer.voucher_print',compact('balanceTransfer'));
    }

    public function receiptDetails(BalanceTransfer $balanceTransfer)
    {

        $balanceTransfer->amount_in_word = DecimalToWords::convert($balanceTransfer->amount,'Taka',
            'Poisa');

        return view('accounts.balance_transfer.receipt_details',compact('balanceTransfer'));
    }
    public function receiptPrint(BalanceTransfer $balanceTransfer)
    {

        $balanceTransfer->amount_in_word = DecimalToWords::convert($balanceTransfer->amount,'Taka',
            'Poisa');

        return view('accounts.balance_transfer.receipt_print',compact('balanceTransfer'));
    }

    public function balanceTransferDatatable()
    {
        $query = BalanceTransfer::with('targetAccountHead','sourceAccountHead');

        return DataTables::eloquent($query)

            ->editColumn('date', function(BalanceTransfer $balanceTransfer) {
                return $balanceTransfer->date->format('Y-m-d');
            })
            ->editColumn('amount', function(BalanceTransfer $balanceTransfer) {
                return number_format($balanceTransfer->amount,2);
            })
            ->addColumn('source_account_head_name', function(BalanceTransfer $balanceTransfer) {

                return $balanceTransfer->sourceAccountHead->name ?? '';

            })
            ->addColumn('target_account_head_name', function(BalanceTransfer $balanceTransfer) {
                return $balanceTransfer->targetAccountHead->name ?? '';
            })
            ->editColumn('type', function(BalanceTransfer $balanceTransfer) {
                if ($balanceTransfer->type == 1)
                    return '<span class="label label-warning">Bank To Cash</span>';
                elseif ($balanceTransfer->type == 2)
                    return '<span class="label label-success">Cash To Bank</span>';
                elseif ($balanceTransfer->type == 3)
                    return '<span class="label label-info">Bank To Bank</span>';
                else
                    return '<span class="label label-danger">Cash To Cash</span>';
            })
            ->addColumn('action', function(BalanceTransfer $balanceTransfer) {
                $btn = '';
                $btn .= ' <a href="'.route('balance_transfer_voucher_details',['balanceTransfer'=>$balanceTransfer->id]).'" class="btn btn-success btn-sm">Voucher </a> <a href="'.route('balance_transfer_receipt_details',['balanceTransfer'=>$balanceTransfer->id]).'" class="btn btn-success btn-sm"> Receipt</a>';
               $btn .= ' <a href="'.route('balance_transfer.edit',['balanceTransfer'=>$balanceTransfer->id]).'" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>';

                return $btn;
            })
            ->rawColumns(['type','action'])
            ->toJson();
    }
    public function balanceTransferAdd() {

        return view('accounts.balance_transfer.add');
    }

    public function balanceTransferAddPost(Request $request) {

        $rules = [
            'financial_year' => 'required',
            'source_account_head_code' => 'required',
            'target_account_head_code' => 'required',
            'type' => 'required|integer|min:1|max:4',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ];

        if ($request->type == 1 || $request->type == 3){
            $rules ['source_bank_cheque_date']= 'required|date';
            $rules ['source_bank_cheque_no']= 'required';
        }

        $request->validate($rules);

        $sourceImage = null;
        if ($request->type == 1 || $request->type == 3) {
            $sourceImage = 'img/no_image.png';

            if ($request->source_bank_cheque_image) {
                // Upload Image
                $file = $request->file('source_bank_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $sourceImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        $voucherNo = '';
        $receiptNo = '';
        if ($request->type == 1) {
            //create dynamic voucher no process start
            $transactionType = 2;
            $financialYear = $request->financial_year;
            $accountHeadId = $request->source_account_head_code;
            $payType = 1;
            $voucherNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);
            $transactionType = 1;
            $accountHeadId = $request->target_account_head_code;
            $payType = 2;
            $receiptNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);

            //create dynamic voucher no process end
        }elseif($request->type == 2){
            //create dynamic voucher no process start
            $transactionType = 2;
            $financialYear = $request->financial_year;
            $accountHeadId = $request->source_account_head_code;
            $payType = 1;
            $voucherNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);
            $transactionType = 1;
            $accountHeadId = $request->target_account_head_code;
            $payType = 2;
            $receiptNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);

            //create dynamic voucher no process end
        }elseif($request->type == 3){
            //create dynamic voucher no process start
            $transactionType = 2;
            $financialYear = $request->financial_year;
            $accountHeadId = $request->source_account_head_code;
            $payType = 1;
            $voucherNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);
            $transactionType = 1;
            $accountHeadId = $request->target_account_head_code;
            $payType = 1;
            $receiptNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);

            //create dynamic voucher no process end
        }elseif($request->type == 4){
            //create dynamic voucher no process start
            $transactionType = 2;
            $financialYear = $request->financial_year;
            $accountHeadId = $request->source_account_head_code;
            $payType = 2;
            $voucherNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);
            $transactionType = 1;
            $accountHeadId = $request->target_account_head_code;
            $payType = 2;
            $receiptNo = generateVoucherReceiptNo($financialYear,$accountHeadId,$transactionType,$payType);
            //create dynamic voucher no process end
        }

        $paymentNoExplode = explode("-",$voucherNo);
        $receiptExplode = explode("-",$receiptNo);

        $paymentNoSl = $paymentNoExplode[1];
        $receiptNoSl = $receiptExplode[1];

        $transfer = new BalanceTransfer();
        $transfer->voucher_no = $voucherNo;
        $transfer->receipt_no = $receiptNo;
        $transfer->financial_year = financialYear($request->financial_year);
        $transfer->type = $request->type;
        $transfer->source_account_head_id = $request->source_account_head_code;
        $transfer->target_account_head_id = $request->target_account_head_code;
        $transfer->source_cheque_no = $request->source_bank_cheque_no ?? null;
        $transfer->source_cheque_image = $sourceImage;
        $transfer->source_cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
        $transfer->amount = $request->amount;
        $transfer->date = Carbon::parse($request->date)->format('Y-m-d');
        $transfer->note = $request->note;
        $transfer->save();

        if ($request->type == 1) {
            // Bank To Cash

            //Bank Credit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $paymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Bank debit
            $log->payment_type = 1;
            $log->cheque_no = $request->source_bank_cheque_no;
            $log->cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            //Cash Debit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Cash Debit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();
        }elseif ($request->type == 2) {
            // Cash To Bank

            //Cash Credit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Cash Credit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            //Bank Debit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $paymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Bank debit
            $log->payment_type = 1;
            $log->cheque_no = $request->source_bank_cheque_no;
            $log->cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();

        }elseif ($request->type == 3) {
            // Bank To Bank
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $paymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Bank Credit
            $log->payment_type = 1;
            $log->cheque_no = $request->source_bank_cheque_no;
            $log->cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Bank Debit
            $log->payment_type = 1;
            $log->cheque_no = $request->target_bank_cheque_no;
            $log->cheque_date = $request->target_bank_cheque_date ? Carbon::parse($request->target_bank_cheque_date)->format('Y-m-d') : null;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();
        }elseif ($request->type == 4) {
            // Cash To Cash

            //Cash Credit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Cash Credit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            //Cash Debit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Cash Debit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();

        }else{
            return redirect()->route('balance_transfer.add')
                ->withInput()
                ->with('error', 'Balance transfer error.');
        }

        return redirect()->route('balance_transfer')
            ->with('message', 'Balance transfer successful.');
    }

    public function balanceTransferEdit(BalanceTransfer $balanceTransfer) {

        return view('accounts.balance_transfer.edit', compact('balanceTransfer'));
    }
    public function balanceTransferEditPost(BalanceTransfer $balanceTransfer,Request $request) {



        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'source_bank_account_cheque_no' => 'nullable|string|max:255',
            'source_bank_account_cheque_image' => 'nullable|image',
            'target_bank_account_cheque_no' => 'nullable|string|max:255',
            'target_bank_account_cheque_image' => 'nullable|image',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $sourceImage = null;
        $targetImage = null;
        if ($request->type == 1 || $request->type == 3) {
            $sourceImage = 'img/no_image.png';

            if ($request->source_bank_cheque_image) {
                // Upload Image
                $file = $request->file('source_bank_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $sourceImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        $oldAmount = $balanceTransfer->amount;

        $transfer = $balanceTransfer;
        $transfer->source_cheque_no =  $request->source_bank_cheque_no;
        $transfer->source_cheque_image = $sourceImage;
        $transfer->source_cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
        $transfer->amount = $request->amount;
        $transfer->date = Carbon::parse($request->date)->format('Y-m-d');
        $transfer->note = $request->note;
        $transfer->save();
        $voucherNo = $transfer->voucher_no;
        $receiptNo = $transfer->receipt_no;

        $paymentNoExplode = explode("-",$voucherNo);
        $receiptExplode = explode("-",$receiptNo);

        $paymentNoSl = $paymentNoExplode[1];
        $receiptNoSl = $receiptExplode[1];

        $request->financial_year = financialYearToYear($request->financial_year);

        TransactionLog::where('balance_transfer_id',$transfer->id)->delete();

        if ($request->type == 1) {
            // Bank To Cash

            //Bank Credit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $paymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Bank debit
            $log->payment_type = 1;
            $log->cheque_no = $request->source_bank_cheque_no;
            $log->cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            //Cash Debit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Cash Debit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();
        }elseif ($request->type == 2) {
            // Cash To Bank

            //Cash Credit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Cash Credit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            //Bank Debit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $paymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Bank debit
            $log->payment_type = 1;
            $log->cheque_no = $request->source_bank_cheque_no;
            $log->cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();

        }elseif ($request->type == 3) {
            // Bank To Bank
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $voucherNo;
            $log->receipt_payment_sl = $paymentNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Bank Credit
            $log->payment_type = 1;
            $log->cheque_no = $request->source_bank_cheque_no;
            $log->cheque_date = $request->source_bank_cheque_date ? Carbon::parse($request->source_bank_cheque_date)->format('Y-m-d') : null;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Bank Debit
            $log->payment_type = 1;
            $log->cheque_no = $request->target_bank_cheque_no;
            $log->cheque_date = $request->target_bank_cheque_date ? Carbon::parse($request->target_bank_cheque_date)->format('Y-m-d') : null;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();
        }elseif ($request->type == 4) {
            // Cash To Cash

            //Cash Credit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;//Cash Credit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->source_account_head_code;
            $log->save();

            //Cash Debit
            $log = new TransactionLog();
            $log->date = Carbon::parse($request->date)->format('Y-m-d');
            $log->receipt_payment_no = $receiptNo;
            $log->receipt_payment_sl = $receiptNoSl;
            $log->financial_year = financialYear($request->financial_year);
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;//Cash Debit
            $log->payment_type = 2;
            $log->amount = $request->amount;
            $log->notes = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->account_head_id = $request->target_account_head_code;
            $log->save();

        }else{
            return redirect()->route('balance_transfer.add')
                ->withInput()
                ->with('error', 'Balance transfer error.');
        }

        return redirect()->route('balance_transfer')
            ->with('message', 'Balance transfer updated successful.');
    }


}
