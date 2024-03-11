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


        $returningWall = new ReturningWallConfigure();
        $returningWall->estimate_project_id = $request->estimate_project;
        $returningWall->costing_segment_id = $request->costing_segment;
        $returningWall->costing_segment_quantity = $request->costing_segment_quantity;
        $returningWall->course_aggregate_type = $request->course_aggregate_type;
        $returningWall->first_ratio = $request->first_ratio;
        $returningWall->second_ratio = $request->second_ratio;
        $returningWall->third_ratio = $request->third_ratio;
        $returningWall->slab_length = $request->segment_length;
        $returningWall->slab_width = $request->segment_width;
        $returningWall->slab_thickness = $request->segment_thickness;
        $returningWall->total_volume = $request->total_volume * $request->costing_segment_quantity;
        $returningWall->dry_volume = $request->dry_volume * $request->costing_segment_quantity;
        $returningWall->total_dry_volume = $request->total_dry_volume * $request->costing_segment_quantity;
        $returningWall->date = $request->date;
        $returningWall->note = $request->note;
        $returningWall->total_ton = 0;
        $returningWall->total_kg = 0;
        $returningWall->total_cement = $totalCement * $request->costing_segment_quantity;
        $returningWall->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
        if($request->course_aggregate_type == 3){
            $returningWall->total_cement_bag = 0;
            $returningWall->total_cement = 0;
            $returningWall->total_sands = 0;
            $returningWall->total_s_sands = 0;
            $returningWall->total_aggregate = 0;
            $returningWall->total_picked = 0;
        }else if($request->course_aggregate_type == 2){
            $returningWall->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
            $returningWall->total_cement = $totalCement * $request->costing_segment_quantity;
            $returningWall->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $returningWall->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $returningWall->total_aggregate = 0;
            $returningWall->total_picked = $totalPiked * $request->costing_segment_quantity;
        }else{
            $returningWall->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
            $returningWall->total_cement = $totalCement * $request->costing_segment_quantity;
            $returningWall->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $returningWall->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $returningWall->total_aggregate = $totalAggregate * $request->costing_segment_quantity;
        }
        
        //price
        $returningWall->common_bar_per_cost = $request->common_bar_costing;
        $returningWall->common_cement_per_cost = $request->common_cement_costing;
        $returningWall->common_sands_per_cost = $request->common_sands_costing;
        $returningWall->s_sands_costing = $request->s_sands_costing;
        //Total Price
    
        if($request->course_aggregate_type == 3){
            $returningWall->total_common_cement_bag_price = 0;
            $returningWall->total_common_sands_price = 0;
            $returningWall->total_slab_s_sands_price = 0;
            $returningWall->total_mat_rmc_price = $request->total_volume * $request->rmc_costing;
            $returningWall->common_aggregate_per_cost = 0;
            $returningWall->common_picked_per_cost = 0;
        }else if($request->course_aggregate_type == 2){
            $returningWall->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
            $returningWall->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $returningWall->total_slab_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $returningWall->total_mat_rmc_price = 0;
            $returningWall->total_common_aggregate_price = 0;
            $returningWall->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_picked_costing;;
        }else{
            $returningWall->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
            $returningWall->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $returningWall->total_slab_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $returningWall->total_mat_rmc_price = 0;
            $returningWall->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;;
            $returningWall->total_common_picked_price = 0;
        }
        
        $returningWall->total_common_bar_price = 0;

        $returningWall->save();
        $returningWall->common_configure_no = str_pad($returningWall->id, 5, "0", STR_PAD_LEFT);
        $returningWall->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            $rft = ($request->length[$counter]/$request->spacing[$counter]) + 1;
            $item = ($request->type_length[$counter] - (($request->clear_cover[$counter] / 12) * 2));
            $data = $rft *  $item;
            $sub_total =  ($request->lapping_lenght[$counter] * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter];
            $total_main_rod = (($data *  $request->kg_by_rft[$counter]) + $sub_total) * $request->layer[$counter];
           
            ReturningWallConfigureProduct::create([
                'common_configure_id' => $returningWall->id,
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

            ReturningWallConfigureProduct::create([
                'common_configure_id' => $returningWall->id,
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


        $returningWall->total_ton = $totalTon * $request->costing_segment_quantity;
        $returningWall->total_kg = $totalKg * $request->costing_segment_quantity;
        $returningWall->total_common_bar_price = ($totalKg * $request->costing_segment_quantity) * $request->common_bar_costing;
        $returningWall->save();

        return redirect()->route('returning_wall_configure.details', ['returningWall' => $returningWall->id]);
    }

    public function returningWallConfigureDetails(ReturningWallConfigure $returningWall){
        return view('estimate.returning_wall_configure.details',compact('returningWall'));
    }
    public function returningWallConfigurePrint(ReturningWallConfigure $returningWall){
        return view('estimate.returning_wall_configure.print',compact('returningWall'));
    }

    public function returningWallConfigureDelete(ReturningWallConfigure $returningWall){
        ReturningWallConfigure::find($returningWall->id)->delete();
        ReturningWallConfigureProduct::where('common_configure_id', $returningWall->id)->delete();
        return redirect()->route('returning_wall_configure')->with('message', 'Returning Wall Info Deleted Successfully.');
    }

    public function returningWallConfigureDatatable() {
        $query = ReturningWallConfigure::with('project','costingSegment');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(ReturningWallConfigure $returningWall) {
                return $returningWall->project->name??'';
            })
            ->addColumn('segment_name', function(ReturningWallConfigure $returningWall) {
                return $returningWall->costingSegment->name??'';
            })
            ->addColumn('action', function(ReturningWallConfigure $returningWall) {
                $btn = '';
                $btn = '<a href="'.route('returning_wall_configure.details', ['returningWall' => $returningWall->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('returning_wall_configure.delete', ['returningWall' => $returningWall->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(ReturningWallConfigure $returningWall) {
                return $returningWall->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
