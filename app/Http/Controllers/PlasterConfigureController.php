<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\BricksConfigure;
use App\Models\BricksConfigureProduct;
use App\Models\PlasterConfigure;
use App\Models\PlasterConfigureProduct;
use App\Models\EstimateFloorUnit;
use App\Models\UnitSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PlasterConfigureController extends Controller
{
    public function plasterConfigure() {
        return view('estimate.plaster_configure.all');
    }

    public function plasterConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        $plasterConfigureProducts = PlasterConfigureProduct::pluck('bricks_configure_product_id');
        $brickConfigureProducts = BricksConfigureProduct::whereNotIn('id',$plasterConfigureProducts)->get();
        $bricksCost = BricksConfigure::orderBy('id','desc')->first();

        return view('estimate.plaster_configure.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections','brickConfigureProducts','bricksCost'));
    }
    public function plasterConfigureAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'first_ratio' => 'required|numeric|min:1',
            'second_ratio' => 'required|numeric|min:1',
            'floor_number' => 'required|numeric|min:1',
            'dry_morter' => 'required|numeric|min:0',
            'date' => 'required',
            'note' => 'nullable',
            'plaster_cement_costing' => 'required|numeric|min:0',
            'plaster_sands_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'plaster_area.*' => 'required|numeric|min:0',
            'deduction_length_one.*' => 'required|numeric|min:0',
            'deduction_height_one.*' => 'required|numeric|min:0',
            'deduction_length_two.*' => 'required|numeric|min:0',
            'deduction_height_two.*' => 'required|numeric|min:0',
            'plaster_side.*' => 'required|numeric|min:0',
            'plaster_thickness.*' => 'required|numeric|min:0',
        ]);

        $plasterConfigure = new PlasterConfigure();
        $plasterConfigure->estimate_project_id = $request->estimate_project;
        $plasterConfigure->estimate_floor_id = $request->estimate_floor;
        $plasterConfigure->estimate_floor_unit_id = $request->estimate_floor_unit;
        $plasterConfigure->first_ratio = $request->first_ratio;
        $plasterConfigure->second_ratio = $request->second_ratio;
        $plasterConfigure->dry_morter = $request->dry_morter * $request->floor_number;
        $plasterConfigure->floor_number = $request->floor_number;
        $plasterConfigure->date = $request->date;
        $plasterConfigure->note = $request->note;
        // dd($plasterConfigure->note);
        $plasterConfigure->total_plaster_area = 0;
        $plasterConfigure->total_cement = 0;
        $plasterConfigure->total_cement_bag = 0;
        $plasterConfigure->total_sands = 0;
        //Price
        $plasterConfigure->plaster_cement_costing = $request->plaster_cement_costing;
        $plasterConfigure->plaster_sands_costing = $request->plaster_sands_costing;
        //Total Price
        $plasterConfigure->total_plaster_cement_cost = 0;
        $plasterConfigure->total_plaster_sands_cost = 0;

        $plasterConfigure->save();
        $plasterConfigure->plaster_configure_no = str_pad($plasterConfigure->id, 5, "0", STR_PAD_LEFT);
        $plasterConfigure->save();

        $totalRatio = ($request->first_ratio + $request->second_ratio);

        $counter = 0;
        $totalPlasterArea = 0;
        $totalCement = 0;
        $totalSands = 0;

        foreach ($request->product as $key => $reqProduct) {

            $firstDeduction = $request->deduction_length_one[$counter] * $request->deduction_height_one[$counter];
            $secondDeduction = $request->deduction_length_two[$counter] * $request->deduction_height_two[$counter];

            $subTotalDeduction = ($firstDeduction + $secondDeduction);

            $totalArea = ((($request->plaster_area[$counter] * $request->plaster_side[$counter]) - $subTotalDeduction) * $request->plaster_thickness[$counter]);

            $subTotalMorter = ($totalArea * $request->dry_morter);

            $subTotalCement = $subTotalMorter * $request->first_ratio/$totalRatio;
            $subTotalSands = $subTotalMorter * $request->second_ratio/$totalRatio;


            PlasterConfigureProduct::create([
                'plaster_configure_id' => $plasterConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'estimate_floor_unit_id' => $request->estimate_floor_unit,
                'bricks_configure_product_id' => $reqProduct,
                'plaster_area' => $request->plaster_area[$counter],
                'wall_direction' => $request->product[$counter],
                'length' => $request->length[$counter] * $request->floor_number,
                'height' => $request->height[$counter] * $request->floor_number,
                'wall_nos' => $request->wall_nos[$counter] * $request->floor_number,
                'deduction_length_one' => $request->deduction_length_one[$counter] * $request->floor_number,
                'deduction_height_one' => $request->deduction_height_one[$counter] * $request->floor_number,
                'deduction_length_two' => $request->deduction_length_two[$counter] * $request->floor_number,
                'deduction_height_two' => $request->deduction_height_two[$counter] * $request->floor_number,
                'sub_total_deduction' => $subTotalDeduction * $request->floor_number,
                'plaster_side' => $request->plaster_side[$counter] * $request->floor_number,
                'plaster_thickness' => $request->plaster_thickness[$counter] * $request->floor_number,
                'sub_total_area' => $totalArea * $request->floor_number,
                'sub_total_dry_area' => $subTotalMorter * $request->floor_number,
                'sub_total_cement' => $subTotalCement * $request->floor_number,
                'sub_total_sands' => $subTotalSands * $request->floor_number,
            ]);

            $totalPlasterArea += $subTotalMorter;
            $totalCement += $subTotalCement;
            $totalSands += $subTotalSands;

            $counter++;
        }

        $plasterConfigure->total_plaster_area = $totalPlasterArea * $request->floor_number;
        $plasterConfigure->total_cement = $totalCement * $request->floor_number;
        $plasterConfigure->total_sands = $totalSands * $request->floor_number;
        $plasterConfigure->total_cement_bag = ($totalCement * 0.8) * $request->floor_number;
        $plasterConfigure->total_plaster_cement_cost = (($totalCement * 0.8) * $request->floor_number) * $request->plaster_cement_costing;
        $plasterConfigure->total_plaster_sands_cost = ($totalSands * $request->floor_number) * $request->plaster_sands_costing;
        $plasterConfigure->save();

        return redirect()->route('plaster_configure.details', ['plasterConfigure' => $plasterConfigure->id]);
    }

    public function plasterConfigureDetails(PlasterConfigure $plasterConfigure){
        return view('estimate.plaster_configure.details',compact('plasterConfigure'));
    }
    public function plasterConfigurePrint(PlasterConfigure $plasterConfigure){
        return view('estimate.plaster_configure.print',compact('plasterConfigure'));
    }

    public function plasterConfigureDatatable() {
        $query = PlasterConfigure::query();

        return DataTables::eloquent($query)
//            ->addColumn('project_name', function(PlasterConfigure $plasterConfigure) {
//                return $plasterConfigure->project->name??'';
//            })
//            ->addColumn('estimate_floor', function(PlasterConfigure $plasterConfigure) {
//                return $plasterConfigure->estimateFloor->name??'';
//            })
//            ->addColumn('estimate_floor_unit', function(PlasterConfigure $plasterConfigure) {
//                return $plasterConfigure->estimateFloorUnit->name??'';
//            })
//            ->addColumn('unit_section', function(PlasterConfigure $plasterConfigure) {
//                return $plasterConfigure->unitSection->name??'';
//            })
            ->addColumn('action', function(PlasterConfigure $plasterConfigure) {

                return '<a href="'.route('plaster_configure.details', ['plasterConfigure' => $plasterConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(PlasterConfigure $plasterConfigure) {
                return $plasterConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
