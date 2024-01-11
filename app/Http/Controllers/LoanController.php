<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Cash;
use App\Model\ClientLoan;
use App\Model\LoanPayment;
use App\Model\LoanTransactionLog;
use App\Model\Client;
use App\Model\Loan;
use App\Model\LoanHolder;
use App\Model\Project;
use App\Model\ProjectLoan;
use App\Model\TransactionLog;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SakibRahaman\DecimalToWords\DecimalToWords;

class LoanController extends Controller
{
    public function Index() {
        $banks = Bank::where('status', 1)
        ->orderBy('name')
        ->get();

        $loanHolders = LoanHolder::all();

        $loans = Loan::all();
        return view('loan.all',compact('loanHolders','banks'));
    }
    public function clientLoan() {
        $banks = Bank::where('status', 1)
        ->orderBy('name')
        ->get();
        return view('loan.client_loan.all',compact('banks'));
    }
    public function projectLoanAll() {
        $banks = Bank::where('status', 1)
        ->orderBy('name')
        ->get();
        return view('loan.project_loan.all',compact('banks'));
    }
    public function Create() {

        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();
        $holders = LoanHolder::where('status',1)->get();
        $clients = Client::where('status',1)->get();
        $projects = Project::where('status',1)->get();
        return view('loan.add',compact('clients', 'projects','banks','holders'));
    }

    public function loanStore(Request $request){

        $rules = [
            'loan_from' => 'required',
            'loan_type' => 'required',
            'payment_type' => 'required',

            'date' => 'required',
            'amount' => 'required',

            'note' => 'nullable|string',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'cheque_image' => 'nullable|image',
            'duration' => 'required',
        ];
        if ($request->loan_from == 1) {
            $rules =[
                'client' => 'required',
                'loan_type' => 'required',
                ];
        }
        if ($request->loan_from == 2) {
            $rules =[
                'project' => 'required',
                'loan_type' => 'required',
            ];
        }
        if ($request->loan_from == 3) {
            $rules =[
                'loan_holder' => 'required',
                'loan_type' => 'required',
            ];
        }

        $request->validate($rules);
        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {

            if ($request->loan_type == 2) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();

                    if ($request->amount > $cash->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
                else {
                    if ($request->account != '') {
                        $account = BankAccount::find($request->account);

                        if ($request->amount > $account->balance)
                            $validator->errors()->add('amount', 'Insufficient balance.');
                    }
                }
            }

        });
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if ($request->loan_holder != '') {
            $loan = new Loan();
            $loan->loan_number= rand(000000,999999);
            $loan->loan_holder_id = $request->loan_holder;
            $loan->loan_type = $request->loan_type;
            $loan->total = $request->amount;
            $loan->duration = $request->duration;
            $loan->interest = $request->interest;
            $loan->date=date("Y-m-d", strtotime($request->date));
            $loan->paid=0;
            $loan->due=$request->amount;
            $loan->note=$request->note;
            $loan->save();
        }elseif ($request->project != ''){
            $loan = new ProjectLoan();
            $loan->loan_number= rand(000000,999999);
            $loan->project_id = $request->project;
            $loan->loan_type = $request->loan_type;
            $loan->total = $request->amount;
            $loan->duration = $request->duration;
            $loan->interest = $request->interest;
            $loan->date=date("Y-m-d", strtotime($request->date));
            $loan->paid=0;
            $loan->due=$request->amount;
            $loan->note=$request->note;
            $loan->save();
        }else {
            $loan = new ClientLoan();
            $loan->loan_number= rand(000000,999999);
            $loan->client_id = $request->client;
            $loan->loan_type = $request->loan_type;
            $loan->total = $request->amount;
            $loan->duration = $request->duration;
            $loan->interest = $request->interest;
            $loan->date=date("Y-m-d", strtotime($request->date));
            $loan->paid=0;
            $loan->due=$request->amount;
            $loan->note=$request->note;
            $loan->save();
        }

        $loan_holder = LoanHolder::find($request->loan_holder);
        $client = Client::find($request->client);
        $project = Project::find($request->project);

        if ($request->payment_type == 2) {

            $payment = new LoanPayment();
            $payment->type = $request->loan_type == 1 ? 1 : 3;
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
             $log = new TransactionLog();
             $log->date = date("Y-m-d", strtotime($request->date));
             if ($payment->type == 1) {
                 if ($request->loan_from == 1) {
                     $log->particular = "Loan Take from ".$client->name;
                 }elseif ($request->loan_from == 2){
                     $log->particular = "Loan Take from ".$project->name;
                 }else{
                     $log->particular = "Loan Take from ".$loan_holder->name;
                 }
             }else{
                 if ($request->loan_from == 1) {
                     $log->particular = "Loan Give from ".$client->name;
                 }elseif ($request->loan_from == 2){
                     $log->particular = "Loan Give from ".$project->name;
                 }else{
                     $log->particular = "Loan Give from ".$loan_holder->name;
                 }
             }
             $log->transaction_type = 1;
             $log->transaction_method = $request->payment_type;
             $log->account_head_type_id = $payment->type == 1 ? 19 : 21;
             $log->account_head_sub_type_id = $payment->type == 1 ? 19 : 21;
             $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
             $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
             $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
             $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
             $log->cheque_image = $image;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->loan_payment_id = $payment->id;
             $log->save();

             if ($payment->type == 1)
                 BankAccount::find($request->account)->increment('balance', $request->amount);
             else
                 BankAccount::find($request->account)->decrement('balance', $request->amount);
        }

        if ($request->payment_type == 1) {
            $payment = new LoanPayment();
            $payment->type = $request->loan_type == 1 ? 1 : 3;
            $payment->loan_id = $loan->id;
            $payment->amount = $request->amount;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

             $log = new TransactionLog();
             $log->date = date("Y-m-d", strtotime($request->date));
            if ($payment->type == 1) {
                if ($request->loan_from == 1) {
                    $log->particular = "Loan Take from ".$client->name;
                }elseif ($request->loan_from == 2){
                    $log->particular = "Loan Take from ".$project->name;
                }else{
                    $log->particular = "Loan Take from ".$loan_holder->name;
                }
            }else{
                if ($request->loan_from == 1) {
                    $log->particular = "Loan Give from ".$client->name;
                }elseif ($request->loan_from == 2){
                    $log->particular = "Loan Give from ".$project->name;
                }else{
                    $log->particular = "Loan Give from ".$loan_holder->name;
                }
            }
             $log->transaction_type = 1;
             $log->transaction_method = $request->payment_type;
             $log->account_head_type_id = $payment->type == 1 ? 19 : 21;
             $log->account_head_sub_type_id = $payment->type == 1 ? 19 : 21;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->loan_payment_id = $payment->id;
             $log->save();

             if ($payment->type == 1)
                 Cash::first()->increment('amount', $request->amount);
             else
                 Cash::first()->decrement('amount', $request->amount);

        }

        //return redirect()->route('loan_payment_print', ['payment'=> $payment->id]);
        if ($request->loan_holder != '') {
            return redirect()->route('loan.all')->with('message', 'Loan Created successfully.');
        }
        if ($request->client != '') {
            return redirect()->route('client_loan.all')->with('message', 'Client Loan Created successfully.');
        }
        if ($request->project != '') {
            return redirect()->route('project_loan.all')->with('message', 'Project Loan Created successfully.');
        }

    }

    public function loanDatatable() {

        $query = Loan::with('loanHolder')
            ->select(DB::raw('sum(`total`) as total,sum(`due`) as due ,sum(`paid`) as paid,loan_type, loan_holder_id'))
            ->groupBy('loan_holder_id','loan_type');

        return DataTables::eloquent($query)
            ->addColumn('holder', function(Loan $loan) {
                return $loan->loanHolder->name??'';
            })

            ->addColumn('action', function(Loan $loan) {
                return '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$loan->loan_holder_id.'" data-type-id="'.$loan->loan_type.'" data-name="'.$loan->loanHolder->name.'">Payment</a> <a href="'.route('loan_details', ['loanHolder' => $loan->loan_holder_id,'type'=>$loan->loan_type]).'" class="btn btn-primary btn-sm">Details</a>';
            })

            ->addColumn('loan_type', function(Loan $loan) {
                if($loan->loan_type == 1)
                    return '<label class="label label-warning">Taken </span>';
                else
                    return '<label class="label label-primary">Given</span>';
            })
            ->editColumn('paid', function(Loan $loan) {
                return ' '.number_format($loan->paid, 2);
            })
            ->editColumn('due', function(Loan $loan) {
                return ' '.number_format($loan->due, 2);
            })
            ->editColumn('total', function(Loan $loan) {
                return ' '.number_format($loan->total, 2);
            })
            ->rawColumns(['action','loan_type'])
            ->toJson();
    }

    public function clientLoanDatatable() {

        $query = ClientLoan::with('client')
            ->select(DB::raw('sum(`total`) as total,sum(`paid`) as paid,sum(`due`) as due,loan_type,client_id'))
            ->groupBy('client_id','loan_type');


        return DataTables::eloquent($query)
            ->addColumn('client', function(ClientLoan $clientLoan) {
                return $clientLoan->client->name??'';
            })

            ->addColumn('action', function(ClientLoan $clientLoan) {
                return '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$clientLoan->client_id.'" data-type-id="'.$clientLoan->loan_type.'" data-name="'.$clientLoan->client->name.'">Payment</a> <a href="'.route('client_loan_details', ['clientLoan' => $clientLoan->client_id,'type'=>$clientLoan->loan_type]).'" class="btn btn-primary btn-sm">Details</a>';
            })

            ->addColumn('loan_type', function(ClientLoan $clientLoan) {
                if($clientLoan->loan_type == 1)
                    return '<label class="label label-warning">Taken </span>';
                else
                    return '<label class="label label-primary">Given</span>';
            })
            ->editColumn('paid', function(ClientLoan $clientLoan) {
                return ' '.number_format($clientLoan->paid, 2);
            })
            ->editColumn('due', function(ClientLoan $clientLoan) {
                return ' '.number_format($clientLoan->due, 2);
            })
            ->editColumn('total', function(ClientLoan $clientLoan) {
                return ' '.number_format($clientLoan->total, 2);
            })
            ->rawColumns(['action','loan_type','client'])
            ->toJson();
    }

    public function projectLoanDatatable() {

        $query = ProjectLoan::with('project')
            ->select(DB::raw('sum(`total`) as total,sum(`due`) as due ,sum(`paid`) as paid,loan_type, project_id'))
            ->groupBy('project_id','loan_type');


        return DataTables::eloquent($query)
            ->addColumn('project', function(ProjectLoan $projectLoan) {
                return $projectLoan->project->name??'';
            })

            ->addColumn('action', function(ProjectLoan $projectLoan) {
                return '<a class="btn btn-success btn-sm btn-pay" role="button" data-id="'.$projectLoan->project_id.'" data-type-id="'.$projectLoan->loan_type.'" data-name="'.$projectLoan->project->name.'">Payment</a> <a href="'.route('project_loan_details',['loan' => $projectLoan->project_id,'type'=>$projectLoan->loan_type]). '" class="btn btn-primary btn-sm">Details</a> ';
            })


            ->addColumn('loan_type', function(ProjectLoan $projectLoan) {
                if($projectLoan->loan_type == 1)
                    return '<label class="label label-warning">Taken </span>';
                else
                    return '<label class="label label-primary">Given</span>';
            })
            ->editColumn('paid', function(ProjectLoan $projectLoan) {
                return ' '.number_format($projectLoan->paid, 2);
            })
            ->editColumn('due', function(ProjectLoan $projectLoan) {
                return ' '.number_format($projectLoan->due, 2);
            })
            ->editColumn('total', function(ProjectLoan $projectLoan) {
                return ' '.number_format($projectLoan->total, 2);
            })
            ->rawColumns(['action','loan_type'])
            ->toJson();
    }

    public function loanPaymentGetNumber(Request $request) {
        $loans = Loan::where('loan_holder_id', $request->holderId)
            ->where('due', '>', 0)
            ->get()->toArray();
        return response()->json($loans);
    }

    public function clientLoanPaymentGetNumber(Request $request) {
        $loans = ClientLoan::where('client_id', $request->clientId)
            ->where('due', '>', 0)
            ->get()->toArray();
        return response()->json($loans);
    }
    public function projectLoanPaymentGetNumber(Request $request) {
        $loans = ProjectLoan::where('project_id', $request->projectId)
            ->where('due', '>', 0)
            ->get()->toArray();
        return response()->json($loans);
    }
    public function loanPaymentDetails(Loan $loan,$type){

        if ($type == 2) {
            $payments = LoanPayment::where('loan_id',$loan->id)
                ->where('loan_holder_id',$loan->loan_holder_id)
                ->where('type',4)
                ->get();
        }else{
            $payments = LoanPayment::where('loan_id',$loan->id)
                ->where('loan_holder_id',$loan->loan_holder_id)
                ->where('type',2)
                ->get();
        }

        return view('loan.loan_payment_details', compact('payments'));
    }
    public function clientLoanPaymentDetails(ClientLoan $loan,$type){

        if ($type == 2) {
            $payments = LoanPayment::where('loan_id',$loan->id)
                ->where('client_id',$loan->client_id)
                ->where('type',4)
                ->get();
        }else{
            $payments = LoanPayment::where('loan_id',$loan->id)
                ->where('client_id',$loan->client_id)
                ->where('type',2)
                ->get();
        }



        return view('loan.loan_payment_details', compact('payments'));
    }
    public function projectLoanPaymentDetails(ProjectLoan $loan,$type){

        if ($type == 2) {
            $payments = LoanPayment::where('loan_id',$loan->id)
                ->where('project_id',$loan->project_id)
                ->where('type',4)
                ->get();
        }else{
            $payments = LoanPayment::where('loan_id',$loan->id)
                ->where('project_id',$loan->project_id)
                ->where('type',2)
                ->get();
        }

        return view('loan.loan_payment_details', compact('payments'));
    }
    // public function loanPaymentDetails ( $loan,$type){
    //     $loan = Loan::where('client_id',$loan)->where('loan_type',$type)->get();

    //     return view('loan.receipt.loan_payment_details',compact('loan'));

    // }
    public function loanDetails(LoanHolder $loanHolder,$type){
        $loans = Loan::where('loan_holder_id',$loanHolder->id)
            ->where('loan_type',$type)
            ->get();
        return view('loan.loan_details', compact('loans'));
    }
    public function clientLoanDetails($clientId, $type){
        $loans = ClientLoan::where('client_id', $clientId)
            ->where('loan_type',$type)
            ->get();
        return view('loan.client_loan.details', compact('loans'));
    }
    public function projectLoanDetails($loan,$type){

        $loans = ProjectLoan::where('project_id',$loan)
            ->where('loan_type',$type)
            ->get();
        return view('loan.project_loan.details', compact('loans'));
    }

    public function projectLoanPrint($loan, $type){
        $loan = ProjectLoan::where('id', $loan)
            ->first();
        $loan->amount_in_word = DecimalToWords::convert(
            $loan->total,
            'Taka',
            'Poisa'
        );

        return view('loan.receipt.project_loan_print', compact('loan'));
    }
    public function holderLoanPrint($loan, $type)
    {
        $loan = Loan::where('id', $loan)
            ->first();
        $loan->amount_in_word = DecimalToWords::convert(
            $loan->total,
            'Taka',
            'Poisa'
        );

        return view('loan.receipt.holder_loan_print', compact('loan'));
    }
    public function clientLoanPrint($loan, $type)
    {
        $loan = ClientLoan::where('id', $loan)
            ->first();
        $loan->amount_in_word = DecimalToWords::convert(
            $loan->total,
            'Taka',
            'Poisa'
        );

        return view('loan.receipt.client_loan_print', compact('loan'));
    }

    public function loanPaymentPrint(LoanPayment $payment){
        $payment->amount_in_word = DecimalToWords::convert($payment->amount,'Taka',
            'Poisa');
        return view('loan.receipt.payment_print', compact('payment'));
    }
    public function makePayment(Request $request) {

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
            $payment->due = $loan->due -$request->amount;
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
            $payment->due = $loan->due - $request->amount;
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
    public function clientLoanmakePayment(Request $request) {

        $rules = [
            'client_id' => 'required',
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

        $loan = ClientLoan::find($request->loan_id);
        $validator = Validator::make($request->all(), $rules);


         $validator->after(function ($validator) use ($request) {

             if ($request->loan_type_id == 1) {
                 if ($request->payment_type == 1) {
                     $cash = Cash::first();

                     if ($request->amount > $cash->amount)
                         $validator->errors()->add('amount', 'Insufficient balance.');
                 }
                 else {
                     if ($request->account != '') {
                         $account = BankAccount::find($request->account);

                         if ($request->amount > $account->balance)
                             $validator->errors()->add('amount', 'Insufficient balance.');
                     }
                 }
             }

         });
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        // return "Work";
        $loan_holder = Client::where('id', $request->client_id)->first();


        if ($request->payment_type == 1 ) {
            $payment = new LoanPayment();
            $payment->type = $request->loan_type_id == 1 ? 2 : 4;
            $payment->client_id = $request->client_id;
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->due = $loan->due -$request->amount;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

             if($request->loan_type_id == 1)
                 Cash::first()->decrement('amount', $request->amount);
             else
                 Cash::first()->increment('amount', $request->amount);

             $log = new TransactionLog();
             $log->particular = $request->loan_type_id == 1 ? 'Loan Payment to '. $loan_holder->name : 'Loan Payment from '. $loan_holder->name;
             $log->date = $request->date;
             $log->transaction_type = $request->loan_type_id == 1 ? 2 : 1;
             $log->transaction_method = $request->payment_type;
             $log->account_head_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->account_head_sub_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->client_id = $request->client_id;
             $log->loan_payment_id = $payment->id;
             $log->save();

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
            $payment->client_id = $request->client_id;
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->due = $loan->due - $request->amount;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->note = $request->note;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

             if($request->loan_type_id == 1)
                 BankAccount::find($request->account)->decrement('balance', $request->amount);
             else
                 BankAccount::find($request->account)->increment('balance', $request->amount);

             $log = new TransactionLog();
             $log->date = $request->date;
             $log->particular = $request->loan_type_id == 1 ? 'Loan Payment to '. $loan_holder->name : 'Loan Payment from '. $loan_holder->name;
             $log->transaction_type = $request->loan_type_id == 1 ? 2 : 1;
             $log->transaction_method = 2;
             $log->account_head_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->account_head_sub_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->bank_id = $request->bank;
             $log->branch_id = $request->branch;
             $log->bank_account_id = $request->account;
             $log->cheque_no = $request->cheque_no;
             $log->cheque_image = $image;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->client_id = $request->client_id;
             $log->loan_payment_id = $payment->id;
             $log->save();
        }

        $loan->increment('paid', $request->amount);
        $loan->decrement('due', $request->amount);

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('loan_payment_print',['payment'=>$payment->id])]);

    }
    public function projectLoanmakePayment(Request $request) {

        //dd($request->all());

        $rules = [
            'project_id' => 'required',
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

        $loan = ProjectLoan::find($request->loan_id);
        $validator = Validator::make($request->all(), $rules);


         $validator->after(function ($validator) use ($request) {

             if ($request->loan_type_id == 1) {
                 if ($request->payment_type == 1) {
                     $cash = Cash::first();

                     if ($request->amount > $cash->amount)
                         $validator->errors()->add('amount', 'Insufficient balance.');
                 }
                 else {
                     if ($request->account != '') {
                         $account = BankAccount::find($request->account);

                         if ($request->amount > $account->balance)
                             $validator->errors()->add('amount', 'Insufficient balance.');
                     }
                 }
             }

         });
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        // return "Work";
        $loan_holder = Project::where('id', $request->project_id)->first();

        if ($request->payment_type == 1 ) {
            $payment = new LoanPayment();
            $payment->type = $request->loan_type_id == 1 ? 2 : 4;
            $payment->project_id = $request->project_id;
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->due = $loan->due -$request->amount;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

             if($request->loan_type_id == 1)
                 Cash::first()->decrement('amount', $request->amount);
             else
                 Cash::first()->increment('amount', $request->amount);

             $log = new TransactionLog();
             $log->particular = $request->loan_type_id == 1 ? 'Loan Payment to '. $loan_holder->name : 'Loan Payment from '. $loan_holder->name;
             $log->date = $request->date;
             $log->transaction_type = $request->loan_type_id == 1 ? 2 : 1;
             $log->transaction_method = $request->payment_type;
             $log->account_head_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->account_head_sub_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->project_id = $request->project_id;
             $log->loan_payment_id = $payment->id;
             $log->save();

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
            $payment->project_id = $request->project_id;
            $payment->loan_id = $request->loan_id;
            $payment->amount = $request->amount;
            $payment->due = $loan->due - $request->amount;
            $payment->bank_id = $request->bank;
            $payment->branch_id = $request->branch;
            $payment->bank_account_id = $request->account;
            $payment->cheque_no = $request->cheque_no;
            $payment->note = $request->note;
            $payment->transaction_method = $request->payment_type;
            $payment->date = date("Y-m-d", strtotime($request->date));
            $payment->save();

             if($request->loan_type_id == 1)
                 BankAccount::find($request->account)->decrement('balance', $request->amount);
             else
                 BankAccount::find($request->account)->increment('balance', $request->amount);

             $log = new TransactionLog();
             $log->date = $request->date;
             $log->particular = $request->loan_type_id == 1 ? 'Loan Payment to '. $loan_holder->name : 'Loan Payment from '. $loan_holder->name;
             $log->transaction_type = $request->loan_type_id == 1 ? 2 : 1;
             $log->transaction_method = 2;
             $log->account_head_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->account_head_sub_type_id = $request->loan_type_id == 1 ? 20 : 22;
             $log->bank_id = $request->bank;
             $log->branch_id = $request->branch;
             $log->bank_account_id = $request->account;
             $log->cheque_no = $request->cheque_no;
             $log->cheque_image = $image;
             $log->amount = $request->amount;
             $log->note = $request->note;
             $log->project_id = $request->project_id;
             $log->loan_payment_id = $payment->id;
             $log->save();
        }

        $loan->increment('paid', $request->amount);
        $loan->decrement('due', $request->amount);

        return response()->json(['success' => true, 'message' => 'Payment has been completed.', 'redirect_url' => route('loan_payment_print',['payment'=>$payment->id])]);

    }

    public function salePaymentDetails(Loan $loan) {
        $loan->amount_in_word = DecimalToWords::convert($loan->amount,'Taka',
            'Poisa');
        return view('loan.receipt.payment_details', compact('loan'));
    }

    public function loanPaymentOrderDetails(Request $request){
        $loan = Loan::where('id', $request->loanId)
            ->first()->toArray();

        return response()->json($loan);
    }
    public function clientLoanPaymentOrderDetails(Request $request){
        $loan = ClientLoan::where('id', $request->loanId)
            ->first()->toArray();

        return response()->json($loan);
    }
    public function projectLoanPaymentOrderDetails(Request $request){
        $loan = ProjectLoan::where('id', $request->loanId)
            ->first()->toArray();

        return response()->json($loan);
    }


}
