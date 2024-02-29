<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\BeamConfigure;
use App\Models\BeamConfogureProduct;
use App\Models\BeamType;
use App\Models\ColumnCofigure;
use App\Models\CommonConfigure;
use App\Models\CommonConfigureProduct;
use App\Models\CostingSegment;
use App\Models\PileConfigure;
use App\Models\PileConfigureProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CommonConfigureController extends Controller
{
    public function configureAll() {
        return view('estimate.common_configure.all');
    }

    public function commonConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $costingSegments = CostingSegment::where('status',1)->get();
        $columnCost = ColumnCofigure::orderBy('id','desc')->first();
        return view('estimate.common_configure.add',compact('estimateProjects',
            'costingSegments','columnCost'));
    }

    public function commonConfigureAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'estimate_project' => 'required',
            'costing_segment' => 'required',
            'course_aggregate_type' => 'required',
            'costing_segment_quantity' => 'required',
            'segment_length' => 'required|numeric|min:1',
            'segment_width' => 'required|numeric|min:1',
            'segment_thickness' => 'required|numeric|min:.01',
            'dry_volume' => 'required|numeric|min:.01',
            'first_ratio' => 'required',
            'second_ratio' => 'required',
            'third_ratio' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'common_bar_costing' => 'required|numeric|min:0',
            'common_cement_costing' => 'required|numeric|min:0',
            'common_sands_costing' => 'required|numeric|min:0',
            'common_aggregate_costing' => 'required|numeric|min:0',
            'common_picked_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'dia.*' => 'required|numeric|min:0',
            'dia_square.*' => 'required|numeric|min:0',
            'value_of_bar.*' => 'required|numeric|min:0',
            'kg_by_rft.*' => 'required|numeric|min:0',
            'kg_by_ton.*' => 'required|numeric|min:0',
            'length_type.*' => 'required|numeric|min:0',
            'length.*' => 'required|numeric|min:0',
            'spacing.*' => 'required|numeric|min:0',
            'type_length.*' => 'required|numeric|min:0',
            'layer.*' => 'required|numeric|min:0',

            'extra_product.*' => 'required',
            'extra_dia.*' => 'required|numeric|min:0',
            'extra_dia_square.*' => 'required|numeric|min:0',
            'extra_value_of_bar.*' => 'required|numeric|min:0',
            'extra_kg_by_rft.*' => 'required|numeric|min:0',
            'extra_kg_by_ton.*' => 'required|numeric|min:0',
            'extra_number_of_bar.*' => 'required|numeric|min:0',
            'extra_length.*' => 'required|numeric|min:0',
        ]);

        $total_dry_volume = (($request->segment_length * $request->segment_width) * $request->segment_thickness) * 1.5;
        
        $totalRatio = ($request->first_ratio + $request->second_ratio + $request->third_ratio);
        
        $totalCement = ($total_dry_volume * $request->first_ratio/$totalRatio);
        $totalCementBag = ($totalCement/1.25);
       
        $totalSands = ($total_dry_volume * $request->second_ratio/$totalRatio);
        
        $totalAggregate = ($total_dry_volume * $request->third_ratio/$totalRatio);
       
        if ($request->course_aggregate_type == 2){
            $totalPiked = ($totalAggregate * 11.11);
        }else{
            $totalPiked = 0;
        }


        $commonConfigure = new CommonConfigure();
        $commonConfigure->estimate_project_id = $request->estimate_project;
        $commonConfigure->costing_segment_id = $request->costing_segment;
        $commonConfigure->costing_segment_quantity = $request->costing_segment_quantity;
        $commonConfigure->course_aggregate_type = $request->course_aggregate_type;
        $commonConfigure->first_ratio = $request->first_ratio;
        $commonConfigure->second_ratio = $request->second_ratio;
        $commonConfigure->third_ratio = $request->third_ratio;
        $commonConfigure->slab_length = $request->segment_length;
        $commonConfigure->slab_width = $request->segment_width;
        $commonConfigure->slab_thickness = $request->segment_thickness;
        $commonConfigure->total_volume = $request->total_volume * $request->costing_segment_quantity;
        $commonConfigure->dry_volume = $request->dry_volume * $request->costing_segment_quantity;
        $commonConfigure->total_dry_volume = $request->total_dry_volume * $request->costing_segment_quantity;
        $commonConfigure->date = $request->date;
        $commonConfigure->note = $request->note;
        $commonConfigure->total_ton = 0;
        $commonConfigure->total_kg = 0;
        $commonConfigure->total_cement = $totalCement * $request->costing_segment_quantity;
        $commonConfigure->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
        if($request->course_aggregate_type == 3){
            $commonConfigure->total_sands = 0;
            $commonConfigure->total_s_sands = 0;
            $commonConfigure->total_aggregate = 0;
        }else if($request->course_aggregate_type == 2){
            $commonConfigure->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $commonConfigure->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $commonConfigure->total_aggregate = 0;
            $commonConfigure->total_picked = $totalPiked * $request->costing_segment_quantity;
        }else{
            $commonConfigure->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $commonConfigure->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $commonConfigure->total_aggregate = $totalAggregate * $request->costing_segment_quantity;
            $commonConfigure->total_picked = $totalPiked * $request->costing_segment_quantity;
        }
        
        //price
        $commonConfigure->common_bar_per_cost = $request->common_bar_costing;
        $commonConfigure->common_cement_per_cost = $request->common_cement_costing;
        $commonConfigure->common_sands_per_cost = $request->common_sands_costing;
        $commonConfigure->s_sands_costing = $request->s_sands_costing;
        //Total Price
        $commonConfigure->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
        if($request->course_aggregate_type == 3){
            $commonConfigure->total_common_sands_price = 0;
            $commonConfigure->total_slab_s_sands_price = 0;
            $commonConfigure->total_slab_rmc_price = $request->total_volume * $request->rmc_costing;
            $commonConfigure->common_aggregate_per_cost = 0;
            $commonConfigure->common_picked_per_cost = $request->common_picked_costing??0;
        }else if($request->course_aggregate_type == 2){
            $commonConfigure->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $commonConfigure->total_slab_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $commonConfigure->total_slab_rmc_price = $request->total_volume * $request->rmc_costing;
            $commonConfigure->common_aggregate_per_cost = 0;
            $commonConfigure->common_picked_per_cost = $request->common_picked_costing ?? 0;
            $commonConfigure->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;
            $commonConfigure->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_aggregate_costing;
        }else{
            $commonConfigure->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $commonConfigure->total_slab_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $commonConfigure->total_slab_rmc_price = $request->total_volume * $request->rmc_costing;
            $commonConfigure->common_aggregate_per_cost = $request->common_aggregate_costing??0;
            $commonConfigure->common_picked_per_cost = $request->common_picked_costing??0;
            $commonConfigure->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;
            $commonConfigure->total_common_picked_price = 0;
        }
        
        
        $commonConfigure->total_common_bar_price = 0;

        $commonConfigure->save();
        $commonConfigure->common_configure_no = str_pad($commonConfigure->id, 5, "0", STR_PAD_LEFT);
        $commonConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            $rft = ($request->length[$counter]/$request->spacing[$counter]) + 1;
            $item = ($request->type_length[$counter] - (($request->clear_cover[$counter] / 12) * 2));
            $data = $rft *  $item;
            $sub_total =  ($request->lapping_lenght[$counter] * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter];
            $total_main_rod = (($data *  $request->kg_by_rft[$counter]) + $sub_total) * $request->layer[$counter];
           
            CommonConfigureProduct::create([
                'common_configure_id' => $commonConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'costing_segment_id' => $request->costing_segment,
                'bar_type' => $reqProduct,
                'dia' => $request->dia[$counter],
                'dia_square' => $request->dia_square[$counter],
                'value_of_bar' => $request->value_of_bar[$counter],
                'kg_by_rft' => $request->kg_by_rft[$counter],
                'kg_by_ton' => $request->kg_by_ton[$counter],
                'length_type' => $request->length_type[$counter] * $request->costing_segment_quantity,
                'length' => $request->length[$counter] * $request->costing_segment_quantity,
                'spacing' => $request->spacing[$counter] * $request->costing_segment_quantity,
                'type_length' => $request->type_length[$counter] * $request->costing_segment_quantity,
                'layer' => $request->layer[$counter] * $request->costing_segment_quantity,
                'sub_total_kg' => $total_main_rod,
                'sub_total_ton' => (($total_main_rod / $request->kg_by_ton[$counter]) * $request->costing_segment_quantity),
            ]);

            $totalKg +=$total_main_rod;
            // dd($totalKg);
            $totalTon += ($total_main_rod / $request->kg_by_ton[$counter]);

            $counter++;
        }

        $counter = 0;

        foreach ($request->extra_product as $key => $reqProduct) {

            CommonConfigureProduct::create([
                'common_configure_id' => $commonConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'costing_segment_id' => $request->costing_segment,
                'bar_type' => $reqProduct,
                'dia' => $request->extra_dia[$counter],
                'dia_square' => $request->extra_dia_square[$counter],
                'value_of_bar' => $request->extra_value_of_bar[$counter],
                'kg_by_rft' => $request->extra_kg_by_rft[$counter],
                'kg_by_ton' => $request->extra_kg_by_ton[$counter],
                'number_of_bar' => $request->extra_number_of_bar[$counter] * $request->costing_segment_quantity,
                'extra_length' => $request->extra_length[$counter]??0 * $request->costing_segment_quantity,
                'sub_total_kg' => ((($request->extra_number_of_bar[$counter] *
                        $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter]??0) * $request->costing_segment_quantity),
                'sub_total_ton' => (((($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter])
                        * $request->extra_length[$counter]??0)/$request->extra_kg_by_ton[$counter]) * $request->costing_segment_quantity),
                'status' => 1,
            ]);

            $totalKg += (($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter]??0);
            $totalTon += ((($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter]??0)/$request->extra_kg_by_ton[$counter]);

            $counter++;
        }


        $commonConfigure->total_ton = $totalTon * $request->costing_segment_quantity;
        $commonConfigure->total_kg = $totalKg * $request->costing_segment_quantity;
        $commonConfigure->total_common_bar_price = ($totalKg * $request->costing_segment_quantity) * $request->common_bar_costing;
        $commonConfigure->save();

        return redirect()->route('common_configure.details', ['commonConfigure' => $commonConfigure->id]);
    }

    public function commonConfigureDetails(CommonConfigure $commonConfigure){
        return view('estimate.common_configure.details',compact('commonConfigure'));
    }
    public function commonConfigurePrint(CommonConfigure $commonConfigure){
        return view('estimate.common_configure.print',compact('commonConfigure'));
    }

    public function commonConfigureDelete(CommonConfigure $commonConfigure){
        CommonConfigure::find($commonConfigure->id)->delete();
        CommonConfigureProduct::where('common_configure_id', $commonConfigure->id)->delete();
        return redirect()->back();
    }
    

    public function commonConfigureDatatable() {
        $query = CommonConfigure::with('project','costingSegment');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(CommonConfigure $commonConfigure) {
                return $commonConfigure->project->name??'';
            })
            ->addColumn('segment_name', function(CommonConfigure $commonConfigure) {
                return $commonConfigure->costingSegment->name??'';
            })
            ->addColumn('action', function(CommonConfigure $commonConfigure) {

                $btn = '';
                $btn = '<a href="'.route('common_configure.details', ['commonConfigure' => $commonConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('common_configure.delete', ['commonConfigure' => $commonConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;

            })
            ->editColumn('date', function(CommonConfigure $commonConfigure) {
                return $commonConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function beamConfigure() {
        return view('estimate.beam_configure.all');
    }

    public function beamConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $beamTypes = BeamType::where('status',1)->get();
        $pileCost = PileConfigure::orderBy('id','desc')->first();
        return view('estimate.beam_configure.add',compact('estimateProjects',
            'beamTypes','pileCost'));
    }

    public function beamConfigureAddPost(Request $request) {
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
        $beamConfigure = new BeamConfigure();
        $beamConfigure->estimate_project_id = $request->estimate_project;
        $beamConfigure->estimate_floor_id = $request->estimate_floor;
        $beamConfigure->beam_type_id = $request->beam_type;
        $beamConfigure->tie_bar = $request->tie_bar * $request->beam_quantity;
        $beamConfigure->tie_interval = $request->tie_interval * $request->beam_quantity;
        $beamConfigure->beam_quantity = $request->beam_quantity;
        $beamConfigure->first_ratio = $request->first_ratio;
        $beamConfigure->second_ratio = $request->second_ratio;
        $beamConfigure->third_ratio = $request->third_ratio;
        $beamConfigure->beam_length = $request->beam_length * $request->beam_quantity;
        $beamConfigure->grade_beam_length = $request->grade_beam_length * $request->beam_quantity;
        $beamConfigure->grade_beam_width = $request->grade_beam_width * $request->beam_quantity;
        $beamConfigure->total_volume = $request->total_volume * $request->beam_quantity;
        $beamConfigure->dry_volume = $request->dry_volume * $request->beam_quantity;
        $beamConfigure->total_dry_volume = $request->total_dry_volume * $request->beam_quantity;
        $beamConfigure->cover = $request->cover * $request->beam_quantity;
        $beamConfigure->date = $request->date;
        $beamConfigure->note = $request->note;
        $beamConfigure->total_ton = 0;
        $beamConfigure->total_kg = 0;
        $beamConfigure->total_cement = $totalCement * $request->beam_quantity;
        $beamConfigure->total_cement_bag = $totalCementBag * $request->beam_quantity;
        $beamConfigure->total_sands = (($totalSands)/2 * $request->beam_quantity);
        $beamConfigure->total_s_sands =(($totalSands)/2 * $request->beam_quantity);
        $beamConfigure->total_aggregate = $totalAggregate * $request->beam_quantity;
        $beamConfigure->total_picked = $totalPiked * $request->beam_quantity;
        //price
        $beamConfigure->beam_bar_per_cost = $request->beam_bar_costing;
        $beamConfigure->beam_cement_per_cost = $request->beam_cement_costing;
        $beamConfigure->beam_sands_per_cost = $request->beam_sands_costing;
        $beamConfigure->s_sands_costing = $request->s_sands_costing;
        $beamConfigure->beam_aggregate_per_cost = $request->beam_aggregate_costing ?? 0;
        $beamConfigure->beam_picked_per_cost = $request->beam_picked_costing ?? 0;
        //Total Price
        $beamConfigure->total_beam_cement_bag_price = ($totalCementBag * $request->beam_quantity) * $request->beam_cement_costing;
        $beamConfigure->total_beam_sands_price = (($totalSands/2) * $request->beam_quantity) * $request->beam_sands_costing;
        $beamConfigure->total_beam_s_sands_price = (($totalSands/2) * $request->beam_quantity) * $request->s_sands_costing;
        $beamConfigure->total_beam_aggregate_price = ($totalAggregate * $request->beam_quantity) * $request->beam_aggregate_costing;
        $beamConfigure->total_beam_picked_price = ($totalPiked * $request->beam_quantity) * $request->beam_picked_costing;
        $beamConfigure->total_beam_bar_price = 0;
        $beamConfigure->save();
        $beamConfigure->beam_configure_no = str_pad($beamConfigure->id, 4, "0", STR_PAD_LEFT);
        $beamConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            if ($key == 0){
                $lapping = (($request->lapping_length[$counter]??0 * $request->lapping_nos[$counter]??0) * $request->kg_by_rft[$counter]);

                BeamConfogureProduct::create([
                    'beam_configure_id' => $beamConfigure->id,
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

                BeamConfogureProduct::create([
                    'beam_configure_id' => $beamConfigure->id,
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

                BeamConfogureProduct::create([
                    'beam_configure_id' => $beamConfigure->id,
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
            BeamConfogureProduct::create([
                'beam_configure_id' => $beamConfigure->id,
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

        $beamConfigure->total_ton = ($totalTon + $totalExtraTon + $totalTonTie) * $request->beam_quantity;
        $beamConfigure->total_kg = ($totalKg + $totalExtraKg +  $totalKgTie) * $request->beam_quantity;
        $beamConfigure->total_beam_bar_price = (($totalKg + $totalExtraKg + $totalKgTie) * $request->beam_bar_costing) * $request->beam_quantity;
        $beamConfigure->save();

        return redirect()->route('beam_configure.details', ['beamConfigure' => $beamConfigure->id]);
    }

    public function beamConfigureDetails(BeamConfigure $beamConfigure){
        return view('estimate.beam_configure.details',compact('beamConfigure'));
    }
    public function beamConfigurePrint(BeamConfigure $beamConfigure){
        return view('estimate.beam_configure.print',compact('beamConfigure'));
    }

    public function beamConfigureDatatable() {
        $query = BeamConfigure::with('project', 'estimateFloor', 'beamType', 'beamConfigureProducts');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(BeamConfigure $beamConfigure) {
                return $beamConfigure->project->name??'';
            })
            ->addColumn('floor_name', function(BeamConfigure $beamConfigure) {
                return $beamConfigure->estimateFloor->name ??'';
            })
            ->addColumn('beam_type', function(BeamConfigure $beamConfigure) {
                return $beamConfigure->beamType->name ??'';
            })
            ->addColumn('total_rod', function(BeamConfigure $beamConfigure) {
                return $beamConfigure->total_kg ??'';
            })
            ->addColumn('action', function(BeamConfigure $beamConfigure) {

                return '<a href="'.route('beam_configure.details', ['beamConfigure' => $beamConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(BeamConfigure $beamConfigure) {
                return $beamConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
