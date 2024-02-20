<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\Batch;
use App\Models\BeamConfigure;
use App\Models\BeamType;
use App\Models\ColumnCofigure;
use App\Models\ColumnConfigureProduct;
use App\Models\ColumnType;
use App\Models\CostingSegment;
use App\Models\FootingConfigure;
use App\Models\FootingConfigureProduct;
use App\Models\PileConfigure;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BatchController extends Controller
{
    public function batch()
    {
        $batches = Batch::get();
        return view('estimate.batch.all', compact('batches'));
    }

    public function batchAdd()
    {
        return view('estimate.batch.add');
    }

    public function batchAddPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required',

        ]);

        $batch = new Batch();
        $batch->name = $request->name;
        $batch->status = $request->status;
        $batch->save();

        return redirect()->route('batch')->with('message', 'Batch add successfully.');
    }

    public function batchEdit(Batch $batch) {

        return view('estimate.batch.edit', compact( 'batch'));
    }

    public function batchEditPost(Batch $batch,Request $request){

        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        $batch->name = $request->name;
        $batch->status = $request->status;
        $batch->save();

        return redirect()->route('batch')->with('message', 'Batch edit successfully.');
    }

    public function footing(){
        return view('estimate.footing_configure.all');
    }

    public function footingConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $beamTypes = Batch::where('status',1)->get();
        $pileCost = PileConfigure::orderBy('id','desc')->first();
        $estimateProjects = EstimateProject::where('status',1)->get();
        $costingSegments = CostingSegment::where('status',1)->get();
        $columnCost = ColumnCofigure::orderBy('id','desc')->first();
        return view('estimate.footing_configure.add',compact('estimateProjects',
        'beamTypes','pileCost', 'costingSegments', 'columnCost'));
    }

    public function footingConfigureAddPost(Request $request) {
        //  dd($request->all());
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


        $footingConfigure = new FootingConfigure();
        $footingConfigure->estimate_project_id = $request->estimate_project;
        $footingConfigure->costing_segment_id = $request->costing_segment;
        $footingConfigure->costing_segment_quantity = $request->costing_segment_quantity;
        $footingConfigure->footing_type_id = $request->footing_type_id;
        $footingConfigure->first_ratio = $request->first_ratio;
        $footingConfigure->second_ratio = $request->second_ratio;
        $footingConfigure->third_ratio = $request->third_ratio;
        $footingConfigure->segment_length = $request->segment_length;
        $footingConfigure->segment_width = $request->segment_width;
        $footingConfigure->segment_thickness = $request->segment_thickness;
        $footingConfigure->date = $request->date;
        $footingConfigure->note = $request->note;
        $footingConfigure->total_ton = 0;
        $footingConfigure->total_kg = 0;
        $footingConfigure->total_volume = $request->total_volume * $request->costing_segment_quantity;
        $footingConfigure->dry_volume = $request->dry_volume * $request->costing_segment_quantity;
        $footingConfigure->total_dry_volume = $request->total_dry_volume * $request->costing_segment_quantity;
        $footingConfigure->total_cement = $totalCement * $request->costing_segment_quantity;
        $footingConfigure->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
        $footingConfigure->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
        $footingConfigure->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
        $footingConfigure->total_aggregate = $totalAggregate * $request->costing_segment_quantity;
        $footingConfigure->total_picked = $totalPiked * $request->costing_segment_quantity;

        //price
        $footingConfigure->common_bar_per_cost = $request->common_bar_costing;
        $footingConfigure->common_cement_per_cost = $request->common_cement_costing;
        $footingConfigure->common_sands_per_cost = $request->common_sands_costing;
        $footingConfigure->s_sands_costing = $request->s_sands_costing;
        $footingConfigure->common_aggregate_per_cost = $request->common_aggregate_costing??0;
        $footingConfigure->common_picked_per_cost = $request->common_picked_costing??0;
        //Total Price
        $footingConfigure->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
        $footingConfigure->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
        $footingConfigure->total_beam_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
        $footingConfigure->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;
        $footingConfigure->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_picked_costing;
        $footingConfigure->total_common_bar_price = 0;

        $footingConfigure->save();
        $footingConfigure->common_configure_no = str_pad($footingConfigure->id, 4, "0", STR_PAD_LEFT);
        $footingConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            $type_length = $request->type_length[$counter] - 0.5;
            $rft = (((($request->length[$counter] / $request->spacing[$counter]) + 1) * $type_length * $request->layer[$counter]));
            $division = $request->length[$counter]/$request->spacing[$counter];

            FootingConfigureProduct::create([
                'common_configure_id' => $footingConfigure->id,
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
                'sub_total_kg' => $rft * $request->kg_by_rft[$counter],
                'sub_total_ton' => $rft * $request->kg_by_rft[$counter] / $request->kg_by_ton[$counter],
            ]);

            $totalKg += $rft * $request->kg_by_rft[$counter];
            $totalTon += $rft * $request->kg_by_rft[$counter] / $request->kg_by_ton[$counter];

            $counter++;
        }

        $footingConfigure->total_ton = $totalTon * $request->costing_segment_quantity;
        $footingConfigure->total_kg = $totalKg * $request->costing_segment_quantity;
        $footingConfigure->total_common_bar_price = ($totalKg * $request->common_bar_costing) * $request->costing_segment_quantity;
        $footingConfigure->save();

        return redirect()->route('footing_configure.details', ['footingConfigure' => $footingConfigure->id]);
    }

    public function footingConfigureDatatable() {
        $query = FootingConfigure::with('project','costingSegment', 'footingType');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(FootingConfigure $footingConfigure) {
                return $footingConfigure->project->name ??'';
            })
            ->addColumn('segment_name', function(FootingConfigure $footingConfigure) {
                return $footingConfigure->costingSegment->name ??'';
            })
            ->addColumn('footing_type', function(FootingConfigure $footingConfigure) {
                return $footingConfigure->footingType->name ??'';
            })
            ->addColumn('action', function(FootingConfigure $footingConfigure) {

                return '<a href="'.route('footing_configure.details', ['footingConfigure' => $footingConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(FootingConfigure $footingConfigure) {
                return $footingConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function footingConfigureDetails(FootingConfigure $footingConfigure){
        return view('estimate.footing_configure.details',compact('footingConfigure'));
    }

    public function footingConfigurePrint(FootingConfigure $footingConfigure){
        return view('estimate.footing_configure.print',compact('footingConfigure'));
    }
}
