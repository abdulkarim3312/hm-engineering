<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\EstimateFloorUnit;
use App\Models\PaintConfigure;
use App\Models\PaintConfigureProduct;
use App\Models\UnitSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaintConfigureController extends Controller
{
    public function paintConfigure() {
        return view('estimate.paint_configure.all');
    }

    public function paintConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        return view('estimate.paint_configure.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections'));
    }

    public function paintConfigureAddPost(Request $request) {

        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'floor_number' => 'required|numeric|min:1',
            'color_paint_per_cft' => 'required',
            'seller_paint_per_cft' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'paint_costing' => 'required|numeric|min:0',
            'seller_costing' => 'required|numeric|min:0',

            'product.*' => 'required',
            'wall_direction.*' => 'required',
            'paint_type.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'deduction_length_one.*' => 'required|numeric|min:0',
            'deduction_height_one.*' => 'required|numeric|min:0',
            'deduction_length_two.*' => 'required|numeric|min:0',
            'deduction_height_two.*' => 'required|numeric|min:0',
            'side.*' => 'required|numeric|min:0',
            'code_nos.*' => 'required|numeric|min:1',
        ]);

        $paintConfigure = new PaintConfigure();
        $paintConfigure->estimate_project_id = $request->estimate_project;
        $paintConfigure->estimate_floor_id = $request->estimate_floor;
        $paintConfigure->estimate_floor_unit_id = $request->estimate_floor_unit;
        $paintConfigure->floor_number = $request->floor_number;
        $paintConfigure->color_paint_per_cft = $request->color_paint_per_cft;
        $paintConfigure->seller_paint_per_cft = $request->seller_paint_per_cft;
        $paintConfigure->total_area_with_floor = 0;
        $paintConfigure->total_area_without_floor = 0;
        $paintConfigure->total_paint_liter_with_floor = 0;
        $paintConfigure->total_paint_liter_without_floor = 0;
        $paintConfigure->total_seller_liter_with_floor = 0;
        $paintConfigure->total_seller_liter_without_floor = 0;
        $paintConfigure->date = $request->date;
        $paintConfigure->note = $request->note;
        //Price
        $paintConfigure->paint_costing = $request->paint_costing;
        $paintConfigure->seller_costing = $request->seller_costing;
        //Total Price
        $paintConfigure->total_paint_cost = 0;
        $paintConfigure->total_seller_cost = 0;

        $paintConfigure->save();
        $paintConfigure->paint_configure_no = str_pad($paintConfigure->id, 5, "0", STR_PAD_LEFT);
        $paintConfigure->save();

        $counter = 0;
        $totalArea = 0;
        $totalPaintLiter = 0;
        $totalSellerLiter = 0;
        foreach ($request->product as $key => $reqProduct) {

            $area = ($request->length[$counter] * $request->height[$counter]);

            $firstDeduction = $request->deduction_length_one[$counter] * $request->deduction_height_one[$counter];
            $secondDeduction = $request->deduction_length_two[$counter] * $request->deduction_height_two[$counter];

            $subTotalDeduction = ($firstDeduction + $secondDeduction);

            $subTotalArea = (($area - $subTotalDeduction) * $request->side[$counter]) * $request->code_nos[$counter];

            PaintConfigureProduct::create([
                'paint_configure_id' => $paintConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'estimate_floor_unit_id' => $request->estimate_floor_unit,
                'unit_section_id' => $reqProduct,
                'wall_direction' => $request->wall_direction[$counter],
                'paint_type' => $request->paint_type[$counter],
                'length' => $request->length[$counter],
                'height' => $request->height[$counter],
                'deduction_length_one' => $request->deduction_length_one[$counter],
                'deduction_height_one' => $request->deduction_height_one[$counter],
                'deduction_length_two' => $request->deduction_length_two[$counter],
                'deduction_height_two' => $request->deduction_height_two[$counter],
                'side' => $request->side[$counter],
                'code_nos' => $request->code_nos[$counter],
                'sub_total_deduction' => $subTotalDeduction,
                'sub_total_area' => $subTotalArea,
                'sub_total_paint_liter' => $subTotalArea * $request->color_paint_per_cft,
                'sub_total_seller_liter' => $subTotalArea * $request->seller_paint_per_cft,
            ]);

            $totalArea += $subTotalArea;
            $totalPaintLiter += $subTotalArea * $request->color_paint_per_cft;
            $totalSellerLiter += $subTotalArea * $request->seller_paint_per_cft;
            $counter++;
        }

        $paintConfigure->total_area_with_floor = $totalArea * $request->floor_number;
        $paintConfigure->total_area_without_floor = $totalArea;
        $paintConfigure->total_paint_liter_with_floor = $totalPaintLiter * $request->floor_number;
        $paintConfigure->total_paint_cost = ($totalPaintLiter * $request->floor_number) * $request->paint_costing;
        $paintConfigure->total_paint_liter_without_floor = $totalPaintLiter;

        $paintConfigure->total_seller_liter_with_floor = $totalSellerLiter * $request->floor_number;
        $paintConfigure->total_seller_cost = ($totalSellerLiter * $request->floor_number) * $request->seller_costing;
        $paintConfigure->total_seller_liter_without_floor = $totalSellerLiter;
        $paintConfigure->save();

        return redirect()->route('paint_configure.details', ['paintConfigure' => $paintConfigure->id]);
    }

    public function paintConfigureDetails(PaintConfigure $paintConfigure){
        return view('estimate.paint_configure.details',compact('paintConfigure'));
    }
    public function paintConfigurePrint(PaintConfigure $paintConfigure){
        return view('estimate.paint_configure.print',compact('paintConfigure'));
    }

    public function paintConfigureDatatable() {
        $query = PaintConfigure::with('project','estimateFloor','estimateFloorUnit');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(PaintConfigure $paintConfigure) {
                return $paintConfigure->project->name??'';
            })
            ->addColumn('estimate_floor', function(PaintConfigure $paintConfigure) {
                return $paintConfigure->estimateFloor->name??'';
            })
            ->addColumn('estimate_floor_unit', function(PaintConfigure $paintConfigure) {
                return $paintConfigure->estimateFloorUnit->name??'';
            })
            ->addColumn('action', function(PaintConfigure $paintConfigure) {

                return '<a href="'.route('paint_configure.details', ['paintConfigure' => $paintConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(PaintConfigure $paintConfigure) {
                return $paintConfigure->date;
            })
            ->rawColumns(['action','configure_type'])
            ->toJson();
    }
}
