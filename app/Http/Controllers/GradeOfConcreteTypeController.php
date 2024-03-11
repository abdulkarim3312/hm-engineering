<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\BeamConfogureProduct;
use App\Models\BeamType;
use App\Models\GradeBeamConfigure;
use App\Models\GradeBeamConfigureProduct;
use App\Models\GradeBeamType;
use App\Models\GradeOfConcreteType;
use App\Models\PileConfigure;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GradeOfConcreteTypeController extends Controller
{
    public function gradeOfConcreteType(){
        $gradeOfConcretes = GradeOfConcreteType::get();
        return view('estimate.grade_of_concrete_type.all', compact('gradeOfConcretes'));
    }

    public function gradeOfConcreteTypeAdd(){
        return view('estimate.grade_of_concrete_type.add');
    }

    public function gradeOfConcreteTypeAddPost(Request $request){
        $request->validate([
            'name' => 'required',
            'first_ratio' => 'required|numeric|min:1',
            'second_ratio' => 'required|numeric|min:1',
            'third_ratio' => 'required|numeric|min:1',
            'description' => 'nullable',
            'status' => 'required',
        ]);

        $gradeOfConcrete = new GradeOfConcreteType();
        $gradeOfConcrete->name = $request->name;
        $gradeOfConcrete->first_ratio = $request->first_ratio;
        $gradeOfConcrete->second_ratio = $request->second_ratio;
        $gradeOfConcrete->third_ratio = $request->third_ratio;
        $gradeOfConcrete->description = $request->description;
        $gradeOfConcrete->status = $request->status;
        $gradeOfConcrete->save();

        return redirect()->route('grade_of_concrete_type')->with('message', 'Grade Concrete Type add successfully.');
    }

    public function gradeOfConcreteTypeEdit(GradeOfConcreteType $gradeOfConcrete) {

        return view('estimate.grade_of_concrete_type.edit', compact( 'gradeOfConcrete'));
    }

    public function gradeOfConcreteTypeEditPost(GradeOfConcreteType $gradeOfConcrete, Request $request){

        $request->validate([
            'name' => 'required',
            'first_ratio' => 'required|numeric|min:1',
            'second_ratio' => 'required|numeric|min:1',
            'third_ratio' => 'required|numeric|min:1',
            'description' => 'nullable',
            'status' => 'required',
        ]);

        $gradeOfConcrete->name = $request->name;
        $gradeOfConcrete->first_ratio = $request->first_ratio;
        $gradeOfConcrete->second_ratio = $request->second_ratio;
        $gradeOfConcrete->third_ratio = $request->third_ratio;
        $gradeOfConcrete->description = $request->description;
        $gradeOfConcrete->status = $request->status;
        $gradeOfConcrete->save();

        return redirect()->route('grade_of_concrete_type')->with('message', 'Grade Concrete Type edit successfully.');
    }

    public function gradeBeanTypeConfigure(){
        return view('estimate.grade_beam_configure.all');
    }

    public function gradeBeanTypeConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $grateBeamTypes = GradeBeamType::where('status',1)->get();
        $pileCost = PileConfigure::orderBy('id','desc')->first();
        return view('estimate.grade_beam_configure.add',compact('estimateProjects',
            'grateBeamTypes','pileCost'));
    }

    public function gradeBeamConfigureAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'beam_type' => 'required',
            'course_aggregate_type' => 'required',
            'tie_bar' => 'required',
            'tie_interval' => 'required',
            'beam_quantity' => 'required',
            'first_ratio' => 'required',
            'second_ratio' => 'required',
            'third_ratio' => 'required',
            'beam_length' => 'required',
            'tie_length' => 'required',
            'tie_width' => 'required',
            'cover' => 'required',
            'total_volume' => 'required',
            'dry_volume' => 'required',
            'total_dry_volume' => 'required',
            'date' => 'required',
            'note' => 'required',
            'beam_bar_costing' => 'required|numeric|min:0',
            'beam_cement_costing' => 'required|numeric|min:0',
            'beam_sands_costing' => 'required|numeric|min:0',
            'beam_aggregate_costing' => 'required|numeric|min:0',
            'beam_picked_costing' => 'required|numeric|min:0',

            'product.*' => 'required',
            'dia.*' => 'required|numeric|min:0',
            'dia_square.*' => 'required|numeric|min:0',
            'value_of_bar.*' => 'required|numeric|min:0',
            'kg_by_rft.*' => 'required|numeric|min:0',
            'kg_by_ton.*' => 'required|numeric|min:0',
            'number_of_bar.*' => 'required|numeric|min:1',
            'lapping_length.*' => 'required|numeric|min:0',
            'lapping_nos.*' => 'required|numeric|min:0',

            'extra_product.*' => 'required',
            'extra_dia.*' => 'required|numeric|min:0',
            'extra_dia_square.*' => 'required|numeric|min:0',
            'extra_value_of_bar.*' => 'required|numeric|min:0',
            'extra_kg_by_rft.*' => 'required|numeric|min:0',
            'extra_kg_by_ton.*' => 'required|numeric|min:0',
            'extra_number_of_bar.*' => 'required|numeric|min:0',
            'extra_length.*' => 'required|numeric|min:0',
        ]);

        $totalRatio = ($request->first_ratio + $request->second_ratio + $request->third_ratio);

        $totalCement = ($request->total_dry_volume * $request->first_ratio/$totalRatio);
        $totalCementBag = ($totalCement/1.25);
        $totalSands = ($request->total_dry_volume * $request->second_ratio/$totalRatio);
        $totalAggregate = ($request->total_dry_volume * $request->third_ratio/$totalRatio);

        if ($request->course_aggregate_type == 2){
            $request->beam_aggregate_costing = 0;
            $totalPiked = ($totalAggregate * 11.11);
        }else{
            $request->beam_picked_costing = 0;
            $totalPiked = 0;
        }

        $counter = 0;
        $gradeBeamConfigure = new GradeBeamConfigure();
        $gradeBeamConfigure->estimate_project_id = $request->estimate_project;
        $gradeBeamConfigure->estimate_floor_id = $request->estimate_floor;
        $gradeBeamConfigure->beam_type_id = $request->beam_type;
        $gradeBeamConfigure->course_aggregate_type = $request->course_aggregate_type;
        $gradeBeamConfigure->tie_bar = $request->tie_bar * $request->beam_quantity;
        $gradeBeamConfigure->tie_interval = $request->tie_interval * $request->beam_quantity;
        $gradeBeamConfigure->beam_quantity = $request->beam_quantity;
        $gradeBeamConfigure->first_ratio = $request->first_ratio;
        $gradeBeamConfigure->second_ratio = $request->second_ratio;
        $gradeBeamConfigure->third_ratio = $request->third_ratio;
        $gradeBeamConfigure->beam_length = $request->beam_length * $request->beam_quantity;
        $gradeBeamConfigure->grade_beam_length = $request->grade_beam_length * $request->beam_quantity;
        $gradeBeamConfigure->grade_beam_width = $request->grade_beam_width * $request->beam_quantity;
        $gradeBeamConfigure->total_volume = $request->total_volume * $request->beam_quantity;
        $gradeBeamConfigure->dry_volume = $request->dry_volume * $request->beam_quantity;
        $gradeBeamConfigure->total_dry_volume = $request->total_dry_volume * $request->beam_quantity;
        $gradeBeamConfigure->cover = $request->cover * $request->beam_quantity;
        $gradeBeamConfigure->date = $request->date;
        $gradeBeamConfigure->note = $request->note;
        $gradeBeamConfigure->total_ton = 0;
        $gradeBeamConfigure->total_kg = 0;
        if($request->course_aggregate_type == 1){
            $gradeBeamConfigure->total_cement = $totalCement * $request->beam_quantity;
            $gradeBeamConfigure->total_cement_bag = $totalCementBag * $request->beam_quantity;
            $gradeBeamConfigure->total_sands = (($totalSands)/2 * $request->beam_quantity);
            $gradeBeamConfigure->total_s_sands =(($totalSands)/2 * $request->beam_quantity);
            $gradeBeamConfigure->total_aggregate = $totalAggregate * $request->beam_quantity;
            $gradeBeamConfigure->total_picked = 0;
        }else if($request->course_aggregate_type == 2){
            $gradeBeamConfigure->total_cement = $totalCement * $request->beam_quantity;
            $gradeBeamConfigure->total_cement_bag = $totalCementBag * $request->beam_quantity;
            $gradeBeamConfigure->total_sands = (($totalSands)/2 * $request->beam_quantity);
            $gradeBeamConfigure->total_s_sands =(($totalSands)/2 * $request->beam_quantity);
            $gradeBeamConfigure->total_aggregate = 0;
            $gradeBeamConfigure->total_picked = $totalPiked * $request->beam_quantity;
        }else{
            $gradeBeamConfigure->total_cement = 0;
            $gradeBeamConfigure->total_cement_bag = 0;
            $gradeBeamConfigure->total_sands = 0;
            $gradeBeamConfigure->total_s_sands = 0;
            $gradeBeamConfigure->total_aggregate = 0;
            $gradeBeamConfigure->total_picked = 0;
        }
        //price
        $gradeBeamConfigure->beam_bar_per_cost = $request->beam_bar_costing;
        $gradeBeamConfigure->beam_cement_per_cost = $request->beam_cement_costing;
        $gradeBeamConfigure->beam_sands_per_cost = $request->beam_sands_costing;
        $gradeBeamConfigure->s_sands_costing = $request->s_sands_costing;
        $gradeBeamConfigure->beam_aggregate_per_cost = $request->beam_aggregate_costing ?? 0;
        $gradeBeamConfigure->beam_picked_per_cost = $request->beam_picked_costing ?? 0;
        //Total Price
        if($request->course_aggregate_type == 1){
            $gradeBeamConfigure->total_beam_cement_bag_price = ($totalCementBag * $request->beam_quantity) * $request->beam_cement_costing;
            $gradeBeamConfigure->total_beam_sands_price = (($totalSands/2) * $request->beam_quantity) * $request->beam_sands_costing;
            $gradeBeamConfigure->total_beam_s_sands_price = (($totalSands/2) * $request->beam_quantity) * $request->s_sands_costing;
            $gradeBeamConfigure->total_beam_aggregate_price = ($totalAggregate * $request->beam_quantity) * $request->beam_aggregate_costing;
            $gradeBeamConfigure->total_beam_picked_price = 0;
            $gradeBeamConfigure->total_grade_beam_rmc_price = 0;
        }else if($request->course_aggregate_type == 2){
            $gradeBeamConfigure->total_beam_cement_bag_price = ($totalCementBag * $request->beam_quantity) * $request->beam_cement_costing;
            $gradeBeamConfigure->total_beam_sands_price = (($totalSands/2) * $request->beam_quantity) * $request->beam_sands_costing;
            $gradeBeamConfigure->total_beam_s_sands_price = (($totalSands/2) * $request->beam_quantity) * $request->s_sands_costing;
            $gradeBeamConfigure->total_beam_aggregate_price = 0;
            $gradeBeamConfigure->total_beam_picked_price = ($totalPiked * $request->beam_quantity) * $request->beam_picked_costing;
            $gradeBeamConfigure->total_grade_beam_rmc_price = 0;
        }else{
            $gradeBeamConfigure->total_grade_beam_rmc_price = $request->total_volume * $request->rmc_costing;
            $gradeBeamConfigure->total_beam_cement_bag_price = 0;
            $gradeBeamConfigure->total_beam_sands_price = 0;
            $gradeBeamConfigure->total_beam_s_sands_price = 0;
            $gradeBeamConfigure->total_beam_aggregate_price = 0;
            $gradeBeamConfigure->total_beam_picked_price = 0;
        }
        $gradeBeamConfigure->total_beam_bar_price = 0;
        $gradeBeamConfigure->save();
        $gradeBeamConfigure->beam_configure_no = str_pad($gradeBeamConfigure->id, 4, "0", STR_PAD_LEFT);
        $gradeBeamConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            if ($key == 0){
                $lapping = (($request->lapping_length[$counter]??0 * $request->lapping_nos[$counter]??0) * $request->kg_by_rft[$counter]);

                GradeBeamConfigureProduct::create([
                    'beam_configure_id' => $gradeBeamConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'bar_type' => $reqProduct,
                    'dia' => $request->dia[$counter],
                    'dia_square' => $request->dia_square[$counter],
                    'value_of_bar' => $request->value_of_bar[$counter],
                    'kg_by_rft' => $request->kg_by_rft[$counter],
                    'kg_by_ton' => $request->kg_by_ton[$counter],
                    'number_of_bar' => $request->number_of_bar[$counter] * $request->beam_quantity,
                    'lapping_length' => $request->lapping_length[$counter]??0 * $request->beam_quantity,
                    'lapping_nos' => $request->lapping_nos[$counter]??0 * $request->beam_quantity,
                    'sub_total_kg' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]))),
                    'sub_total_ton' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]))) / ($request->kg_by_ton[$counter] * $request->beam_quantity),
                    'status' => 2,
                ]);
            }else{
                $lapping = (($request->lapping_length[$counter]??0 * $request->lapping_nos[$counter]??0) * $request->kg_by_rft[$counter]);

                GradeBeamConfigureProduct::create([
                    'beam_configure_id' => $gradeBeamConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'bar_type' => $reqProduct,
                    'dia' => $request->dia[$counter],
                    'dia_square' => $request->dia_square[$counter],
                    'value_of_bar' => $request->value_of_bar[$counter],
                    'kg_by_rft' => $request->kg_by_rft[$counter],
                    'kg_by_ton' => $request->kg_by_ton[$counter],
                    'number_of_bar' => $request->number_of_bar[$counter] * $request->beam_quantity,
                    'lapping_length' => $request->lapping_length[$counter]??0 * $request->beam_quantity,
                    'lapping_nos' => $request->lapping_nos[$counter]??0 * $request->beam_quantity,
                    'sub_total_kg' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]))),
                    'sub_total_ton' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]))) / ($request->kg_by_ton[$counter] * $request->beam_quantity),
                    'status' => 2,
                ]);
            }
            
            if ($key == 0){
                $totalKg += (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter])));
                $totalTon+= (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]))) / ($request->kg_by_ton[$counter] * $request->beam_quantity);
            }else{
                $totalKg += (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter])));
                $totalTon += (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + (($request->lapping_length[$counter]  * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]))) / ($request->kg_by_ton[$counter] * $request->beam_quantity);
            }
            $counter++;
        }

        $counter = 0;
        $totalExtraTon = 0;
        $totalExtraKg = 0;

        foreach ($request->extra_product as $key => $reqProduct) {

                GradeBeamConfigureProduct::create([
                    'beam_configure_id' => $gradeBeamConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'bar_type' => $reqProduct,
                    'dia' => $request->extra_dia[$counter],
                    'dia_square' => $request->extra_dia_square[$counter],
                    'value_of_bar' => $request->extra_value_of_bar[$counter],
                    'kg_by_rft' => $request->extra_kg_by_rft[$counter],
                    'kg_by_ton' => $request->extra_kg_by_ton[$counter],
                    'number_of_bar' => $request->extra_number_of_bar[$counter] * $request->beam_quantity,
                    'extra_length' => $request->extra_length[$counter]??0 * $request->beam_quantity,
                    'sub_total_kg' => ((($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter]??0) * $request->beam_quantity),
                    'sub_total_ton' => (((($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter])
                            * $request->extra_length[$counter]??0)/$request->extra_kg_by_ton[$counter]) * $request->beam_quantity),
                    'status' => 1,
                ]);

                $totalExtraKg += (($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter] ?? 0);
                $totalExtraTon += ((($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter] ?? 0)/$request->extra_kg_by_ton[$counter]);

            $counter++;
        }


        $counter = 0;
        $totalTonTie = 0;
        $totalKgTie = 0;

        foreach ($request->tie_product as $key => $reqProduct) {

            $length_tie_total = $request->tie_length[$counter] / 12;
            
            $width_tie_total = $request->tie_width[$counter] / 12;
           
            $pre_tie_bar = (($length_tie_total + $width_tie_total) * 2) + 0.42;
            GradeBeamConfigureProduct::create([
                'beam_configure_id' => $gradeBeamConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'tie_bar_type' => $reqProduct,
                'tie_dia' => $request->tie_dia[$counter],
                'tie_dia_square' => $request->tie_dia_square[$counter],
                'tie_value_of_bar' => $request->tie_value_of_bar[$counter],
                'tie_kg_by_rft' => $request->tie_kg_by_rft[$counter],
                'tie_kg_by_ton' => $request->tie_kg_by_ton[$counter],
                'tie_length' => $request->tie_length[$counter] * $request->beam_quantity,
                'tie_width' => $request->tie_width[$counter] * $request->beam_quantity,
                'sub_total_kg_tie' => ((($pre_tie_bar * $request->tie_kg_by_rft[$counter]) * $request->ring_quantity) * $request->beam_quantity),
                'sub_total_ton_tie' => (((($pre_tie_bar * $request->tie_kg_by_rft[$counter])
                        * $request->ring_quantity)/$request->tie_kg_by_ton[$counter]) * $request->beam_quantity),
            ]);

            $totalKgTie += (($pre_tie_bar * $request->tie_kg_by_rft[$counter]) * $request->ring_quantity);
            $totalTonTie += ((($pre_tie_bar * $request->tie_kg_by_rft[$counter]) * $request->ring_quantity)/$request->tie_kg_by_ton[$counter]);

            $counter++;
        }

        $gradeBeamConfigure->total_ton = ($totalTon + $totalExtraTon + $totalTonTie) * $request->beam_quantity;
        $gradeBeamConfigure->total_kg = ($totalKg + $totalExtraKg +  $totalKgTie) * $request->beam_quantity;
        $gradeBeamConfigure->total_beam_bar_price = (($totalKg + $totalExtraKg + $totalKgTie) * $request->beam_bar_costing) * $request->beam_quantity;
        $gradeBeamConfigure->save();

        return redirect()->route('grade_beam_configure.details', ['gradeBeamConfigure' => $gradeBeamConfigure->id]);
    }

    public function gradeBeamConfigureDatatable() {
        $query = GradeBeamConfigure::with('project', 'estimateFloor', 'gradeBeamType');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(GradeBeamConfigure $gradeBeamConfigure) {
                return $gradeBeamConfigure->project->name??'';
            })
            ->addColumn('estimate_floor_name', function(GradeBeamConfigure $gradeBeamConfigure) {
                return $gradeBeamConfigure->estimateFloor->name??'';
            })
            ->addColumn('grade_beam_name', function(GradeBeamConfigure $gradeBeamConfigure) {
                return $gradeBeamConfigure->gradeBeamType->name??'';
            })
            ->addColumn('action', function(GradeBeamConfigure $gradeBeamConfigure) {
                $btn = '';
                $btn = '<a href="'.route('grade_beam_configure.details', ['gradeBeamConfigure' => $gradeBeamConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('grade_beam_configure.delete', ['gradeBeamConfigure' => $gradeBeamConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(GradeBeamConfigure $gradeBeamConfigure) {
                return $gradeBeamConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function gradeBeamConfigureDetails(GradeBeamConfigure $gradeBeamConfigure){
        return view('estimate.grade_beam_configure.details',compact('gradeBeamConfigure'));
    }
    public function gradeBeamConfigurePrint(GradeBeamConfigure $gradeBeamConfigure){
        return view('estimate.grade_beam_configure.print',compact('gradeBeamConfigure'));
    }

    public function gradeBeamConfigureDelete(GradeBeamConfigure $gradeBeamConfigure){
        GradeBeamConfigure::find($gradeBeamConfigure->id)->delete();
        GradeBeamConfigureProduct::where('beam_configure_id', $gradeBeamConfigure->id)->delete();
        return redirect()->route('grade_beam_type_configure')->with('message', 'Grade Beam Info Deleted Successfully.');
    }
}
