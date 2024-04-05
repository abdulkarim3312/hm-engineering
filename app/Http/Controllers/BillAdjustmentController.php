<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Models\BillAdjustment;
use App\Models\BillAdjustmentProduct;
use App\Models\BillForm;
use App\Models\BillFormProduct;
use Illuminate\Http\Request;

class BillAdjustmentController extends Controller
{
    public function billAdjustmentAll(){
        $billAdjustment = BillAdjustment::get();
        return view('labour.bill_adjustment.all', compact('billAdjustment'));
    }
    public function billAdjustmentAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.bill_adjustment.add', compact('projects'));
    }

    public function billAdjustmentAddPost(Request $request){
        $request->validate([
            'project_id' => 'required',
            'address' => 'required',
            'month' => 'required',
            'trade' => 'required',
        ]);
        $billAdjustment = new BillAdjustment();
        $billAdjustment->project_id = $request->project_id;
        $billAdjustment->for_the_month = $request->month;
        $billAdjustment->address = $request->address;
        $billAdjustment->bill_no = $request->bill_no;
        $billAdjustment->acc_holder_name = $request->acc_holder_name;
        $billAdjustment->date = $request->date;
        $billAdjustment->trade = $request->trade;
        $billAdjustment->duration = $request->duration;
        $billAdjustment->total_amount = 0;
        $billAdjustment->save();


        $counter = 0;
        $totalAmount = 0;

        foreach ($request->product as $key => $reqProduct) {
            BillAdjustmentProduct::create([
                'bill_adjustment_id' => $billAdjustment->id,
                'project_id' => $request->project_id,
                'product' => $reqProduct,
                'amount' => $request->amount[$counter],
            ]);
            $totalAmount += $request->amount[$counter];
            $counter++;
        }
        $billAdjustment->total_amount = $totalAmount;
        $billAdjustment->save();
        return redirect()->route('bill_adjustment.details', ['billAdjustment' => $billAdjustment->id]);
    }

    public function billAdjustmentDetails(BillAdjustment $billAdjustment){
        return view('labour.bill_adjustment.details', compact('billAdjustment'));
    }
    public function billAdjustmentPrint(BillAdjustment $billAdjustment){
        return view('labour.bill_adjustment.print', compact('billAdjustment'));
    }

    public function billFormAll(){
        $billAdjustment = BillForm::get();
        return view('labour.bill_form.all', compact('billAdjustment'));
    }
    public function billFormAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.bill_form.add', compact('projects'));
    }

    public function billFormAddPost(Request $request){
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
