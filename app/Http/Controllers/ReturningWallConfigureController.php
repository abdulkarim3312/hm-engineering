<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\ColumnCofigure;
use App\Models\ReturningWallConfigure;
use App\Models\ReturningWallConfigureProduct;
use App\Models\CostingSegment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReturningWallConfigureController extends Controller
{
    public function returningWallConfigureAll() {
        return view('estimate.returning_wall_configure.all');
    }

    public function returningWallConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $costingSegments = CostingSegment::where('status',1)->get();
        $columnCost = ColumnCofigure::orderBy('id','desc')->first();
        return view('estimate.returning_wall_configure.add',compact('estimateProjects',
            'costingSegments','columnCost'));
    }

    public function returningWallConfigureAddPost(Request $request) {
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


        $commonConfigure = new ReturningWallConfigure();
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

            ReturningWallConfigureProduct::create([
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

            ReturningWallConfigureProduct::create([
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
        // dd($commonConfigure->total_kg);
        $commonConfigure->total_common_bar_price = ($totalKg * $request->costing_segment_quantity) * $request->common_bar_costing;
        $commonConfigure->save();

        return redirect()->route('returning_wall_configure.details', ['commonConfigure' => $commonConfigure->id]);
    }

    public function returningWallConfigureDetails(ReturningWallConfigure $commonConfigure){
        return view('estimate.returning_wall_configure.details',compact('commonConfigure'));
    }
    public function returningWallConfigurePrint(ReturningWallConfigure $commonConfigure){
        return view('estimate.returning_wall_configure.print',compact('commonConfigure'));
    }

    public function returningWallConfigureDatatable() {
        $query = ReturningWallConfigure::with('project','costingSegment');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(ReturningWallConfigure $commonConfigure) {
                return $commonConfigure->project->name??'';
            })
            ->addColumn('segment_name', function(ReturningWallConfigure $commonConfigure) {
                return $commonConfigure->costingSegment->name??'';
            })
            ->addColumn('action', function(ReturningWallConfigure $commonConfigure) {

                return '<a href="'.route('returning_wall_configure.details', ['commonConfigure' => $commonConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(ReturningWallConfigure $commonConfigure) {
                return $commonConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
