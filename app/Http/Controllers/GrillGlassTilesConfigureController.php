<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\GrillGlassTilesConfigure;
use App\Models\GrillGlassTilesConfigureProduct;
use App\Models\EstimateFloorUnit;
use App\Models\UnitSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GrillGlassTilesConfigureController extends Controller
{
    public function grillGlassTilesConfigure() {
        return view('estimate.grill_glass_tiles_configure.all');
    }

    public function grillGlassTilesConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        $grillGlassTilesCost = GrillGlassTilesConfigure::orderBy('id','desc')->first();
        return view('estimate.grill_glass_tiles_configure.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections','grillGlassTilesCost'));
    }

    public function grillGlassTilesConfigureAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'configure_type' => 'required',
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'floor_number' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
            'grill_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|numeric|min:0',
        ]);

        $grillGlassTilesConfigure = new GrillGlassTilesConfigure();
        $grillGlassTilesConfigure->configure_type = $request->configure_type;
        $grillGlassTilesConfigure->grill_size = $request->grill_size;
        $grillGlassTilesConfigure->estimate_project_id = $request->estimate_project;
        $grillGlassTilesConfigure->estimate_floor_id = $request->estimate_floor;
        $grillGlassTilesConfigure->estimate_floor_unit_id = $request->estimate_floor_unit;
        $grillGlassTilesConfigure->floor_number = $request->floor_number;
        $grillGlassTilesConfigure->date = $request->date;
        $grillGlassTilesConfigure->note = $request->note;
        $grillGlassTilesConfigure->total_area_with_floor = 0;
        $grillGlassTilesConfigure->total_area_without_floor = 0;
        //Price
        $grillGlassTilesConfigure->grill_costing = $request->grill_costing;
        $grillGlassTilesConfigure->tiles_glass_costing = $request->tiles_glass_costing;
        //Total Price
        $grillGlassTilesConfigure->total_grill_cost = 0;
        $grillGlassTilesConfigure->total_tiles_glass_cost = 0;

        $grillGlassTilesConfigure->save();
        $grillGlassTilesConfigure->grill_glass_tiles_configure_no = str_pad($grillGlassTilesConfigure->id, 5, "0", STR_PAD_LEFT);
        $grillGlassTilesConfigure->save();

        $counter = 0;
        $totalArea = 0;
        foreach ($request->product as $key => $reqProduct) {

            if ($request->configure_type == 1){
                $subTotalArea = ((($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]));
                }else{
                $subTotalArea = (($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]);
            }

            GrillGlassTilesConfigureProduct::create([
                'grill_glass_tiles_configure_id' => $grillGlassTilesConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'estimate_floor_unit_id' => $request->estimate_floor_unit,
                'unit_section_id' => $reqProduct,
                'length' => $request->length[$counter],
                'height' => $request->height[$counter],
                'quantity' => $request->quantity[$counter],
                'sub_total_area' => $subTotalArea,
            ]);

            if ($request->configure_type == 1){
                $totalArea += ((($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]));
            }else{
                $totalArea += (($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]);
            }
            $counter++;
        }

        $grillGlassTilesConfigure->total_area_with_floor = $totalArea * $request->floor_number;
        $grillGlassTilesConfigure->total_area_without_floor = $totalArea;
        if ($request->configure_type == 1) {
            $grillGlassTilesConfigure->total_grill_cost = ($totalArea * $request->floor_number) * $request->grill_costing;
        }else{
            $grillGlassTilesConfigure->total_tiles_glass_cost = ($totalArea * $request->floor_number) * $request->grill_costing;
        }
        $grillGlassTilesConfigure->save();

        return redirect()->route('grill_glass_tiles_configure.details', ['grillGlassTilesConfigure' => $grillGlassTilesConfigure->id]);
    }

    public function grillGlassTilesConfigureDetails(GrillGlassTilesConfigure $grillGlassTilesConfigure){
        return view('estimate.grill_glass_tiles_configure.details',compact('grillGlassTilesConfigure'));
    }
    public function grillGlassTilesConfigurePrint(GrillGlassTilesConfigure $grillGlassTilesConfigure){
        return view('estimate.grill_glass_tiles_configure.print',compact('grillGlassTilesConfigure'));
    }

    public function grillGlassTilesConfigureDatatable() {
        $query = GrillGlassTilesConfigure::with('project','estimateFloor','estimateFloorUnit');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {
                return $grillGlassTilesConfigure->project->name??'';
            })
            ->addColumn('estimate_floor', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {
                return $grillGlassTilesConfigure->estimateFloor->name??'';
            })
            ->addColumn('estimate_floor_unit', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {
                return $grillGlassTilesConfigure->estimateFloorUnit->name??'';
            })
            ->addColumn('configure_type', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {
                return $grillGlassTilesConfigure->configure_type?? '';
                  
            })
            ->addColumn('grill_size', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {
                return $grillGlassTilesConfigure->grill_size?? '';
                  
            })
            ->addColumn('action', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {

                return '<a href="'.route('grill_glass_tiles_configure.details', ['grillGlassTilesConfigure' => $grillGlassTilesConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(GrillGlassTilesConfigure $grillGlassTilesConfigure) {
                return $grillGlassTilesConfigure->date;
            })
            ->rawColumns(['action','configure_type'])
            ->toJson();
    }
}
