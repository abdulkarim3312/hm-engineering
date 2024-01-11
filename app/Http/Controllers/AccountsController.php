<?php

namespace App\Http\Controllers;

use App\Model\AccountHeadSubType;
use App\Model\AccountHeadType;
use App\Model\BalanceTransfer;
use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Cash;
use App\Model\Cheeque;
use App\Model\MobileBanking;
use App\Model\Project;
use App\Model\Transaction;
use App\Model\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use DataTables;

class AccountsController extends Controller
{
    public function accountHeadType() {
        $types = AccountHeadType::all();

        return view('accounts.account_head_type.all', compact('types'));
    }

    public function accountHeadTypeAdd() {
        return view('accounts.account_head_type.add');
    }

    public function accountHeadTypeAddPost(Request $request) {
        $request->validate([

            'name' => 'required|string|unique:account_head_types|max:255',
            'status' => 'required'
        ]);

        $type = new AccountHeadType();
        $type->name = $request->name;
        $type->status = $request->status;
        $type->save();

        return redirect()->route('account_head.type')->with('message', 'Account head type add successfully.');
    }

    public function accountHeadTypeEdit(AccountHeadType $type) {
        return view('accounts.account_head_type.edit', compact('type'));
    }

    public function accountHeadTypeEditPost(AccountHeadType $type, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:account_head_types,name,'.$type->id,
            'status' => 'required'
        ]);

        $type->name = $request->name;
        $type->status = $request->status;
        $type->save();

        return redirect()->route('account_head.type')->with('message', 'Account head type edit successfully.');
    }


    public function transactionIndex() {
        return view('accounts.transaction.all');
    }

    public function transactionAdd() {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();
        $projects = Project::where('status',1)->get();
        return view('accounts.transaction.add', compact('banks','projects'));
    }

    public function transactionEdit(Request $request, $id) {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();
        $projects = Project::where('status',1)->get();
        $transaction = Transaction::where('id', $id)->first();
        return view('accounts.transaction.edit', compact('banks','projects', 'transaction'));
    }

    public function transactionAddPost(Request $request) {
        $messages = [
            'location.required_if' => 'The location field is required.',
            'bank.required_if' => 'The bank field is required.',
            'branch.required_if' => 'The branch field is required.',
            'account.required_if' => 'The account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:2',
            'account_head_type' => 'required',
            'account_head_sub_type' => 'required',
            'payment_type' => 'required|integer|min:1|max:2',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request) {
            if ($request->type == 2) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();

                    if ($request->amount > $cash->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } elseif ($request->payment_type == 3) {
                    $mobile_banking = MobileBanking::first();

                    if ($request->amount > $mobile_banking->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
                else {
                    $bankAccount = BankAccount::find($request->account);

                    if ($request->amount > $bankAccount->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }

            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $image = null;
        if ($request->payment_type == 2) {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/transaction_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/transaction_cheque/'.$filename;
            }
        }
        $transaction = new Transaction();
        $transaction->transaction_type = $request->type;
        $transaction->receipt_no =  random_int(1000000,9999999);
        $transaction->account_head_type_id = $request->account_head_type;
        $transaction->account_head_sub_type_id = $request->account_head_sub_type;
        $transaction->transaction_method = $request->payment_type;
        $transaction->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $transaction->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $transaction->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $transaction->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $transaction->cheque_image = $image;
        $transaction->cheque_date = $request->cheque_date;
        $transaction->amount = $request->amount;
        $transaction->date = $request->date;
        $transaction->note = $request->note;
        $transaction->save();

        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $request->amount);
            }elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $request->amount);
            }

            else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $request->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $request->amount);
            }elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $request->amount);
            }
            else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $request->amount);
            }
        }

        $accountHeadSubType = AccountHeadSubType::find($request->account_head_sub_type);

        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = $accountHeadSubType->name;
        $log->transaction_type = $request->type;
        $log->transaction_method = $request->payment_type;
        $log->account_head_type_id = $request->account_head_type;
        $log->account_head_sub_type_id = $request->account_head_sub_type;
        $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $log->cheque_image = $image;
        $log->amount = $request->amount;
        $log->note = $request->note;
        $log->transaction_id = $transaction->id;
        $log->save();

        return redirect()->away(route('transaction.print', ['transaction' => $transaction->id]));
    }


    public function transactionEditPost(Request $request, $id) {
        $transaction = Transaction::where('id', $id)->first();
        $messages = [
            'location.required_if' => 'The location field is required.',
            'bank.required_if' => 'The bank field is required.',
            'branch.required_if' => 'The branch field is required.',
            'account.required_if' => 'The account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:2',
            'account_head_type' => 'required',
            'account_head_sub_type' => 'required',
            'payment_type' => 'required|integer|min:1|max:2',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request, $transaction) {
            $amount = $transaction->amount - $request->amount;
            if ($request->type == 2) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();

                    if ($amount > $cash->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } elseif ($request->payment_type == 3) {
                    $mobile_banking = MobileBanking::first();

                    if ($amount > $mobile_banking->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
                else {
                    $bankAccount = BankAccount::find($request->account);

                    if ($amount > $bankAccount->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }

            }
        });


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $transaction->amount);
            } elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $transaction->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $transaction->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $transaction->amount);
            } elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $transaction->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $transaction->amount);
            }
        }


        $image = $transaction->cheque_image;
        if ($request->payment_type == 2) {
            $image = $transaction->cheque_image;

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/transaction_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/transaction_cheque/'.$filename;
            }
        }

        $transaction->transaction_type = $request->type;
        $transaction->account_head_type_id = $request->account_head_type;
        $transaction->account_head_sub_type_id = $request->account_head_sub_type;
        $transaction->transaction_method = $request->payment_type;
        $transaction->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $transaction->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $transaction->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $transaction->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $transaction->cheque_image = $image;
        $transaction->cheque_date = $request->cheque_date;
        $transaction->amount = $request->amount;
        $transaction->date = $request->date;
        $transaction->note = $request->note;
        $transaction->save();

        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $request->amount);
            }elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $request->amount);
            }

            else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $request->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $request->amount);
            }elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $request->amount);
            }
            else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $request->amount);
            }
        }

        $accountHeadSubType = AccountHeadSubType::find($request->account_head_sub_type);

        $log = TransactionLog::where('transaction_id', $transaction->id)->first();
        $log->date = $request->date;
        $log->particular = $accountHeadSubType->name;
        $log->transaction_type = $request->type;
        $log->transaction_method = $request->payment_type;
        $log->account_head_type_id = $request->account_head_type;
        $log->account_head_sub_type_id = $request->account_head_sub_type;
        $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $log->cheque_image = $image;
        $log->amount = $request->amount;
        $log->note = $request->note;
        $log->transaction_id = $transaction->id;
        $log->save();

        return redirect()->away(route('transaction.print', ['transaction' => $transaction->id]));
    }

    public function transactionProjectWiseEdit(Request $request, $id)
    {
        $banks = Bank::where('status', 1)
        ->orderBy('name')
        ->get();
        $projects = Project::where('status', 1)->get();
        $transaction = Transaction::where('id', $id)->first();
        return view('accounts.transaction.project_wise_edit', compact('banks', 'projects', 'transaction'));
    }

    public function transactionProjectWiseEditPost(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)->first();
        $messages = [
            'location.required_if' => 'The location field is required.',
            'bank.required_if' => 'The bank field is required.',
            'branch.required_if' => 'The branch field is required.',
            'account.required_if' => 'The account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:2',
            'account_head_type' => 'required',
            'account_head_sub_type' => 'required',
            'project' => 'required',
            'payment_type' => 'required|integer|min:1|max:2',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request, $transaction) {
            $amount = $transaction->amount - $request->amount;
            if ($request->type == 2) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();

                    if ($amount > $cash->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } elseif ($request->payment_type == 3) {
                    $mobile_banking = MobileBanking::first();

                    if ($amount > $mobile_banking->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } else {
                    $bankAccount = BankAccount::find($request->account);

                    if ($amount > $bankAccount->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
            }
        });


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $transaction->amount);
            } elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $transaction->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $transaction->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $transaction->amount);
            } elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $transaction->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $transaction->amount);
            }
        }


        $image = $transaction->cheque_image;
        if ($request->payment_type == 2) {
            $image = $transaction->cheque_image;

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/transaction_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/transaction_cheque/' . $filename;
            }
        }

        $transaction->transaction_type = $request->type;
        $transaction->account_head_type_id = $request->account_head_type;
        $transaction->account_head_sub_type_id = $request->account_head_sub_type;
        $transaction->transaction_method = $request->payment_type;
        $transaction->project_id = $request->project;
        $transaction->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $transaction->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $transaction->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $transaction->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $transaction->cheque_image = $image;
        $transaction->cheque_date = $request->cheque_date;
        $transaction->amount = $request->amount;
        $transaction->date = $request->date;
        $transaction->note = $request->note;
        $transaction->save();

        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $request->amount);
            } elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $request->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $request->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $request->amount);
            } elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $request->amount);
            } else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $request->amount);
            }
        }

        $accountHeadSubType = AccountHeadSubType::find($request->account_head_sub_type);

        $log = TransactionLog::where('transaction_id', $transaction->id)->first();
        $log->date = $request->date;
        $log->project_id = $request->project;
        $log->particular = $accountHeadSubType->name;
        $log->transaction_type = $request->type;
        $log->transaction_method = $request->payment_type;
        $log->account_head_type_id = $request->account_head_type;
        $log->account_head_sub_type_id = $request->account_head_sub_type;
        $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $log->cheque_image = $image;
        $log->amount = $request->amount;
        $log->note = $request->note;
        $log->transaction_id = $transaction->id;
        $log->save();

        return redirect()->away(route('transaction.print', ['transaction' => $transaction->id]));
    }

    public function transactionDelete(Request $request, $id){
        $transaction = Transaction::where('id', $id)->first();
        $log = TransactionLog::where('transaction_id', $transaction->id)->first();
        $log->delete();
        if ($transaction->transaction_type == 1) {
            // Income
            if ($transaction->transaction_method == 1) {
                // Cash
                Cash::first()->decrement('amount', $transaction->amount);
            } elseif ($transaction->transaction_method == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $transaction->amount);
            } else {
                // Bank
                BankAccount::find($transaction->account)->decrement('balance', $transaction->amount);
            }
        } else {
            // Expense
            if ($transaction->transaction_method == 1) {
                // Cash
                Cash::first()->increment('amount', $transaction->amount);
            } elseif ($transaction->transaction_method == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $transaction->amount);
            } else {
                // Bank
                BankAccount::find($transaction->account)->increment('balance', $transaction->amount);
            }
        }

        $transaction->delete();
        return redirect()->back()->with('message','Transaction Deleted successfully Done.');


    }

    public function transactionDetails(Transaction $transaction) {
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount,'Taka',
            'Poisa');

        return view('accounts.transaction.details', compact('transaction'));
    }

    public function transactionPrint(Transaction $transaction) {
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount,'Taka',
            'Poisa');

        return view('accounts.transaction.print', compact('transaction'));
    }

    public function transactionMoneyReceiptPrint(Transaction $transaction) {
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount,'Taka',
            'Poisa');

        return view('accounts.transaction.money_receipt', compact('transaction'));
    }
    public function transactionMoneyReceiptDetails(Transaction $transaction) {
        $transaction->amount_in_word = DecimalToWords::convert($transaction->amount,'Taka',
            'Poisa');

        return view('accounts.transaction.money_receipt_detail', compact('transaction'));
    }


    public function balanceTransferAdd() {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();

        return view('accounts.balance_transfer.add', compact('banks'));
    }

    public function balanceTransferEdit(Request $request, $id) {
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();
        $balance_transfer = BalanceTransfer::where('id', $id)->first();
        return view('accounts.balance_transfer.edit', compact('banks', 'balance_transfer'));
    }
    public function balanceTransfer(){

        return view('accounts.balance_transfer.all');

    }
    public function transferPrint(BalanceTransfer $transfer) {
        $transfer->amount_in_word = DecimalToWords::convert($transfer->amount,'Taka',
            'Poisa');

        return view('accounts.balance_transfer.print', compact('transfer'));
    }
    public function balanceTransferDatatable() {
        $query = BalanceTransfer::with('log');

        return DataTables::eloquent($query)
//

            ->addColumn('action', function(BalanceTransfer $transfer) {
                // return '<a href="'.route('transaction.details', ['transaction' => $transaction->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn = '<a href="'.route('transfer.print', ['transfer' => $transfer->id]). '" class="btn btn-info btn-sm">Print</a> &nbsp;';
                $btn .= '<a href="' . route('balance_transfer.edit', $transfer->id) . '" class="btn btn-primary btn-sm">Edit</a> &nbsp;';
                // $confirm = "Do you want to delete";
                // $btn .= '<a href="' . route('balance_transfer.delete', $transfer->id) . '" class="btn btn-danger btn-sm" onclick="return confirm('.$confirm.')">delete</a>';
                return $btn;
            })
            ->editColumn('date', function(BalanceTransfer $transfer) {
                return $transfer->date;
            })
            ->editColumn('transfer_type', function(BalanceTransfer $transfer) {
                if ($transfer->type == 1)
                    return '<span class="label label-success">Bank To Cash</span>';
                else if($transfer->type == 2)
                    return '<span class="label label-primary">Cash To Bank</span>';
                else if($transfer->type == 3)
                return '<span class="label label-secondary">Bank To Bank</span>';
                else if($transfer->type == 4)
                    return '<span class="label label-info">Cheque To Bank</span>';
                else
                    return '<span class="label label-success">Cheque To Cash</span>';
            })
            ->editColumn('amount', function(BalanceTransfer $transfer) {
                return ''.number_format($transfer->amount, 2);
            })

            ->orderColumn('date', function ($query, $transfer) {
                $query->orderBy('date', $transfer)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action', 'transfer_type'])
            ->toJson();
    }

    public function balanceTransferAddPost(Request $request) {
        $messages = [
            'source_bank.required_if' => 'The source bank field is required.',
            'source_branch.required_if' => 'The source branch field is required.',
            'source_account.required_if' => 'The source account field is required.',
            'target_bank.required_if' => 'The target bank field is required.',
            'target_branch.required_if' => 'The target branch field is required.',
            'target_account.required_if' => 'The target account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:5',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'source_bank' => 'required_if:type,==,1|required_if:type,==,3',
            'source_branch' => 'required_if:type,==,1|required_if:type,==,3',
            'source_account' => 'required_if:type,==,1|required_if:type,==,3',
            'source_cheque_no' => 'nullable|string|max:255',
            'source_cheque_image' => 'nullable|image',
            'target_bank' => 'required_if:type,==,2|required_if:type,==,3',
            'target_branch' => 'required_if:type,==,2required_if:type,==,3',
            'target_account' => 'required_if:type,==,2|required_if:type,==,3',
            'target_cheque_no' => 'nullable|string|max:255',
            'target_cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request) {
            if ($request->type == 1 || $request->type == 3) {
                $bankAccount = BankAccount::find($request->source_account);

                if ($request->amount > $bankAccount->balance)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            } else {
                $cash = Cash::first();

                if ($request->amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sourceImage = null;
        $targetImage = null;
        if ($request->type == 1 || $request->type == 3 ) {
            $sourceImage = 'img/no_image.png';

            if ($request->source_cheque_image) {
                // Upload Image
                $file = $request->file('source_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $sourceImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        if ($request->type == 2 || $request->type == 3 || $request->type == 4) {
            $targetImage = 'img/no_image.png';

            if ($request->target_cheque_image) {
                // Upload Image
                $file = $request->file('target_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $targetImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        $transfer = new BalanceTransfer();
        $transfer->type = $request->type;
        $transfer->source_bank_id = in_array($request->type, [1, 3]) ? $request->source_bank : null;
        $transfer->source_branch_id = in_array($request->type, [1, 3]) ? $request->source_branch : null;
        $transfer->source_bank_account_id = in_array($request->type, [1, 3]) ? $request->source_account : null;
        $transfer->source_cheque_no = in_array($request->type, [1, 3]) ? $request->source_cheque_no : null;
        $transfer->source_cheque_image = $sourceImage;
        $transfer->target_bank_id = in_array($request->type, [2, 3,4]) ? $request->target_bank : null;
        $transfer->target_branch_id = in_array($request->type, [2, 3,4]) ? $request->target_branch : null;
        $transfer->target_bank_account_id = in_array($request->type, [2, 3,4]) ? $request->target_account : null;
        $transfer->target_cheque_no = in_array($request->type, [2, 3,4]) ? $request->target_cheque_no : null;
        $transfer->target_cheque_image = $targetImage;
        $transfer->amount = $request->amount;
        $transfer->date = $request->date;
        $transfer->note = $request->note;
        $transfer->save();
        $transfer->receipt_no = $transfer->id+1000;
        $transfer->save();

        if ($request->type == 1) {
            // Bank To Cash
            BankAccount::find($request->source_account)->decrement('balance', $request->amount);
            Cash::first()->increment('amount', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->transaction_method = 2;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->bank_id = $request->source_bank;
            $log->branch_id = $request->source_branch;
            $log->bank_account_id = $request->source_account;
            $log->cheque_no = $request->source_cheque_no;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->transaction_method = 1;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();
        } elseif ($request->type == 2) {
            // Cash To Bank
            Cash::first()->decrement('amount', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->transaction_method = 1;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->bank_id = $request->target_bank;
            $log->branch_id = $request->target_branch;
            $log->bank_account_id = $request->target_account;
            $log->cheque_no = $request->target_cheque_no;
            $log->cheque_image = $targetImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();
        } elseif ($request->type == 3) {
            // Bank To Bank
            BankAccount::find($request->source_account)->decrement('balance', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->transaction_method = 2;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->bank_id = $request->source_bank;
            $log->branch_id = $request->source_branch;
            $log->bank_account_id = $request->source_account;
            $log->cheque_no = $request->source_cheque_no;
            $log->cheque_image = $sourceImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->bank_id = $request->target_bank;
            $log->branch_id = $request->target_branch;
            $log->bank_account_id = $request->target_account;
            $log->cheque_no = $request->target_cheque_no;
            $log->cheque_image = $targetImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();
        }elseif ($request->type == 4){
            // Cheque To Bank
            Cheeque::first()->decrement('amount', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->transaction_method = 1;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->transaction_method = 2;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->bank_id = $request->target_bank;
            $log->branch_id = $request->target_branch;
            $log->bank_account_id = $request->target_account;
            $log->cheque_no = $request->target_cheque_no;
            $log->cheque_image = $targetImage;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();
        }elseif ($request->type == 5){
            // Cheque To Cash
            Cheeque::first()->decrement('amount', $request->amount);
            Cash::first()->increment('amount', $request->amount);
            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 2;
            $log->transaction_method = 2;
            $log->account_head_type_id = 4;
            $log->account_head_sub_type_id = 4;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Balance Transfer';
            $log->transaction_type = 1;
            $log->transaction_method = 1;
            $log->account_head_type_id = 3;
            $log->account_head_sub_type_id = 3;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->balance_transfer_id = $transfer->id;
            $log->save();
        }

            return redirect()->route('balance_transfer.all')->with('message', 'Balance transfer successful.');
    }
    public function balanceTransferEditPost(Request $request, $id) {
        $transfer = BalanceTransfer::where('id', $id)->first();
        $messages = [
            'source_bank.required_if' => 'The source bank field is required.',
            'source_branch.required_if' => 'The source branch field is required.',
            'source_account.required_if' => 'The source account field is required.',
            'target_bank.required_if' => 'The target bank field is required.',
            'target_branch.required_if' => 'The target branch field is required.',
            'target_account.required_if' => 'The target account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:5',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'source_bank' => 'required_if:type,==,1|required_if:type,==,3',
            'source_branch' => 'required_if:type,==,1|required_if:type,==,3',
            'source_account' => 'required_if:type,==,1|required_if:type,==,3',
            'source_cheque_no' => 'nullable|string|max:255',
            'source_cheque_image' => 'nullable|image',
            'target_bank' => 'required_if:type,==,2|required_if:type,==,3',
            'target_branch' => 'required_if:type,==,2required_if:type,==,3',
            'target_account' => 'required_if:type,==,2|required_if:type,==,3',
            'target_cheque_no' => 'nullable|string|max:255',
            'target_cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request, $transfer) {
            $amount = $transfer->amount;
            if ($request->type == 1 || $request->type == 3) {
                $bankAccount = BankAccount::find($request->source_account);

                if ($request->amount - $amount > $bankAccount->balance)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            } else {
                $cash = Cash::first();

                if ($request->amount - $amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sourceImage = null;
        $targetImage = null;
        if ($request->type == 1 || $request->type == 3) {
            $sourceImage = 'img/no_image.png';

            if ($request->source_cheque_image) {
                // Remove old Image
                if ($transfer->source_cheque_image != 'img/no_image.png' && $transfer->source_cheque_image != null) {
                    $file = url('public/' . $transfer->source_cheque_image);
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                // Upload Image
                $file = $request->file('source_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $sourceImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        if ($request->type == 2 || $request->type == 3  || $request->type == 4) {
            $targetImage = 'img/no_image.png';

            if ($request->target_cheque_image) {
                // Remove old Image
                if ($transfer->target_cheque_image != 'img/no_image.png' && $transfer->target_cheque_image != null) {
                    $file = url('public/' . $transfer->target_cheque_image);
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
                // Upload Image
                $file = $request->file('target_cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/balance_transfer_cheque';
                $file->move($destinationPath, $filename);

                $targetImage = 'uploads/balance_transfer_cheque/'.$filename;
            }
        }

        // Update previous balance
        if ($request->type == 1) {
            // Bank To Cash
            BankAccount::find($request->source_account)->increment('balance', $transfer->amount);
            Cash::first()->decrement('amount', $transfer->amount);
        } elseif ($request->type == 2) {
            // Cash To Bank
            Cash::first()->increment('amount', $transfer->amount);
            BankAccount::find($request->target_account)->decrement('balance', $transfer->amount);
        } elseif ($request->type == 3) {
            // Bank To Bank
            BankAccount::find($request->source_account)->increment('balance', $transfer->amount);
            BankAccount::find($request->target_account)->decrement('balance', $transfer->amount);
        }elseif ($request->type == 4){
            Cheeque::first()->increment('amount', $transfer->amount);
            BankAccount::find($request->target_account)->decrement('balance', $transfer->amount);
        }else{
            Cheeque::first()->increment('amount', $transfer->amount);
            Cash::first()->decrement('amount', $transfer->amount);
        }

        $transfer->type = $request->type;
        $transfer->source_bank_id = in_array($request->type, [1, 3]) ? $request->source_bank : null;
        $transfer->source_branch_id = in_array($request->type, [1, 3]) ? $request->source_branch : null;
        $transfer->source_bank_account_id = in_array($request->type, [1, 3]) ? $request->source_account : null;
        $transfer->source_cheque_no = in_array($request->type, [1, 3]) ? $request->source_cheque_no : null;
        $transfer->source_cheque_image = $sourceImage;
        $transfer->target_bank_id = in_array($request->type, [2, 3,4]) ? $request->target_bank : null;
        $transfer->target_branch_id = in_array($request->type, [2, 3,4]) ? $request->target_branch : null;
        $transfer->target_bank_account_id = in_array($request->type, [2, 3,4]) ? $request->target_account : null;
        $transfer->target_cheque_no = in_array($request->type, [2, 3,4]) ? $request->target_cheque_no : null;
        $transfer->target_cheque_image = $targetImage;
        $transfer->amount = $request->amount;
        $transfer->date = $request->date;
        $transfer->note = $request->note;
        $transfer->save();
        $transfer->receipt_no = $transfer->id+1000;
        $transfer->save();

        if ($request->type == 1) {
            // Bank To Cash
            BankAccount::find($request->source_account)->decrement('balance', $request->amount);
            Cash::first()->increment('amount', $request->amount);

            $expense_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>2])->first();
            if ($expense_log) {
                $expense_log->date = $request->date;
                $expense_log->particular = 'Balance Transfer';
                $expense_log->transaction_type = 2;
                $expense_log->transaction_method = 2;
                $expense_log->account_head_type_id = 4;
                $expense_log->account_head_sub_type_id = 4;
                $expense_log->bank_id = $request->source_bank;
                $expense_log->branch_id = $request->source_branch;
                $expense_log->bank_account_id = $request->source_account;
                $expense_log->cheque_no = $request->source_cheque_no;
                $expense_log->cheque_image = $sourceImage;
                $expense_log->amount = $request->amount;
                $expense_log->note = $request->note;
                $expense_log->balance_transfer_id = $transfer->id;
                $expense_log->save();
            }

            $income_log = TransactionLog::where(['balance_transfer_id' => $transfer->id, 'transaction_type' => 1])->first();
            if ($income_log) {
                $income_log->date = $request->date;
                $income_log->particular = 'Balance Transfer';
                $income_log->transaction_type = 1;
                $income_log->transaction_method = 1;
                $income_log->account_head_type_id = 3;
                $income_log->account_head_sub_type_id = 3;
                $income_log->amount = $request->amount;
                $income_log->note = $request->note;
                $income_log->balance_transfer_id = $transfer->id;
                $income_log->save();
            }



        } elseif ($request->type == 2) {
            // Cash To Bank
            Cash::first()->decrement('amount', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $expense_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>2])->first();
            if ($expense_log) {
                $expense_log->date = $request->date;
                $expense_log->particular = 'Balance Transfer';
                $expense_log->transaction_type = 2;
                $expense_log->transaction_method = 1;
                $expense_log->account_head_type_id = 4;
                $expense_log->account_head_sub_type_id = 4;
                $expense_log->amount = $request->amount;
                $expense_log->note = $request->note;
                $expense_log->balance_transfer_id = $transfer->id;
                $expense_log->save();
            }


            $income_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>1])->first();
            if ($income_log) {
                $income_log->date = $request->date;
                $income_log->particular = 'Balance Transfer';
                $income_log->transaction_type = 1;
                $income_log->transaction_method = 2;
                $income_log->account_head_type_id = 3;
                $income_log->account_head_sub_type_id = 3;
                $income_log->bank_id = $request->target_bank;
                $income_log->branch_id = $request->target_branch;
                $income_log->bank_account_id = $request->target_account;
                $income_log->cheque_no = $request->target_cheque_no;
                $income_log->cheque_image = $targetImage;
                $income_log->amount = $request->amount;
                $income_log->note = $request->note;
                $income_log->balance_transfer_id = $transfer->id;
                $income_log->save();
            }

        } elseif ($request->type == 3) {
            // Bank To Bank
            BankAccount::find($request->source_account)->decrement('balance', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $expense_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>2])->first();
            if ($expense_log) {
                $expense_log->date = $request->date;
                $expense_log->particular = 'Balance Transfer';
                $expense_log->transaction_type = 2;
                $expense_log->transaction_method = 2;
                $expense_log->account_head_type_id = 4;
                $expense_log->account_head_sub_type_id = 4;
                $expense_log->bank_id = $request->source_bank;
                $expense_log->branch_id = $request->source_branch;
                $expense_log->bank_account_id = $request->source_account;
                $expense_log->cheque_no = $request->source_cheque_no;
                $expense_log->cheque_image = $sourceImage;
                $expense_log->amount = $request->amount;
                $expense_log->note = $request->note;
                $expense_log->balance_transfer_id = $transfer->id;
                $expense_log->save();
            }


            $income_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>1])->first();
            if ($income_log) {
                $income_log->date = $request->date;
                $income_log->particular = 'Balance Transfer';
                $income_log->transaction_type = 1;
                $income_log->transaction_method = 2;
                $income_log->account_head_type_id = 3;
                $income_log->account_head_sub_type_id = 3;
                $income_log->bank_id = $request->target_bank;
                $income_log->branch_id = $request->target_branch;
                $income_log->bank_account_id = $request->target_account;
                $income_log->cheque_no = $request->target_cheque_no;
                $income_log->cheque_image = $targetImage;
                $income_log->amount = $request->amount;
                $income_log->note = $request->note;
                $income_log->balance_transfer_id = $transfer->id;
                $income_log->save();
            }

        }elseif ($request->type == 4){
            // Cheque To Bank
            Cheeque::first()->decrement('amount', $request->amount);
            BankAccount::find($request->target_account)->increment('balance', $request->amount);

            $expense_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>2])->first();
            if ($expense_log) {
                $expense_log->date = $request->date;
                $expense_log->particular = 'Balance Transfer';
                $expense_log->transaction_type = 2;
                $expense_log->transaction_method = 1;
                $expense_log->account_head_type_id = 4;
                $expense_log->account_head_sub_type_id = 4;
                $expense_log->amount = $request->amount;
                $expense_log->note = $request->note;
                $expense_log->balance_transfer_id = $transfer->id;
                $expense_log->save();
            }


            $income_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>1])->first();
            if ($income_log) {
                $income_log->date = $request->date;
                $income_log->particular = 'Balance Transfer';
                $income_log->transaction_type = 1;
                $income_log->transaction_method = 2;
                $income_log->account_head_type_id = 3;
                $income_log->account_head_sub_type_id = 3;
                $income_log->bank_id = $request->target_bank;
                $income_log->branch_id = $request->target_branch;
                $income_log->bank_account_id = $request->target_account;
                $income_log->cheque_no = $request->target_cheque_no;
                $income_log->cheque_image = $targetImage;
                $income_log->amount = $request->amount;
                $income_log->note = $request->note;
                $income_log->balance_transfer_id = $transfer->id;
                $income_log->save();
            }
        }else{
            // Cheque To Cash
            Cheeque::first()->decrement('balance', $request->amount);
            Cash::first()->increment('amount', $request->amount);

            $expense_log = TransactionLog::where(['balance_transfer_id'=> $transfer->id, 'transaction_type'=>2])->first();
            if ($expense_log) {
                $expense_log->date = $request->date;
                $expense_log->particular = 'Balance Transfer';
                $expense_log->transaction_type = 2;
                $expense_log->transaction_method = 2;
                $expense_log->account_head_type_id = 4;
                $expense_log->account_head_sub_type_id = 4;
                $expense_log->amount = $request->amount;
                $expense_log->note = $request->note;
                $expense_log->balance_transfer_id = $transfer->id;
                $expense_log->save();
            }

            $income_log = TransactionLog::where(['balance_transfer_id' => $transfer->id, 'transaction_type' => 1])->first();
            if ($income_log) {
                $income_log->date = $request->date;
                $income_log->particular = 'Balance Transfer';
                $income_log->transaction_type = 1;
                $income_log->transaction_method = 1;
                $income_log->account_head_type_id = 3;
                $income_log->account_head_sub_type_id = 3;
                $income_log->amount = $request->amount;
                $income_log->note = $request->note;
                $income_log->balance_transfer_id = $transfer->id;
                $income_log->save();
            }
        }

        return redirect()->route('balance_transfer.all')->with('message', 'Balance transfer updated successful.');
    }

    public function balanceTransferDelete(Request $request, $id)
    {
        $transfer = BalanceTransfer::where('id', $id)->first();
        // Update previous balance
        if ($transfer->type == 1) {
            // Bank To Cash
            BankAccount::find($transfer->source_account)->increment('balance', $transfer->amount);
            Cash::first()->decrement('amount', $transfer->amount);
        } elseif ($transfer->type == 2) {
            // Cash To Bank
            Cash::first()->increment('amount', $transfer->amount);
            BankAccount::find($transfer->target_account)->decrement('balance', $transfer->amount);
        } else {
            // Bank To Bank
            BankAccount::find($transfer->source_account)->increment('balance', $transfer->amount);
            BankAccount::find($transfer->target_account)->decrement('balance', $transfer->amount);
        }

        // Delete Transaction log
        TransactionLog::where(['balance_transfer_id' => $transfer->id])->delete();
        // Delete Balance transfer
        if ($transfer->source_cheque_image != 'img/no_image.png' && $transfer->source_cheque_image != null) {
            $file = url('public/'. $transfer->source_cheque_image);
            if (file_exists($file)) {
                unlink($file);
            }
        }
        if ($transfer->target_cheque_image != 'img/no_image.png' && $transfer->target_cheque_image != null) {
            $file = url('public/'. $transfer->target_cheque_image);
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $transfer->delete();

        return redirect()->route('balance_transfer.all')->with('message', 'Balance transfer deleted successful.');

    }

    public function transactionDatatable() {
        $query = Transaction::with('accountHeadType', 'accountHeadSubType')->where('project_id',null);

        return DataTables::eloquent($query)
            ->addColumn('accountHeadType', function(Transaction $transaction) {
                return $transaction->accountHeadType->name;
            })
            ->addColumn('accountHeadSubType', function(Transaction $transaction) {
                return $transaction->accountHeadSubType->name;
            })
            ->addColumn('action', function(Transaction $transaction) {
//                return '<a href="'.route('transaction.details', ['transaction' => $transaction->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn = '<a target="_blank" href="'.route('transaction.details', ['transaction' => $transaction->id]).'" class="btn btn-primary btn-sm"> Receipt </a>';
                $btn .= '<a target="_blank" href="'.route('transaction.money_receipt.details', ['transaction' => $transaction->id]).'" class="btn btn-success btn-sm">Money Receipt </a>';
                $btn .= '<a target="_blank" href="'.route('transaction.edit',$transaction->id).'" class="btn btn-info btn-sm"> Edit </a>';
                $confirm = "return confirm('Do you want to delete')";
                $btn .= '<a href="'.route('transaction.delete',$transaction->id).'" class="btn btn-danger btn-sm" onclick="'.$confirm.'"> Delete </a>';
                return $btn;
            })
            ->editColumn('date', function(Transaction $transaction) {
                return $transaction->date->format('j F, Y');
            })
            ->editColumn('transaction_type', function(Transaction $transaction) {
                if ($transaction->transaction_type == 1)
                    return '<span class="label label-success">Income</span>';
                else
                    return '<span class="label label-warning">Expense</span>';
            })
            ->editColumn('amount', function(Transaction $transaction) {
                return ''.number_format($transaction->amount, 2);
            })
            ->editColumn('location', function(Transaction $transaction) {
                if ($transaction->location == 1)
                    return 'Dhaka Office';
                elseif ($transaction->location == 2)
                    return 'Factory';
                else
                    return '';
            })
            ->orderColumn('date', function ($query, $transaction) {
                $query->orderBy('date', $transaction)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action', 'transaction_type'])
            ->toJson();
    }

    public function transactionProjectwise(){
        return view('accounts.transaction.project_wise');
    }
    public function transactionProjectwiseAdd(){
        $banks = Bank::where('status', 1)
            ->orderBy('name')
            ->get();
        $projects = Project::where('status',1)->get();
        return view('accounts.transaction.project_wise_add', compact('banks','projects'));
    }
    public function projectWiseDatatable(){
        $query = Transaction::with('accountHeadType', 'accountHeadSubType','project')->where('project_id','!=',null);

        return DataTables::eloquent($query)
            ->addColumn('project', function(Transaction $transaction) {
                return $transaction->project->name;
            })
            ->addColumn('accountHeadType', function(Transaction $transaction) {
                return $transaction->accountHeadType->name;
            })
            ->addColumn('accountHeadSubType', function(Transaction $transaction) {
                return $transaction->accountHeadSubType->name;
            })
            ->addColumn('action', function(Transaction $transaction) {
                //     return '<a href="'.route('transaction.details', ['transaction' => $transaction->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn = '<a target="_blank" href="' . route('transaction.print', ['transaction' => $transaction->id]) . '" class="btn btn-primary btn-sm"> Receipt </a>';
                $btn .= '<a target="_blank" href="' . route('transaction.money_receipt.print', ['transaction' => $transaction->id]) . '" class="btn btn-success btn-sm">Money Receipt </a>';
                $btn .= '<a target="_blank" href="' . route('transaction.project_wise.edit', $transaction->id) . '" class="btn btn-info btn-sm"> Edit </a>';
                $confirm = "return confirm('Do you want to delete')";
                $btn .= '<a href="' . route('transaction.delete', $transaction->id) . '" class="btn btn-danger btn-sm" onclick="' . $confirm . '"> Delete </a>';
                return $btn;
            })
            ->editColumn('date', function(Transaction $transaction) {
                return $transaction->date->format('j F, Y');
            })
            ->editColumn('transaction_type', function(Transaction $transaction) {
                if ($transaction->transaction_type == 1)
                    return '<span class="label label-success">Income</span>';
                else
                    return '<span class="label label-warning">Expense</span>';
            })
            ->editColumn('amount', function(Transaction $transaction) {
                return ''.number_format($transaction->amount, 2);
            })

            ->orderColumn('date', function ($query, $transaction) {
                $query->orderBy('date', $transaction)->orderBy('created_at', 'desc');
            })
            ->rawColumns(['action', 'transaction_type'])
            ->toJson();
    }
    public function transactionProjectwiseAddPost(Request $request) {
        $messages = [
            'location.required_if' => 'The location field is required.',
            'bank.required_if' => 'The bank field is required.',
            'branch.required_if' => 'The branch field is required.',
            'account.required_if' => 'The account field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|min:1|max:2',
            'project' => 'required',
            'account_head_type' => 'required',
            'account_head_sub_type' => 'required',
            'payment_type' => 'required|integer|min:1|max:2',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'bank' => 'required_if:payment_type,==,2',
            'branch' => 'required_if:payment_type,==,2',
            'account' => 'required_if:payment_type,==,2',
            'cheque_no' => 'nullable|string|max:255',
            'cheque_date' => 'nullable|date',
            'cheque_image' => 'nullable|image',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validator->after(function ($validator) use ($request) {
            if ($request->type == 2) {
                if ($request->payment_type == 1) {
                    $cash = Cash::first();

                    if ($request->amount > $cash->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                } elseif ($request->payment_type == 3) {
                    $mobile_banking = MobileBanking::first();

                    if ($request->amount > $mobile_banking->amount)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
                else {
                    $bankAccount = BankAccount::find($request->account);

                    if ($request->amount > $bankAccount->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }

            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $image = null;
        if ($request->payment_type == 2) {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/transaction_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/transaction_cheque/'.$filename;
            }
        }
        $transaction = new Transaction();
        $transaction->transaction_type = $request->type;
        $transaction->project_id = $request->project;
        $transaction->receipt_no =  random_int(1000000,9999999);
        $transaction->account_head_type_id = $request->account_head_type;
        $transaction->account_head_sub_type_id = $request->account_head_sub_type;
        $transaction->transaction_method = $request->payment_type;
        $transaction->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $transaction->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $transaction->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $transaction->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $transaction->cheque_image = $image;
        $transaction->cheque_date = $request->cheque_date;
        $transaction->amount = $request->amount;
        $transaction->date = $request->date;
        $transaction->note = $request->note;
        $transaction->save();

        if ($request->type == 1) {
            // Income
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->increment('amount', $request->amount);
            }elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->increment('amount', $request->amount);
            }

            else {
                // Bank
                BankAccount::find($request->account)->increment('balance', $request->amount);
            }
        } else {
            // Expense
            if ($request->payment_type == 1) {
                // Cash
                Cash::first()->decrement('amount', $request->amount);
            }elseif ($request->payment_type == 3) {
                // MobileBanking
                MobileBanking::first()->decrement('amount', $request->amount);
            }
            else {
                // Bank
                BankAccount::find($request->account)->decrement('balance', $request->amount);
            }
        }

        $accountHeadSubType = AccountHeadSubType::find($request->account_head_sub_type);

        $log = new TransactionLog();
        $log->date = $request->date;
        $log->particular = $accountHeadSubType->name;
        $log->transaction_type = $request->type;
        $log->project_id = $request->project;
        $log->transaction_method = $request->payment_type;
        $log->account_head_type_id = $request->account_head_type;
        $log->account_head_sub_type_id = $request->account_head_sub_type;
        $log->bank_id = $request->payment_type == 2 ? $request->bank : null;
        $log->branch_id = $request->payment_type == 2 ? $request->branch : null;
        $log->bank_account_id = $request->payment_type == 2 ? $request->account : null;
        $log->cheque_no = $request->payment_type == 2 ? $request->cheque_no : null;
        $log->cheque_image = $image;
        $log->amount = $request->amount;
        $log->note = $request->note;
        $log->transaction_id = $transaction->id;
        $log->save();

        return redirect()->route('transaction.print', ['transaction' => $transaction->id]);
    }

}
