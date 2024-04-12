<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Models\BillForm;
use App\Models\BillFormProduct;
use App\Models\Conveyance;
use App\Models\ConveyanceDetails;
use Illuminate\Http\Request;

class ConveyanceController extends Controller
{
    public function conveyanceFormAll(){
        $conveyances = Conveyance::get();
        return view('labour.conveyance.all', compact('conveyances'));
    }
    public function conveyanceFormAdd(){
        $projects = Project::where('status',1)->get();
        return view('labour.conveyance.add', compact('projects'));
    }

    public function conveyanceFormAddPost(Request $request){
        // dd($request->all());
        $request->validate([
            'project_id' => 'required',
            'designation' => 'required',
            'month' => 'required',
            'name' => 'required',
        ]);
        $conveyance = new Conveyance();
        $conveyance->project_id = $request->project_id;
        $conveyance->name = $request->name;
        $conveyance->month = $request->month;
        $conveyance->date = $request->date;
        $conveyance->designation = $request->designation;
        $conveyance->total_amount = 0;
        $conveyance->save();

        $counter = 0;
        $totalAmount = 0;

        foreach ($request->product as $key => $reqProduct) {
            ConveyanceDetails::create([
                'conveyance_id' => $conveyance->id,
                'project_id' => $request->project_id,
                'product' => $reqProduct,
                'start_from' => $request->start_from[$counter],
                'end_to' => $request->end_to[$counter],
                'media' => $request->media[$counter],
                'purpose' => $request->purpose[$counter],
                'amount' => $request->amount[$counter],
            ]);
            $totalAmount += $request->amount[$counter];
            $counter++;
        }
        $conveyance->total_amount = $totalAmount;
        $conveyance->save();
        return redirect()->route('conveyance.details', ['conveyance' => $conveyance->id]);
    }

    public function conveyanceDetails(Conveyance $conveyance){
        return view('labour.conveyance.details', compact('conveyance'));
    }
    public function conveyancePrint(Conveyance $conveyance){
        return view('labour.conveyance.print', compact('conveyance'));
    }
}
