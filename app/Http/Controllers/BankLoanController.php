<?php

namespace App\Http\Controllers;

use App\BankLoan;
use App\Model\Bank;
use App\Model\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class BankLoanController extends Controller
{
    public function Index() {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();

    //    $loanHolders = LoanHolder::all();
    $bankLoans = BankLoan::all();
        return view('loan.bank_loan.all',compact('bankLoans','banks'));
    }
    public function add() {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();
        return view('loan.bank_loan.add',compact('banks'));
    }
    public function addPost(Request $request){

        $rules = [
            'loan_holder' => 'required',
            'loan_type' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'payment_type' => 'required',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'cheque_image' => 'nullable|image',
        ];

        $request->validate($rules);


        $loan = new Loan();
        $loan->loan_number= rand(000000,999999);
        $loan->loan_holder_id = $request->loan_holder;
        $loan->loan_type = $request->loan_type;
        $loan->total = $request->amount;
        $loan->date=date("Y-m-d", strtotime($request->date));
        $loan->paid=0;
        $loan->due=$request->amount;
        $loan->save();

        $loan_holder = LoanHolder::find($request->loan_holder);

        if ($request->payment_type == 2) {

            $payment = new LoanPayment();
            $payment->type = $request->loan_type == 1 ? 1 : 3;
            $payment->loan_holder_id = $request->loan_holder;
            $payment->loan_id = $loan->id;
            $payment->amount = $request->amount;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->note = $request->note;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/loan';
                $file->move($destinationPath, $filename);

                $image = 'uploads/loan/'.$filename;
            }
            // $log = new TransactionLog();
            // $log->date = $request->date;
            // $log->particular = "Loan Take from ".$loan_holder->name;
            // $log->transaction_type = 1;
            // $log->transaction_method = $request->payment_type;
            // $log->account_head_type_id = $payment->type == 1 ? 19 : 21;
            // $log->account_head_sub_type_id = $payment->type == 1 ? 19 : 21;
            // $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
            // $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
            // $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
            // $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
            // $log->cheque_image = $image;
            // $log->amount = $request->amount;
            // $log->note = $request->note;
            // $log->loan_holder_id = $request->loan_holder;
            // $log->loan_payment_id = $payment->id;
            // $log->save();

            // if ($payment->type == 1)
            //     BankAccount::find($request->account)->increment('balance', $request->amount);
            // else
            //     BankAccount::find($request->account)->decrement('balance', $request->amount);
        }

        if ($request->payment_type == 1) {
            $payment = new LoanPayment();
            $payment->type = $request->loan_type == 1 ? 1 : 3;
            $payment->loan_holder_id = $request->loan_holder;
            $payment->loan_id = $loan->id;
            $payment->amount = $request->amount;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

            // $log = new TransactionLog();
            // $log->date = $request->date;
            // $log->particular = "Loan Take from ".$loan_holder->name;
            // $log->transaction_type = 1;
            // $log->transaction_method = $request->payment_type;
            // $log->account_head_type_id = $payment->type == 1 ? 19 : 21;
            // $log->account_head_sub_type_id = $payment->type == 1 ? 19 : 21;
            // $log->amount = $request->amount;
            // $log->note = $request->note;
            // $log->loan_holder_id = $request->loan_holder;
            // $log->loan_payment_id = $payment->id;
            // $log->save();

            // if ($payment->type == 1)
            //     Cash::first()->increment('amount', $request->amount);
            // else
            //     Cash::first()->decrement('amount', $request->amount);

        }

        return redirect()->route('loan_payment_print', ['payment'=> $payment->id]);

    }

    public function bankLoanDatatable() {

        // $query = BankLoan::with('loanHolder')
        //     ->select(DB::raw('sum(`total`) as total,sum(`due`) as due ,sum(`paid`) as paid,loan_type, loan_holder_id'))
        //     ->groupBy('loan_holder_id','loan_type');
        $query = Loan::with('loanHolder','project','client');

        return DataTable::eloquent($query)
            ->addColumn( function() {
                foreach ($query as $data) {
                    return $data->name??'';
                }
            })
            ->addColumn('action', function($query) {
                foreach ($query as $data) {
                    return '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$data->id.'" data-type-id="'.$loan->loan_type.'" data-name="'.$loan->loanHolder->name.'">পেমেন্ট</a> <a href="'.route('loan_details', ['loanHolder' => $loan->loan_holder_id,'type'=>$loan->loan_type]).'" class="btn btn-primary btn-sm">বিস্তারিত</a>';
                }

            })
            ->addColumn('loan_type', function(BankLoan $bankLoan) {
                if($bankLoan->loan_type == 1)
                    return '<label class="label label-warning">গৃহীত লোন </span>';
                else
                    return '<label class="label label-primary">প্রদানকৃত লোন</span>';
            })
            ->editColumn('paid', function(BankLoan $bankLoan) {
                return '৳ '.number_format($bankLoan->amount, 2);
            })
            // ->editColumn('due', function(BankLoan $bankLoan) {
            //     return '৳ '.number_format($bankLoan->due, 2);
            // })
            // ->editColumn('total', function(BankLoan $bankLoan) {
            //     return '৳ '.number_format($bankLoan->total, 2);
            // })
            // ->rawColumns(['action','loan_type'])
            ->toJson();
    }

    public function loanPrint(Request $request, $loan_id)
    {
        $loan = Loan::where('id', $loan_id)->first();
        $transaction = TransactionLog::where('loan_id', $loan_id)->first();
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount, 'Taka', 'Poisa');
        return view('loan.print', compact('transaction', 'loan'));
    }
    public function loanPaymentGetNumber(Request $request) {
        $loans = Loan::where('loan_holder_id', $request->holderId)
            ->where('loan_type',$request->loanType)
            ->where('due', '>', 0)
            ->get()->toArray();
        return response()->json($loans);
    }
    public function loanPaymentOrderDetails(Request $request){
        $loan = Loan::where('id', $request->loanId)
            ->first()->toArray();

        return response()->json($loan);
    }
    public function makePayment(Request $request) {
//
        $rules = [
            'loan_holder_id' => 'required',
            'loan_id' => 'required',
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

        $loan = Loan::find($request->loan_id);
        $validator = Validator::make($request->all(), $rules);


        // $validator->after(function ($validator) use ($request) {

        //     if ($request->loan_type_id == 1) {
        //         if ($request->payment_type == 1) {
        //             $cash = Cash::first();

        //             if ($request->amount > $cash->amount)
        //                 $validator->errors()->add('amount', 'Insufficient balance.');
        //         }
        //         else {
        //             if ($request->account != '') {
        //                 $account = BankAccount::find($request->account);

        //                 if ($request->amount > $account->balance)
        //                     $validator->errors()->add('amount', 'Insufficient balance.');
        //             }
        //         }
        //     }

        // });
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        // return "Work";
        $loan_holder = LoanHolder::where('id', $request->loan_holder_id)->first();


        if ($request->payment_type == 1 ) {
            $payment = new LoanPayment();
            $payment->type = $request->loan_type_id == 1 ? 2 : 4;
            $payment->loan_holder_id = $request->loan_holder_id;
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

            // if($request->loan_type_id == 1)
            //     Cash::first()->decrement('amount', $request->amount);
            // else
            //     Cash::first()->increment('amount', $request->amount);

            // $log = new TransactionLog();
            // $log->particular = $request->loan_type_id == 1 ? 'Loan Payment to '. $loan_holder->name : 'Loan Payment from '. $loan_holder->name;
            // $log->date = $request->date;
            // $log->transaction_type = $request->loan_type_id == 1 ? 2 : 1;
            // $log->transaction_method = $request->payment_type;
            // $log->account_head_type_id = $request->loan_type_id == 1 ? 20 : 22;
            // $log->account_head_sub_type_id = $request->loan_type_id == 1 ? 20 : 22;
            // $log->amount = $request->amount;
            // $log->note = $request->note;
            // $log->loan_holder_id = $request->loan_holder_id;
            // $log->loan_payment_id = $payment->id;
            // $log->save();

        }else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/loan';
                $file->move($destinationPath, $filename);

                $image = 'uploads/loan/'.$filename;
            }
            $payment = new LoanPayment();
            $payment->type = $request->loan_type_id == 1 ? 2 : 4;
            $payment->loan_holder_id = $request->loan_holder_id;
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->note = $request->note;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

            // if($request->loan_type_id == 1)
            //     BankAccount::find($request->account)->decrement('balance', $request->amount);
            // else
            //     BankAccount::find($request->account)->increment('balance', $request->amount);

            // $log = new TransactionLog();
            // $log->date = $request->date;
            // $log->particular = $request->loan_type_id == 1 ? 'Loan Payment to '. $loan_holder->name : 'Loan Payment from '. $loan_holder->name;
            // $log->transaction_type = $request->loan_type_id == 1 ? 2 : 1;
            // $log->transaction_method = 2;
            // $log->account_head_type_id = $request->loan_type_id == 1 ? 20 : 22;
            // $log->account_head_sub_type_id = $request->loan_type_id == 1 ? 20 : 22;
            // $log->bank_id = $request->bank;
            // $log->branch_id = $request->branch;
            // $log->bank_account_id = $request->account;
            // $log->cheque_no = $request->cheque_no;
            // $log->cheque_image = $image;
            // $log->amount = $request->amount;
            // $log->note = $request->note;
            // $log->loan_holder_id = $request->loan_holder_id;
            // $log->loan_payment_id = $payment->id;
            // $log->save();
        }


        $loan->increment('paid', $request->amount);
        $loan->decrement('due', $request->amount);


        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('loan_payment_print',['payment'=>$payment->id])]);
    }
    public function loanDetails(LoanHolder $loanHolder,$type){
        $loans = Loan::where('loan_holder_id',$loanHolder->id)
            ->where('loan_type',$type)
            ->get();
        return view('loan.loan_details', compact('loans'));
    }
    public function loanPaymentDetails(Loan $loan,$type){
                if($type == 1)
                    $type = 2;
                else
                    $type = 4;
        $payments = LoanPayment::where('loan_id',$loan->id)
                    ->where('type',$type)
                    ->get();
        return view('loan.loan_payment_details', compact('payments'));
    }
    public function loanPaymentPrint(LoanPayment $payment){
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('loan.loan_and_payment_print', compact('payment'));

    }
}
