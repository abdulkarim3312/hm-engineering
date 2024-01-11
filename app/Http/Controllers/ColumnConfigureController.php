<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\BeamConfigure;
use App\Models\ColumnCofigure;
use App\Models\ColumnConfigureProduct;
use App\Models\ColumnCofigureProduct;
use App\Models\ColumnType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ColumnConfigureController extends Controller
{
    public function columnConfigure() {
        return view('estimate.column_configure.all');
    }

    public function columnConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $columnTypes = ColumnType::where('status',1)->get();
        // dd($columnTypes);
        $beamCost = BeamConfigure::orderBy('id','desc')->first();
        return view('estimate.column_configure.add',compact('estimateProjects',
            'columnTypes','beamCost'));
    }

    public function columnConfigureAddPost(Request $request) {

        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'column_type' => 'required',
            'course_aggregate_type' => 'required',
            'ring_quantity' => 'required',
            'tie_interval' => 'required',
            'column_quantity' => 'required',
            'first_ratio' => 'required',
            'second_ratio' => 'required',
            'third_ratio' => 'required',
            'column_length' => 'required',
            'tie_length_volume' => 'required',
            'tie_width_volume' => 'required',
            'total_volume' => 'required',
            'dry_volume' => 'required',
            'total_dry_volume' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'column_bar_costing' => 'required|numeric|min:0',
            'column_cement_costing' => 'required|numeric|min:0',
            'column_sands_costing' => 'required|numeric|min:0',
            'column_aggregate_costing' => 'required|numeric|min:0',
            'column_picked_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'dia.*' => 'required|numeric|min:0',
            'dia_square.*' => 'required|numeric|min:0',
            'value_of_bar.*' => 'required|numeric|min:0',
            'kg_by_rft.*' => 'required|numeric|min:0',
            'kg_by_ton.*' => 'required|numeric|min:0',
            'number_of_bar.*' => 'required|numeric|min:1',
            'lapping_length.*' => 'required|numeric|min:0',
            'lapping_nos.*' => 'required|numeric|min:0',
            'tie_product.*' => 'required',
            'tie_dia.*' => 'required|numeric|min:0',
            'tie_dia_square.*' => 'required|numeric|min:0',
            'tie_value_of_bar.*' => 'required|numeric|min:0',
            'tie_kg_by_rft.*' => 'required|numeric|min:0',
            'tie_kg_by_ton.*' => 'required|numeric|min:0',
            'tie_length.*' => 'required|numeric|min:0',
            'tie_width.*' => 'required|numeric|min:0',
            'tie_clear_cover.*' => 'required|numeric|min:0',
        ]);

        $totalRatio = ($request->first_ratio + $request->second_ratio + $request->third_ratio);
        $totalCement = ($request->total_dry_volume * $request->first_ratio/$totalRatio);
        $totalCementBag = ($totalCement/1.25);
        $totalSands = ($request->total_dry_volume * $request->second_ratio/$totalRatio);
        $totalAggregate = ($request->total_dry_volume * $request->third_ratio/$totalRatio);

        if ($request->course_aggregate_type == 2){
            $totalPiked = ($totalAggregate * 11.11);
        }else{
            $totalPiked = 0;
        }


        $columnConfigure = new ColumnCofigure();
        $columnConfigure->estimate_project_id = $request->estimate_project;
        $columnConfigure->estimate_floor_id = $request->estimate_floor;
        $columnConfigure->column_type_id = $request->column_type;
        $columnConfigure->ring_quantity = $request->ring_quantity * $request->column_quantity;
        $columnConfigure->tie_interval = $request->tie_interval * $request->column_quantity;
        $columnConfigure->column_quantity = $request->column_quantity;
        $columnConfigure->first_ratio = $request->first_ratio;
        $columnConfigure->second_ratio = $request->second_ratio;
        $columnConfigure->third_ratio = $request->third_ratio;
        $columnConfigure->column_length = $request->column_length * $request->column_quantity;
        $columnConfigure->tie_length_volume = $request->tie_length_volume * $request->column_quantity;
        $columnConfigure->tie_width_volume = $request->tie_width_volume * $request->column_quantity;
        $columnConfigure->total_volume = $request->total_volume * $request->column_quantity;
        $columnConfigure->dry_volume = $request->dry_volume * $request->column_quantity;
        $columnConfigure->total_dry_volume = $request->total_dry_volume * $request->column_quantity;
        $columnConfigure->date = $request->date;
        $columnConfigure->note = $request->note;
        $columnConfigure->total_ton_straight = 0;
        $columnConfigure->total_kg_straight = 0;
        $columnConfigure->total_ton_tie = 0;
        $columnConfigure->total_kg_tie = 0;
        $columnConfigure->total_kg = 0;
        $columnConfigure->total_ton = 0;
        $columnConfigure->total_cement = $totalCement * $request->column_quantity;
        $columnConfigure->total_cement_bag = $totalCementBag * $request->column_quantity;
        $columnConfigure->total_sands = $totalSands * $request->column_quantity;
        $columnConfigure->total_aggregate = $totalAggregate * $request->column_quantity;
        $columnConfigure->total_picked = $totalPiked * $request->column_quantity;
        //price
        $columnConfigure->column_bar_per_cost = $request->column_bar_costing;
        $columnConfigure->column_cement_per_cost = $request->column_cement_costing;
        $columnConfigure->column_sands_per_cost = $request->column_sands_costing;
        $columnConfigure->column_aggregate_per_cost = $request->column_aggregate_costing??0;
        $columnConfigure->column_picked_per_cost = $request->column_picked_costing??0;
        //Total Price
        $columnConfigure->total_column_cement_bag_price = ($totalCementBag * $request->column_quantity) * $request->column_cement_costing;
        $columnConfigure->total_column_sands_price = ($totalSands * $request->column_quantity) * $request->column_sands_costing;
        $columnConfigure->total_column_aggregate_price = ($totalAggregate * $request->column_quantity) * $request->column_aggregate_costing;
        $columnConfigure->total_column_picked_price = ($totalPiked * $request->column_quantity) * $request->column_picked_costing;
        $columnConfigure->total_column_bar_price = 0;

        $columnConfigure->save();
        $columnConfigure->column_configure_no = str_pad($columnConfigure->id, 5, "0", STR_PAD_LEFT);
        $columnConfigure->save();

        $counter = 0;

        $totalTonStraight = 0;
        $totalKgStraight = 0;

        foreach ($request->product as $key => $reqProduct) {

            $lapping = (($request->lapping_length[$counter] * $request->lapping_nos[$counter]) * $request->kg_by_rft[$counter]);

                ColumnConfigureProduct::create([
                    'column_configure_id' => $columnConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'bar_type' => $reqProduct,
                    'dia' => $request->dia[$counter],
                    'dia_square' => $request->dia_square[$counter],
                    'value_of_bar' => $request->value_of_bar[$counter],
                    'kg_by_rft' => $request->kg_by_rft[$counter],
                    'kg_by_ton' => $request->kg_by_ton[$counter],
                    'number_of_bar' => $request->number_of_bar[$counter] * $request->column_quantity,
                    'lapping_length' => $request->lapping_length[$counter] * $request->column_quantity,
                    'lapping_nos' => $request->lapping_nos[$counter] * $request->column_quantity,
                    'sub_total_kg_straight' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->column_length) + $lapping) * $request->column_quantity),
                    'sub_total_ton_straight' => (((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter])
                            * $request->column_height + $lapping) / $request->kg_by_ton[$counter]) * $request->column_quantity),
                ]);

                $totalKgStraight += ((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->column_length) + $lapping);
                $totalTonStraight += ((($request->number_of_bar[$counter] * $request->kg_by_rft[$counter]) * $request->column_height + $lapping) /$request->kg_by_ton[$counter]);

            $counter++;
        }

        $counter = 0;
        $totalTonTie = 0;
        $totalKgTie = 0;

        foreach ($request->tie_product as $key => $reqProduct) {

            $length_tie_total = $request->tie_length[$counter] - (2 * $request->tie_clear_cover[$counter]);
            $width_tie_total = $request->tie_width[$counter] - (2 * $request->tie_clear_cover[$counter]);

            $pre_tie_bar = (($length_tie_total + $width_tie_total) * 2) + 0.42;

            ColumnConfigureProduct::create([
                'column_configure_id' => $columnConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'tie_bar_type' => $reqProduct,
                'tie_dia' => $request->tie_dia[$counter],
                'tie_dia_square' => $request->tie_dia_square[$counter],
                'tie_value_of_bar' => $request->tie_value_of_bar[$counter],
                'tie_kg_by_rft' => $request->tie_kg_by_rft[$counter],
                'tie_kg_by_ton' => $request->tie_kg_by_ton[$counter],
                'tie_length' => $request->tie_length[$counter] * $request->column_quantity,
                'tie_width' => $request->tie_width[$counter] * $request->column_quantity,
                'tie_clear_cover' => $request->tie_clear_cover[$counter] * $request->column_quantity,
                'sub_total_kg_tie' => ((($pre_tie_bar * $request->tie_kg_by_rft[$counter]) * $request->ring_quantity) * $request->column_quantity),
                'sub_total_ton_tie' => (((($pre_tie_bar * $request->tie_kg_by_rft[$counter])
                        * $request->ring_quantity)/$request->tie_kg_by_ton[$counter]) * $request->column_quantity),
            ]);

            $totalKgTie += (($pre_tie_bar * $request->tie_kg_by_rft[$counter]) * $request->ring_quantity);
            $totalTonTie += ((($pre_tie_bar * $request->tie_kg_by_rft[$counter]) * $request->ring_quantity)/$request->tie_kg_by_ton[$counter]);

            $counter++;
        }

        $columnConfigure->total_ton_straight = $totalTonStraight * $request->column_quantity;
        $columnConfigure->total_kg_straight = $totalKgStraight * $request->column_quantity;
        $columnConfigure->total_kg_tie = $totalKgTie * $request->column_quantity;
        $columnConfigure->total_ton_tie = $totalTonTie * $request->column_quantity;
        $columnConfigure->total_ton = ($totalTonStraight + $totalTonTie) * $request->column_quantity;
        $columnConfigure->total_kg = ($totalKgStraight + $totalKgTie) * $request->column_quantity;
        $columnConfigure->total_column_bar_price = (($totalKgStraight + $totalKgTie) * $request->column_quantity) * $request->column_bar_costing;
        $columnConfigure->save();

        return redirect()->route('column_configure.details', ['columnConfigure' => $columnConfigure->id]);
    }

    public function columnConfigureDetails(ColumnCofigure $columnConfigure){
        return view('estimate.column_configure.details',compact('columnConfigure'));
    }
    public function columnConfigurePrint(ColumnCofigure $columnConfigure){
        return view('estimate.column_configure.print',compact('columnConfigure'));
    }

    public function columnConfigureDatatable() {
        $query = ColumnCofigure::with('project');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(ColumnCofigure $columnConfigure) {
                return $columnConfigure->project->name??'';
            })
            ->addColumn('action', function(ColumnCofigure $columnConfigure) {

                return '<a href="'.route('column_configure.details', ['columnConfigure' => $columnConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(ColumnCofigure $columnConfigure) {
                return $columnConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
