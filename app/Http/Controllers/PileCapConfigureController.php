<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\Batch;
use App\Models\BeamConfigure;
use App\Models\BeamConfogureProduct;
use App\Models\BeamType;
use App\Models\ColumnCofigure;
use App\Models\PileCapConfigure;
use App\Models\PileCapConfigureProduct;
use App\Models\CostingSegment;
use App\Models\PileConfigure;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PileCapConfigureController extends Controller
{
    public function pileCapConfigureAll() {
        return view('estimate.pile_cap_configure.all');
    }

    public function pileCapConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $beamTypes = Batch::where('status',1)->get();
        $pileCost = PileConfigure::orderBy('id','desc')->first();
        $estimateProjects = EstimateProject::where('status',1)->get();
        $costingSegments = CostingSegment::where('status',1)->get();
        $columnCost = ColumnCofigure::orderBy('id','desc')->first();
        return view('estimate.pile_cap_configure.add',compact('estimateProjects',
        'beamTypes','pileCost', 'costingSegments', 'columnCost'));
    }

    public function pileCapConfigureAddPost(Request $request) {
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


        $pileCapConfigure = new PileCapConfigure();
        $pileCapConfigure->estimate_project_id = $request->estimate_project;
        $pileCapConfigure->costing_segment_id = $request->costing_segment;
        $pileCapConfigure->costing_segment_quantity = $request->costing_segment_quantity;
        $pileCapConfigure->footing_type_id = $request->footing_type;
        $pileCapConfigure->course_aggregate_type = $request->course_aggregate_type;
        $pileCapConfigure->first_ratio = $request->first_ratio;
        $pileCapConfigure->second_ratio = $request->second_ratio;
        $pileCapConfigure->third_ratio = $request->third_ratio;
        $pileCapConfigure->segment_length = $request->segment_length;
        $pileCapConfigure->segment_width = $request->segment_width;
        $pileCapConfigure->segment_thickness = $request->segment_thickness;
        $pileCapConfigure->date = $request->date;
        $pileCapConfigure->note = $request->note;
        $pileCapConfigure->total_ton = 0;
        $pileCapConfigure->total_kg = 0;
        $pileCapConfigure->total_volume = $request->total_volume * $request->costing_segment_quantity;
        $pileCapConfigure->dry_volume = $request->dry_volume * $request->costing_segment_quantity;
        $pileCapConfigure->total_dry_volume = $request->total_dry_volume * $request->costing_segment_quantity;

        if($request->course_aggregate_type == 1){
            $pileCapConfigure->total_cement = $totalCement * $request->costing_segment_quantity;
            $pileCapConfigure->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
            $pileCapConfigure->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $pileCapConfigure->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $pileCapConfigure->total_aggregate = $totalAggregate * $request->costing_segment_quantity;
        }else if($request->course_aggregate_type == 2){
            $pileCapConfigure->total_cement = $totalCement * $request->costing_segment_quantity;
            $pileCapConfigure->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
            $pileCapConfigure->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $pileCapConfigure->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $pileCapConfigure->total_aggregate = 0;
            $pileCapConfigure->total_picked = $totalPiked * $request->costing_segment_quantity;
        }else{
            $pileCapConfigure->total_cement = 0;
            $pileCapConfigure->total_cement_bag = 0;
            $pileCapConfigure->total_sands = 0;
            $pileCapConfigure->total_s_sands =0;
            $pileCapConfigure->total_aggregate = 0;
            $pileCapConfigure->total_picked = 0;
        }
        //price
        $pileCapConfigure->common_bar_per_cost = $request->common_bar_costing;
        $pileCapConfigure->common_cement_per_cost = $request->common_cement_costing;
        $pileCapConfigure->common_sands_per_cost = $request->common_sands_costing;
        $pileCapConfigure->s_sands_costing = $request->s_sands_costing;
        $pileCapConfigure->common_aggregate_per_cost = $request->common_aggregate_costing??0;
        $pileCapConfigure->common_picked_per_cost = $request->common_picked_costing??0;
        //Total Price

        if($request->course_aggregate_type == 1){
            $pileCapConfigure->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $pileCapConfigure->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
            $pileCapConfigure->total_beam_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $pileCapConfigure->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;
            $pileCapConfigure->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_picked_costing;
            $pileCapConfigure->total_pile_cap_rmc_price = 0;
        }else if($request->course_aggregate_type == 2){
            $pileCapConfigure->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
            $pileCapConfigure->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $pileCapConfigure->total_beam_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $pileCapConfigure->total_common_aggregate_price = 0;
            $pileCapConfigure->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_picked_costing;
            $pileCapConfigure->total_pile_cap_rmc_price = 0;
        }else{
            $pileCapConfigure->total_pile_cap_rmc_price = $request->total_volume * $request->rmc_costing;
            $pileCapConfigure->total_common_cement_bag_price = 0;
            $pileCapConfigure->total_common_sands_price = 0;
            $pileCapConfigure->total_beam_s_sands_price = 0;
            $pileCapConfigure->total_common_aggregate_price = 0;
            $pileCapConfigure->total_common_picked_price = 0;
        }
        $pileCapConfigure->total_common_bar_price = 0;

        $pileCapConfigure->save();
        $pileCapConfigure->common_configure_no = str_pad($pileCapConfigure->id, 4, "0", STR_PAD_LEFT);
        $pileCapConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            $type_length = $request->type_length[$counter] - 0.5;
            $rft = (((($request->length[$counter] / $request->spacing[$counter]) + 1) * $type_length * $request->layer[$counter]));
            $division = $request->length[$counter]/$request->spacing[$counter];

            PileCapConfigureProduct::create([
                'common_configure_id' => $pileCapConfigure->id,
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

        $pileCapConfigure->total_ton = $totalTon * $request->costing_segment_quantity;
        $pileCapConfigure->total_kg = $totalKg * $request->costing_segment_quantity;
        $pileCapConfigure->total_common_bar_price = ($totalKg * $request->common_bar_costing) * $request->costing_segment_quantity;
        $pileCapConfigure->save();

        return redirect()->route('pile_cap_configure.details', ['pileCapConfigure' => $pileCapConfigure->id]);
    }

    public function pileCapConfigureDetails(PileCapConfigure $pileCapConfigure){
        return view('estimate.pile_cap_configure.details',compact('pileCapConfigure'));
    }
    public function pileCapConfigurePrint(PileCapConfigure $pileCapConfigure){
        return view('estimate.pile_cap_configure.print',compact('pileCapConfigure'));
    }

    public function pileCapConfigureDelete(PileCapConfigure $pileCapConfigure){
        PileCapConfigure::find($pileCapConfigure->id)->delete();
        PileCapConfigureProduct::where('common_configure_id', $pileCapConfigure->id)->delete();
        return redirect()->back();
    }

    public function pileCapConfigureDatatable() {
        $query = PileCapConfigure::with('project','costingSegment', 'footingType');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(PileCapConfigure $pileCapConfigure) {
                return $pileCapConfigure->project->name??'';
            })
            ->addColumn('segment_name', function(PileCapConfigure $pileCapConfigure) {
                return $pileCapConfigure->costingSegment->name??'';
            })
            ->addColumn('pile_cap_type', function(PileCapConfigure $pileCapConfigure) {
                return $pileCapConfigure->footingType->name??'';
            })
            ->addColumn('action', function(PileCapConfigure $pileCapConfigure) {

                $btn = '';
                $btn = '<a href="'.route('pile_cap_configure.details', ['pileCapConfigure' => $pileCapConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('pile_cap_configure.delete', ['pileCapConfigure' => $pileCapConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(PileCapConfigure $pileCapConfigure) {
                return $pileCapConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
