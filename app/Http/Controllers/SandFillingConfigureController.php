<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\SandFillingConfigure;
use App\Models\SandFilling;
use Illuminate\Http\Request;

class SandFillingConfigureController extends Controller
{
    public function sandFillingConfigure() {
        $sandFillingConfigures = SandFilling::get();
        return view('estimate.sand_filling_configure.all',compact('sandFillingConfigures'));
    }

    public function sandFillingConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        return view('estimate.sand_filling_configure.add',compact('estimateProjects'));
    }

    public function sandFillingConfigureAddPost(Request $request) {

        $request->validate([
            'project.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'width.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $counter = 0;
        $sandFillingConfigure = new SandFilling();
        $sandFillingConfigure->estimate_project_id = $request->project[$counter];
        $sandFillingConfigure->length = $request->length[$counter];
        $sandFillingConfigure->width = $request->width[$counter];
        $sandFillingConfigure->height = $request->height[$counter];
        $sandFillingConfigure->quantity = $request->quantity[$counter];
        $sandFillingConfigure->save();


        $counter = 0;

        foreach ($request->project as $key => $reqProject) {

            SandFillingConfigure::create([
                'sand_filling_id' => $sandFillingConfigure->id,
                'estimate_project_id' => $reqProject,
                'length' => $request->length[$counter],
                'width' => $request->width[$counter],
                'height' => $request->height[$counter],
                'quantity' => $request->quantity[$counter],
                'unit_price' => $request->unit_price[$counter],
                'total_area' => (($request->length[$counter] * $request->width[$counter]) * $request->height[$counter] * $request->quantity[$counter]),
                 'total_price' => ((($request->length[$counter] * $request->width[$counter]) * $request->height[$counter]) * $request->unit_price[$counter] * $request->quantity[$counter]),
            ]);
            $counter++;
        }

        return redirect()->route('sand_filling_configure.details', ['sandFillingConfigure' => $sandFillingConfigure->id]);
    }

    public function sandFillingConfigureDetails(SandFilling $sandFillingConfigure){
        return view('estimate.sand_filling_configure.details',compact('sandFillingConfigure'));
    }
    public function sandFillingConfigurePrint(SandFilling $sandFillingConfigure){
        return view('estimate.sand_filling_configure.print',compact('sandFillingConfigure'));
    }
}
