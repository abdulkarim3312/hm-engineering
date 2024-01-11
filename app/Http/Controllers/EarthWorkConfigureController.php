<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\EarthWorkConfigure;
use Illuminate\Http\Request;

class EarthWorkConfigureController extends Controller
{
    public function earthWorkConfigure() {
        $earthWorkConfigures = EarthWorkConfigure::get();
        return view('estimate.earth_work_configure.all',compact('earthWorkConfigures'));
    }

    public function earthWorkConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        return view('estimate.earth_work_configure.add',compact('estimateProjects'));
    }

    public function earthWorkConfigureAddPost(Request $request) {

        $request->validate([
            'project.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'width.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $counter = 0;

        foreach ($request->project as $key => $reqProject) {

            EarthWorkConfigure::create([
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

        return redirect()->route('earth_work_configure');
    }
}
