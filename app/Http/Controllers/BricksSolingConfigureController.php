<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\BrickSolingConfigure;
use Illuminate\Http\Request;

class BricksSolingConfigureController extends Controller
{
    public function brickSolingConfigure() {
        $brickSolingConfigures = BrickSolingConfigure::get();
        return view('estimate.brick_soling_configure.all',compact('brickSolingConfigures'));
    }

    public function brickSolingConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        return view('estimate.brick_soling_configure.add',compact('estimateProjects'));
    }

    public function brickSolingConfigureAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'project.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'width.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $counter = 0;

        foreach ($request->project as $key => $reqProject) {

            BrickSolingConfigure::create([
                'estimate_project_id' => $reqProject,
                'length' => $request->length[$counter],
                'width' => $request->width[$counter],
                'height' => $request->height[$counter],
                'unit_price' => $request->unit_price[$counter],
                'total_area' => (($request->length[$counter] * $request->width[$counter]) * $request->height[$counter]),
                'total_price' => ((($request->length[$counter] * $request->width[$counter]) * $request->height[$counter]) * $request->unit_price[$counter]),
            ]);
            $counter++;
        }

        return redirect()->route('bricks_soling_configure');
    }
}
