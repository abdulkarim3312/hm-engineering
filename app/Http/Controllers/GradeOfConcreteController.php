<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\Batch;
use App\Models\GradeOfConcrete;
use App\Models\GradeOfConcreteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GradeOfConcreteController extends Controller
{
    public function gradeOfConcrete(){
        $gradeOfConcretes = GradeOfConcrete::get();
        return view('estimate.grade_of_concrete.all',compact('gradeOfConcretes'));
    }

    public function gradeOfConcretePrint(){
        $gradeOfConcretes = GradeOfConcrete::get();
        return view('estimate.grade_of_concrete.print',compact('gradeOfConcretes'));
    }

    public function gradeOfConcreteAdd(){
        $estimateProjects = EstimateProject::where('status',1)->get();
        $gradeOfConcreteTypes = GradeOfConcreteType::where('status',1)->get();
        $batches = Batch::where('status',1)->get();
        return view('estimate.grade_of_concrete.add',compact('estimateProjects','gradeOfConcreteTypes','batches'));
    }

    public function gradeOfConcreteAddPost(Request $request){
        $request->validate([
            'estimate_project' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'product.*' => 'required',
            'batch.*' => 'required',
            'total_volume.*' => 'required',
            'chemical.*' => 'required',
        ]);

        $counter = 0;

        foreach ($request->product as $key => $reqProduct) {

            $gradeOfConcreteType = DB::table('grade_of_concrete_types')->where('id', $request->product[$counter])->first();

            $totalRatio = ($gradeOfConcreteType->first_ratio + $gradeOfConcreteType->second_ratio + $gradeOfConcreteType->third_ratio);
            $totalCement = (($request->total_volume[$counter] * $gradeOfConcreteType->first_ratio/$totalRatio) * 0.8);
            $totalSands = ($request->total_volume[$counter] * $gradeOfConcreteType->second_ratio/$totalRatio);
            $totalAggregate = ($request->total_volume[$counter] *$gradeOfConcreteType->third_ratio/$totalRatio);

            $gradeOfConcrete = new GradeOfConcrete();
            $gradeOfConcrete->estimate_project_id = $request->estimate_project;
            $gradeOfConcrete->grade_of_concrete_type_id = $request->product[$counter];
            $gradeOfConcrete->batch_id = $request->batch[$counter];
            $gradeOfConcrete->total_volume = $request->total_volume[$counter];
            $gradeOfConcrete->date = $request->date;
            $gradeOfConcrete->total_water = ($totalCement *35);
            $gradeOfConcrete->total_cement = $totalCement;
            $gradeOfConcrete->total_sands = $totalSands;
            $gradeOfConcrete->total_aggregate = $totalAggregate;
            $gradeOfConcrete->chemical = $request->chemical[$counter];
            $gradeOfConcrete->note = $request->note;
            $gradeOfConcrete->save();
            $gradeOfConcrete->grade_of_concrete_no = str_pad($gradeOfConcrete->id, 5, "0", STR_PAD_LEFT);
            $gradeOfConcrete->save();
            $counter++;
        }
        return redirect()->route('grade_of_concrete');
    }
}
