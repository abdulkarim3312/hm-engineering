<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\ColumnCofigure;
use App\Models\CostingSegment;
use App\Models\WaterTankConfigure;
use App\Models\WaterTankConfigureProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WaterTankController extends Controller
{
    public function waterTankConfigure() {
        return view('estimate.water_tank_configure.all');
    }
    public function waterTankConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $costingSegments = CostingSegment::where('status',1)->get();
        $columnCost = ColumnCofigure::orderBy('id','desc')->first();
        return view('estimate.water_tank_configure.add',compact('estimateProjects',
            'costingSegments','columnCost'));
    }

    public function waterTankConfigureAddPost(Request $request) {
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


        $waterTank = new WaterTankConfigure();
        $waterTank->estimate_project_id = $request->estimate_project;
        $waterTank->costing_segment_id = $request->costing_segment;
        $waterTank->costing_segment_quantity = $request->costing_segment_quantity;
        $waterTank->course_aggregate_type = $request->course_aggregate_type;
        $waterTank->first_ratio = $request->first_ratio;
        $waterTank->second_ratio = $request->second_ratio;
        $waterTank->third_ratio = $request->third_ratio;
        $waterTank->slab_length = $request->segment_length;
        $waterTank->slab_width = $request->segment_width;
        $waterTank->slab_thickness = $request->segment_thickness;
        $waterTank->total_volume = $request->total_volume * $request->costing_segment_quantity;
        $waterTank->dry_volume = $request->dry_volume * $request->costing_segment_quantity;
        $waterTank->total_dry_volume = $request->total_dry_volume * $request->costing_segment_quantity;
        $waterTank->date = $request->date;
        $waterTank->note = $request->note;
        $waterTank->total_ton = 0;
        $waterTank->total_kg = 0;

        if($request->course_aggregate_type == 3){
            $waterTank->total_sands = 0;
            $waterTank->total_s_sands = 0;
            $waterTank->total_aggregate = 0;
            $waterTank->total_cement = 0;
            $waterTank->total_cement_bag = 0;
            $waterTank->total_picked = 0;
        }else if($request->course_aggregate_type == 2){
            $waterTank->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
            $waterTank->total_cement = $totalCement * $request->costing_segment_quantity;
            $waterTank->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $waterTank->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $waterTank->total_aggregate = 0;
            $waterTank->total_picked = $totalPiked * $request->costing_segment_quantity;
        }else{
            $waterTank->total_cement_bag = $totalCementBag * $request->costing_segment_quantity;
            $waterTank->total_cement = $totalCement * $request->costing_segment_quantity;
            $waterTank->total_sands = (($totalSands)/2 * $request->costing_segment_quantity);
            $waterTank->total_s_sands =(($totalSands)/2 * $request->costing_segment_quantity);
            $waterTank->total_aggregate = $totalAggregate * $request->costing_segment_quantity;
            $waterTank->total_picked = 0;
        }

        //price
        $waterTank->common_bar_per_cost = $request->common_bar_costing;
        $waterTank->common_cement_per_cost = $request->common_cement_costing;
        $waterTank->common_sands_per_cost = $request->common_sands_costing;
        $waterTank->s_sands_costing = $request->s_sands_costing;
        //Total Price

        if($request->course_aggregate_type == 3){
            $waterTank->total_slab_rmc_price = $request->total_volume * $request->rmc_costing;
            $waterTank->total_common_cement_bag_price = 0;
            $waterTank->total_common_sands_price = 0;
            $waterTank->total_slab_s_sands_price = 0;
            $waterTank->common_aggregate_per_cost = 0;
            $waterTank->common_picked_per_cost = 0;
        }else if($request->course_aggregate_type == 2){
            $waterTank->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
            $waterTank->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $waterTank->total_slab_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $waterTank->total_slab_rmc_price = 0;
            $waterTank->total_common_aggregate_price = 0;
            $waterTank->total_common_picked_price = ($totalPiked * $request->costing_segment_quantity) * $request->common_picked_costing;
        }else{
            $waterTank->total_common_cement_bag_price = ($totalCementBag * $request->costing_segment_quantity) * $request->common_cement_costing;
            $waterTank->total_common_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->common_sands_costing;
            $waterTank->total_slab_s_sands_price = (($totalSands/2) * $request->costing_segment_quantity) * $request->s_sands_costing;
            $waterTank->total_slab_rmc_price = 0;
            $waterTank->total_common_aggregate_price = ($totalAggregate * $request->costing_segment_quantity) * $request->common_aggregate_costing;;
            $waterTank->total_common_picked_price = 0;
        }


        $waterTank->total_common_bar_price = 0;

        $waterTank->save();
        $waterTank->common_configure_no = str_pad($waterTank->id, 5, "0", STR_PAD_LEFT);
        $waterTank->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {

            $rft = ($request->length[$counter]/$request->spacing[$counter]) + 1;
            $item = ($request->type_length[$counter] - (($request->clear_cover[$counter] / 12) * 2));
            $data = $rft *  $item;
            $sub_total =  ($request->lapping_lenght[$counter] * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter];
            $total_main_rod = (($data *  $request->kg_by_rft[$counter]) + $sub_total) * $request->layer[$counter];

            WaterTankConfigureProduct::create([
                'common_configure_id' => $waterTank->id,
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
            $totalTon += ($total_main_rod / $request->kg_by_ton[$counter]);

            $counter++;
        }


        $counter = 0;
        foreach ($request->product_two as $key => $reqProduct) {

            $rft = ($request->length_two[$counter]/$request->spacing_two[$counter]) + 1;
            $item = ($request->type_length_two[$counter] - (($request->clear_cover_two[$counter] / 12) * 2));
            $data = $rft *  $item;
            $sub_total =  ($request->lapping_lenght_two[$counter] * $request->lapping_nos_two[$counter]) * $request->kg_by_rft_two[$counter];
            $total_main_rod = (($data *  $request->kg_by_rft_two[$counter]) + $sub_total) * $request->layer_two[$counter];

            WaterTankConfigureProduct::create([
                'common_configure_id' => $waterTank->id,
                'estimate_project_id' => $request->estimate_project,
                'costing_segment_id' => $request->costing_segment,
                'bar_type' => $reqProduct,
                'dia' => $request->dia_two[$counter],
                'dia_square' => $request->dia_square_two[$counter],
                'value_of_bar' => $request->value_of_bar_two[$counter],
                'kg_by_rft' => $request->kg_by_rft_two[$counter],
                'kg_by_ton' => $request->kg_by_ton_two[$counter],
                'length_type' => $request->length_type_two[$counter] * $request->costing_segment_quantity,
                'length' => $request->length_two[$counter] * $request->costing_segment_quantity,
                'spacing' => $request->spacing_two[$counter] * $request->costing_segment_quantity,
                'type_length' => $request->type_length_two[$counter] * $request->costing_segment_quantity,
                'layer' => $request->layer_two[$counter] * $request->costing_segment_quantity,
                'sub_total_kg' => $total_main_rod,
                'sub_total_ton' => (($total_main_rod / $request->kg_by_ton_two[$counter]) * $request->costing_segment_quantity),
                'status' => 3,
            ]);

            $totalKg +=$total_main_rod;
            $totalTon += ($total_main_rod / $request->kg_by_ton_two[$counter]);

            $counter++;
        }

        $counter = 0;

        foreach ($request->extra_product as $key => $reqProduct) {

            WaterTankConfigureProduct::create([
                'common_configure_id' => $waterTank->id,
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

        $counter = 0;

        foreach ($request->extra_product_two as $key => $reqProduct) {

            WaterTankConfigureProduct::create([
                'common_configure_id' => $waterTank->id,
                'estimate_project_id' => $request->estimate_project,
                'costing_segment_id' => $request->costing_segment,
                'bar_type' => $reqProduct,
                'dia' => $request->extra_dia_two[$counter],
                'dia_square' => $request->extra_dia_square_two[$counter],
                'value_of_bar' => $request->extra_value_of_bar_two[$counter],
                'kg_by_rft' => $request->extra_kg_by_rft_two[$counter],
                'kg_by_ton' => $request->extra_kg_by_ton_two[$counter],
                'number_of_bar' => $request->extra_number_of_bar_two[$counter] * $request->costing_segment_quantity,
                'extra_length' => $request->extra_length_two[$counter]??0 * $request->costing_segment_quantity,
                'sub_total_kg' => ((($request->extra_number_of_bar_two[$counter] *
                        $request->extra_kg_by_rft_two[$counter]) * $request->extra_length_two[$counter]??0) * $request->costing_segment_quantity),
                'sub_total_ton' => (((($request->extra_number_of_bar_two[$counter] * $request->extra_kg_by_rft_two[$counter])
                        * $request->extra_length_two[$counter]??0)/$request->extra_kg_by_ton_two[$counter]) * $request->costing_segment_quantity),
                'status' => 2,
            ]);

            $totalKg += (($request->extra_number_of_bar_two[$counter] * $request->extra_kg_by_rft_two[$counter]) * $request->extra_length_two[$counter]??0);
            $totalTon += ((($request->extra_number_of_bar_two[$counter] * $request->extra_kg_by_rft_two[$counter]) * $request->extra_length_two[$counter]??0)/$request->extra_kg_by_ton_two[$counter]);

            $counter++;
        }


        $waterTank->total_ton = $totalTon * $request->costing_segment_quantity;
        $waterTank->total_kg = $totalKg * $request->costing_segment_quantity;
        $waterTank->total_common_bar_price = ($totalKg * $request->costing_segment_quantity) * $request->common_bar_costing;
        $waterTank->save();

        return redirect()->route('water_tank_configure.details', ['waterTank' => $waterTank->id]);
    }

    public function waterTankConfigureDetails(WaterTankConfigure $waterTank){
        return view('estimate.water_tank_configure.details',compact('waterTank'));
    }
    public function waterTankConfigurePrint(WaterTankConfigure $waterTank){
        return view('estimate.water_tank_configure.print',compact('waterTank'));
    }

    public function waterTankConfigureDelete(WaterTankConfigure $waterTank){
        WaterTankConfigure::find($waterTank->id)->delete();
        WaterTankConfigureProduct::where('common_configure_id', $waterTank->id)->delete();
        return redirect()->route('water_tank_configure')->with('message', 'Slab Info Deleted Successfully.');
    }


    public function waterTankConfigureDatatable() {
        $query = WaterTankConfigure::with('project','costingSegment');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(WaterTankConfigure $waterTank) {
                return $waterTank->project->name??'';
            })
            ->addColumn('segment_name', function(WaterTankConfigure $waterTank) {
                return $waterTank->costingSegment->name??'';
            })
            ->addColumn('action', function(WaterTankConfigure $waterTank) {
                $btn = '';
                $btn = '<a href="'.route('water_tank_configure.details', ['waterTank' => $waterTank->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('water_tank_configure.delete', ['waterTank' => $waterTank->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;

            })
            ->editColumn('date', function(WaterTankConfigure $waterTank) {
                return $waterTank->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
