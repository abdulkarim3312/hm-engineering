<?php

namespace App\Http\Controllers;

use App\Models\PaymentCheck;
use App\Models\ReceiveCheck;
use Illuminate\Http\Request;

class BankCheckController extends Controller
{
    public function index() {
        $paymentCheque = PaymentCheck::all();
        return view('accounts.payment_check.all', compact('paymentCheque'));
    }

    public function add() {
        return view('accounts.payment_check.add');
    }

    public function addPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'check_no' => 'required|string|max:255',
            'check_amount' => 'required|string|max:255',
            'submitted_date' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $paymentCheque = new PaymentCheck();
        $paymentCheque->customer_name = $request->customer_name;
        $paymentCheque->bank_name = $request->bank_name;
        $paymentCheque->check_no = $request->check_no;
        $paymentCheque->check_amount = $request->check_amount;
        $paymentCheque->check_date = $request->check_date;
        $paymentCheque->submitted_date = $request->submitted_date;
        $paymentCheque->status = $request->status;
        $paymentCheque->save();

        return redirect()->route('payment_check')->with('message', 'Payment Cheque add successfully.');
    }

    public function edit(PaymentCheck $cheque) {
        return view('accounts.payment_check.edit', compact('cheque'));
    }

    public function editPost(Request $request, $cheque) {
        // dd($request->all());
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'check_no' => 'required|string|max:255',
            'check_amount' => 'required|string|max:255',
            'submitted_date' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $paymentCheque = PaymentCheck::find($cheque);
        $paymentCheque->customer_name = $request->customer_name;
        $paymentCheque->bank_name = $request->bank_name;
        $paymentCheque->check_no = $request->check_no;
        $paymentCheque->check_amount = $request->check_amount;
        $paymentCheque->check_date = $request->check_date;
        $paymentCheque->submitted_date = $request->submitted_date;
        $paymentCheque->status = $request->status;
        $paymentCheque->save();

        return redirect()->route('payment_check')->with('message', 'Payment Cheque edit successfully.');
    }
    public function receiveIndex() {
        $paymentCheque = ReceiveCheck::all();
        return view('accounts.receive_cheque.all', compact('paymentCheque'));
    }

    public function receiveAdd() {
        return view('accounts.receive_cheque.add');
    }

    public function receiveAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'check_no' => 'required|string|max:255',
            'check_amount' => 'required|string|max:255',
            'submitted_date' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $paymentCheque = new ReceiveCheck();
        $paymentCheque->customer_name = $request->customer_name;
        $paymentCheque->bank_name = $request->bank_name;
        $paymentCheque->check_no = $request->check_no;
        $paymentCheque->check_amount = $request->check_amount;
        $paymentCheque->check_date = $request->check_date;
        $paymentCheque->submitted_date = $request->submitted_date;
        $paymentCheque->status = $request->status;
        $paymentCheque->save();

        return redirect()->route('receive_cheque')->with('message', 'Receive Cheque added successfully.');
    }

    public function receiveEdit(ReceiveCheck $cheque) {
        return view('accounts.receive_cheque.edit', compact('cheque'));
    }

    public function receiveEditPost(Request $request, $cheque) {
        // dd($request->all());
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'check_no' => 'required|string|max:255',
            'check_amount' => 'required|string|max:255',
            'submitted_date' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $paymentCheque = ReceiveCheck::find($cheque);
        $paymentCheque->customer_name = $request->customer_name;
        $paymentCheque->bank_name = $request->bank_name;
        $paymentCheque->check_no = $request->check_no;
        $paymentCheque->check_amount = $request->check_amount;
        $paymentCheque->check_date = $request->check_date;
        $paymentCheque->submitted_date = $request->submitted_date;
        $paymentCheque->status = $request->status;
        $paymentCheque->save();

        return redirect()->route('receive_cheque')->with('message', 'Receive Cheque edit successfully.');
    }
}
