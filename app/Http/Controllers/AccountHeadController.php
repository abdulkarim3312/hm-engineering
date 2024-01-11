<?php

namespace App\Http\Controllers;

use App\Model\AccountHeadType;
use App\Models\AccountHead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AccountHeadController extends Controller
{
    public function datatable() {
        $query = AccountHead::with('type');
        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function(AccountHead $accountHead) {
                return '<a href="'.route('account_head.edit',['accountHead'=>$accountHead->id]).'" class="btn btn-success btn-sm btn-edit"><i class="fa fa-edit"></i></a>';
            })
            ->addColumn('type', function(AccountHead $accountHead) {
                return $accountHead->type->name ?? '';
            })
            ->editColumn('opening_balance', function(AccountHead $accountHead) {
                return number_format($accountHead->opening_balance,2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function accountHead() {
        return view('accounts.account_head.all');
    }

    public function accountHeadAdd() {
        $types = AccountHeadType::all();
        return view('accounts.account_head.add',compact('types'));
    }

    public function accountHeadAddPost(Request $request) {

        $rules = [
            'name' => 'required|string|max:255|unique:account_heads',
            'account_code' => 'nullable',
            'type' => 'required|integer',
            'opening_balance' => 'required|numeric|min:0',
        ];

        $request->validate($rules);


        $accountHead = new AccountHead();
        $accountHead->account_code = $request->account_code;
        $accountHead->name = $request->name;
        $accountHead->account_head_type_id = $request->type;
        $accountHead->opening_balance = $request->opening_balance;
        $accountHead->save();

        return redirect()->route('account_head')->with('message', 'Account head add successfully.');
    }

    public function accountHeadEdit(AccountHead $accountHead) {
        $types = AccountHeadType::all();
        return view('accounts.account_head.edit', compact('accountHead','types'));
    }

    public function accountHeadEditPost(AccountHead $accountHead, Request $request) {

        $rules = [
            'name' => [
                'required','string','max:255',
                Rule::unique('account_heads')
                    ->ignore($accountHead)
            ],
            'account_code' =>[
                'nullable','string','max:255',
//                Rule::unique('account_heads')
//                    ->where('account_code',$request->account_code)
//                    ->ignore($accountHead)
            ],
            'opening_balance' => 'required|numeric|min:0',
            'type' => 'required|integer',
        ];


        $request->validate($rules);


        $accountHead->account_code = $request->account_code;
        $accountHead->name = $request->name;
        $accountHead->account_head_type_id = $request->type;
        $accountHead->opening_balance = $request->opening_balance;
        $accountHead->save();

        return redirect()->route('account_head')->with('message', 'Account head edit successfully.');
    }
}
