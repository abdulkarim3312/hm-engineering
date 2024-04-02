<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Models\BillAdjustment;
use App\Models\BillAdjustmentProduct;
use Illuminate\Http\Request;

class BillAdjustmentController extends Controller
{
    public function billAdjustmentAll(){
        $billAdjustment = BillAdjustment::get();
        // dd($billAdjustment);
        return view('labour.bill_adjustment.all', compact('billAdjustment'));
    }
    public function billAdjustmentAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.bill_adjustment.add', compact('projects'));
    }

    public function billAdjustmentAddPost(Request $request){
        // dd($request->all());
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
}
