<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Models\BillForm;
use App\Models\BillFormProduct;
use Illuminate\Http\Request;

class ConveyanceController extends Controller
{
    public function conveyanceFormAll(){
        $billAdjustment = BillForm::get();
        return view('labour.conveyance.all', compact('billAdjustment'));
    }
    public function conveyanceFormAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.conveyance.add', compact('projects'));
    }

    public function conveyanceFormAddPost(Request $request){
        $request->validate([
            'project_id' => 'required',
            'address' => 'required',
            'month' => 'required',
            'trade' => 'required',
        ]);
        $billForm = new BillForm();
        $billForm->project_id = $request->project_id;
        $billForm->for_the_month = $request->month;
        $billForm->address = $request->address;
        $billForm->bill_no = $request->bill_no;
        $billForm->acc_holder_name = $request->acc_holder_name;
        $billForm->date = $request->date;
        $billForm->trade = $request->trade;
        $billForm->duration = $request->duration;
        $billForm->total_amount = 0;
        $billForm->save();


        $counter = 0;
        $totalAmount = 0;

        foreach ($request->product as $key => $reqProduct) {
            BillFormProduct::create([
                'bill_adjustment_id' => $billForm->id,
                'project_id' => $request->project_id,
                'product' => $reqProduct,
                'amount' => $request->amount[$counter],
            ]);
            $totalAmount += $request->amount[$counter];
            $counter++;
        }
        $billForm->total_amount = $totalAmount;
        $billForm->save();
        return redirect()->route('bill_form.details', ['billForm' => $billForm->id]);
    }

    public function billFormDetails(BillForm $billForm){
        return view('labour.bill_form.details', compact('billForm'));
    }
    public function billFormPrint(BillForm $billForm){
        return view('labour.bill_form.print', compact('billForm'));
    }
}
