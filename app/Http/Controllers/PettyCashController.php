<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Models\PettyCash;
use App\Models\PettyCashAdjustment;
use App\Models\PettyCashAdjustmentProduct;
use App\Models\PettyCashProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PettyCashController extends Controller
{
    public function pettyCashAll(){
        $pettyCash = PettyCash::get();
        return view('labour.petty_cash.all', compact('pettyCash'));
    }
    public function pettyCashAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.petty_cash.add', compact('projects'));
    }

    public function pettyCashAddPost(Request $request){
        $request->validate([
            'estimate_project' => 'required',
            'address' => 'required',
            'month' => 'required',
        ]);
        $pettyCash = new PettyCash();
        $pettyCash->estimate_project = $request->estimate_project;
        $pettyCash->month = $request->month;
        $pettyCash->address = $request->address;
        $pettyCash->acc_holder_name = $request->acc_holder_name;
        $pettyCash->date = $request->date;
        $pettyCash->note = $request->note;
        $pettyCash->save();


        $counter = 0;

        foreach ($request->product as $key => $reqProduct) {
            PettyCashProduct::create([
                'petty_cash_id' => $pettyCash->id,
                'project_id' => $request->estimate_project,
                'product' => $reqProduct,
                'previous_balance' => $request->previous_balance[$counter],
                'budget_amount' =>  $request->budget_amount[$counter],
                'required_amount' => $request->budget_amount[$counter] - $request->previous_balance[$counter],
                'recommended_amount' => $request->budget_amount[$counter] - $request->previous_balance[$counter],
                'total_amount' => 0,
            ]);
            $counter++;
        }
        return redirect()->route('petty_cash.details', ['pettyCash' => $pettyCash->id]);
    }

    public function pettyCashDetails(PettyCash $pettyCash){
        return view('labour.petty_cash.details', compact('pettyCash'));
    }
    public function pettyCashPrint(PettyCash $pettyCash){
        return view('labour.petty_cash.print', compact('pettyCash'));
    }
    public function pettyCashApproval(PettyCash $pettyCash){
        $projects = Project::where('status',1)->get();
        return view('labour.petty_cash.approval', compact('pettyCash','projects'));
    }

    public function pettyCashApprovalPost(PettyCash $pettyCash, Request $request){
        $rules = [
            'previous_balance.*' => 'required',
        ];
        $request->validate($rules);

        $available = true;
        $message = '';
        $first_counter = 0;

         foreach ($pettyCash->pettyCashProduct as $requisitionProduct) {

                $requisitionQuantity = PettyCashProduct::where('id', $requisitionProduct->id)
                    ->first();

                if ($request->previous_balance[$first_counter] > $requisitionQuantity->budget_amount) {
                    $available = false;
                    $message = 'Approved Quantity Not Greater Than Requisition Quantity ' . $requisitionQuantity->quantity;
                    break;
                }

             $first_counter++;
            }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $counter = 0;
        $totalApprovedQuantity = 0;

        foreach ($pettyCash->pettyCashProduct as $requisitionProduct) {

            $requisitionProduct->increment('app_previous_balance', $request->previous_balance[$counter]);
            $requisitionProduct->increment('app_budget_amount', $request->budget_amount[$counter]);
            $requisitionProduct->increment('app_required_amount', $request->budget_amount[$counter] - $request->previous_balance[$counter]);
            $requisitionProduct->increment('app_recommended_amount', $request->budget_amount[$counter] - $request->previous_balance[$counter]);

            if ($requisitionProduct->previous_balance > 0){
                $requisitionProduct->status = 1;
                $requisitionProduct->save();
            }

            $counter++;
        }

        $pettyCash->status = 1;
        $pettyCash->save();

        return redirect()->route('petty_cash.details', ['pettyCash' => $pettyCash->id]);
    }
    public function pettyCashAdjustmentAll(){
        $pettyCash = PettyCashAdjustment::get();
        return view('labour.petty_cash_adjustment.all', compact('pettyCash'));
    }
    public function pettyCashAdjustmentAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.petty_cash_adjustment.add', compact('projects'));
    }

    public function pettyCashAddAdjustmentPost(Request $request){
        $request->validate([
            'estimate_project' => 'required',
            'address' => 'required',
            'month' => 'required',
        ]);
        $pettyCash = new PettyCashAdjustment();
        $pettyCash->estimate_project = $request->estimate_project;
        $pettyCash->month = $request->month;
        $pettyCash->address = $request->address;
        $pettyCash->acc_holder_name = $request->acc_holder_name;
        $pettyCash->date = $request->date;
        $pettyCash->note = $request->note;
        $pettyCash->total_amount = 0;
        $pettyCash->save();


        $counter = 0;
        $totalAmount = 0;
        foreach ($request->product as $key => $reqProduct) {
            PettyCashAdjustmentProduct::create([
                'petty_cash_adjustment_id' => $pettyCash->id,
                'project_id' => $request->estimate_project,
                'product' => $reqProduct,
                'receive_amount' => $request->receive_amount[$counter],
                'expense_amount' =>  $request->expense_amount[$counter],
                'balance_amount' => $request->receive_amount[$counter] - $request->expense_amount[$counter],
            ]);
            $totalAmount += $request->receive_amount[$counter] - $request->expense_amount[$counter];
            $counter++;
        }
        $pettyCash->total_amount = $totalAmount;
        $pettyCash->save();
        return redirect()->route('petty_cash_adjustment.details', ['pettyCash' => $pettyCash->id]);
    }

    public function pettyCashAdjustmentDetails(PettyCashAdjustment $pettyCash){
        return view('labour.petty_cash_adjustment.details', compact('pettyCash'));
    }
    public function pettyCashAdjustmentPrint(PettyCashAdjustment $pettyCash){
        return view('labour.petty_cash_adjustment.print', compact('pettyCash'));
    }
    // public function pettyCashApproval(PettyCash $pettyCash){
    //     $projects = Project::where('status',1)->get();
    //     return view('labour.petty_cash_adjustment.approval', compact('pettyCash','projects'));
    // }

    // public function pettyCashApprovalPost(PettyCash $pettyCash, Request $request){
    //     $rules = [
    //         'previous_balance.*' => 'required',
    //     ];
    //     $request->validate($rules);

    //     $available = true;
    //     $message = '';
    //     $first_counter = 0;

    //      foreach ($pettyCash->pettyCashProduct as $requisitionProduct) {

    //             $requisitionQuantity = PettyCashProduct::where('id', $requisitionProduct->id)
    //                 ->first();

    //             if ($request->previous_balance[$first_counter] > $requisitionQuantity->budget_amount) {
    //                 $available = false;
    //                 $message = 'Approved Quantity Not Greater Than Requisition Quantity ' . $requisitionQuantity->quantity;
    //                 break;
    //             }

    //          $first_counter++;
    //         }

    //     if (!$available) {
    //         return redirect()->back()->withInput()->with('message', $message);
    //     }

    //     $counter = 0;
    //     $totalApprovedQuantity = 0;

    //     foreach ($pettyCash->pettyCashProduct as $requisitionProduct) {

    //         $requisitionProduct->increment('app_previous_balance', $request->previous_balance[$counter]);
    //         $requisitionProduct->increment('app_budget_amount', $request->budget_amount[$counter]);
    //         $requisitionProduct->increment('app_required_amount', $request->budget_amount[$counter] - $request->previous_balance[$counter]);
    //         $requisitionProduct->increment('app_recommended_amount', $request->budget_amount[$counter] - $request->previous_balance[$counter]);

    //         if ($requisitionProduct->previous_balance > 0){
    //             $requisitionProduct->status = 1;
    //             $requisitionProduct->save();
    //         }

    //         $counter++;
    //     }

    //     $pettyCash->status = 1;
    //     $pettyCash->save();

    //     return redirect()->route('petty_cash.details', ['pettyCash' => $pettyCash->id]);
    // }
}
