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
        // dd($total_dry_volume);
        $totalRatio = ($request->first_ratio + $request->second_ratio + $request->third_ratio);
        // dd($totalRatio);
        $totalCement = ($total_dry_volume * $request->first_ratio/$totalRatio);
        $totalCementBag = ($totalCement/1.25);
        // dd($totalCementBag);
        $totalSands = ($total_dry_volume * $request->second_ratio/$totalRatio);
        // dd($totalSands);
        $totalAggregate = ($total_dry_volume * $request->third_ratio/$totalRatio);
        // dd($totalAggregate);
        if ($request->course_aggregate_type == 2){
            $totalPiked = ($totalAggregate * 11.11);
        }else{
            $totalPiked = 0;
        }


        $commonConfigure = new CommonConfigure();
        $commonConfigure->estimate_project_id = $request->estimate_project;
        $commonConfigure->costing_segment_id = $request->costing_segment;
        $commonConfigure->costing_segment_quantity = $request->costing_segment_quantity;
        $commonConfigure->first_ratio = $request->first_ratio;
        $commonConfigure->second_ratio = $request->second_ratio;
        $commonConfigure->third_ratio = $request->third_ratio;
        $commonConfigure->date = $request->date;
        $commonConfigure->note = $request->note;
        $commonConfigure->total_ton = 0;
        $commonConfigure->total_kg = 0;
        $commonConfigure->total_cement = $totalCement * $request->costing_segment_quantity;
        $commonConfigure->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
        $commonConfigure->total_sands = $totalSands * $request->costing_segment_quantity;
        $commonConfigure->total_aggregate = $totalAggregate * $request->costing_segment_quantity;
        $commonConfigure->total_picked = $totalPiked * $request->costing_segment_quantity;

        //price
        $commonConfigure->common_bar_per_cost = $request->common_bar_costing;
        $commonConfigure->common_cement_per_cost = $request->common_cement_costing;
        $commonConfigure->common_sands_per_cost = $request->common_sands_costing;
        $commonConfigure->common_aggregate_per_cost = $request->common_aggregate_costing??0;
        $commonConfigure->common_picked_per_cost = $request->common_picked_costing??0;
        //Total Price
        $commonConfigure->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
        // dd($commonConfigure->total_common_cement_bag_price);
        $commonConfigure->total_common_sands_price = ($totalSands * $request->costing_segment_quantity) * $request->common_sands_costing;
        $commonConfigure->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;
        $commonConfigure->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_picked_costing;
        $commonConfigure->total_common_bar_price = 0;

        $commonConfigure->save();
        $commonConfigure->common_configure_no = str_pad($commonConfigure->id, 5, "0", STR_PAD_LEFT);
        $commonConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            $division = $request->length[$counter]/$request->spacing[$counter];

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
                'sub_total_kg' => ((($division * $request->type_length[$counter]) *  $request->layer[$counter]) * $request->kg_by_rft[$counter] * $request->costing_segment_quantity),
                'sub_total_ton' => ((((($division * $request->type_length[$counter]) * $request->layer[$counter])
                        * $request->kg_by_rft[$counter])/$request->kg_by_ton[$counter]) * $request->costing_segment_quantity),
            ]);

            $totalKg += (($division * $request->type_length[$counter]) *  $request->layer[$counter]) * $request->kg_by_rft[$counter];
            // dd($totalKg);
            $totalTon += (((($division * $request->type_length[$counter]) * $request->layer[$counter]) * $request->kg_by_rft[$counter])/$request->kg_by_ton[$counter]);

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

                return '<a href="'.route('common_configure.details', ['commonConfigure' => $commonConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

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
        //dd($beamCost);
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
        $beamConfigure->tie_length = $request->tie_length * $request->beam_quantity;
        $beamConfigure->tie_width = $request->tie_width * $request->beam_quantity;
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
        $beamConfigure->total_sands = $totalSands * $request->beam_quantity;
        $beamConfigure->total_aggregate = $totalAggregate * $request->beam_quantity;
        $beamConfigure->total_picked = $totalPiked * $request->beam_quantity;
        //price
        $beamConfigure->beam_bar_per_cost = $request->beam_bar_costing;
        $beamConfigure->beam_cement_per_cost = $request->beam_cement_costing;
        $beamConfigure->beam_sands_per_cost = $request->beam_sands_costing;
        $beamConfigure->beam_aggregate_per_cost = $request->beam_aggregate_costing??0;
        $beamConfigure->beam_picked_per_cost = $request->beam_picked_costing??0;
        //Total Price
        $beamConfigure->total_beam_cement_bag_price = ($totalCementBag * $request->beam_quantity) * $request->beam_cement_costing;
        $beamConfigure->total_beam_sands_price = ($totalSands * $request->beam_quantity) * $request->beam_sands_costing;
        $beamConfigure->total_beam_aggregate_price = ($totalAggregate * $request->beam_quantity) * $request->beam_aggregate_costing;
        $beamConfigure->total_beam_picked_price = ($totalPiked * $request->beam_quantity) * $request->beam_picked_costing;
        $beamConfigure->total_beam_bar_price = 0;
        $beamConfigure->save();
        $beamConfigure->beam_configure_no = str_pad($beamConfigure->id, 5, "0", STR_PAD_LEFT);
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
                    'sub_total_kg' => ((($request->tie_bar + $lapping ) * $request->kg_by_rft[$counter]) * $request->beam_quantity),
                    'sub_total_ton' => (((($request->tie_bar + $lapping ) * $request->kg_by_rft[$counter])
                        /$request->kg_by_ton[$counter]) * $request->beam_quantity),
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
                    'sub_total_kg' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + $lapping)  * $request->beam_quantity),
                    'sub_total_ton' => ((((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter])
                                * $request->beam_length) + $lapping)/$request->kg_by_ton[$counter]) * $request->beam_quantity),
                ]);
            }

            if ($key == 0){
                $totalKg += (($request->tie_bar + $lapping ) * $request->kg_by_rft[$counter]);
                $totalTon+= ((($request->tie_bar + $lapping ) * $request->kg_by_rft[$counter])/$request->kg_by_ton[$counter]);
            }else{
                $totalKg += ((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + $lapping);
                $totalTon += (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->beam_length) + $lapping)/$request->kg_by_ton[$counter]);
            }
            $counter++;
        }

        $counter = 0;

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

                $totalKg += (($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter]??0);
                $totalTon += ((($request->extra_number_of_bar[$counter] * $request->extra_kg_by_rft[$counter]) * $request->extra_length[$counter]??0)/$request->extra_kg_by_ton[$counter]);

            $counter++;
        }

        $beamConfigure->total_ton = $totalTon * $request->beam_quantity;
        $beamConfigure->total_kg = $totalKg * $request->beam_quantity;
        $beamConfigure->total_beam_bar_price = ($totalKg * $request->beam_quantity) * $request->beam_bar_costing;
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
        $query = BeamConfigure::with('project');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(BeamConfigure $beamConfigure) {
                return $beamConfigure->project->name??'';
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
