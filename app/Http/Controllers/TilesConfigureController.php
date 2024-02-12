<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\GrillGlassTilesConfigure;
use App\Models\GrillGlassTilesConfigureProduct;
use App\Models\EstimateFloorUnit;
use App\Models\UnitSection;
use App\Models\TilesConfigure;
use App\Models\TilesConfigureProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TilesConfigureController extends Controller
{
    public function tilesConfigure() {
        return view('estimate.tiles_configure.all');
    }

    public function tilesConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        $grillGlassTilesCost = GrillGlassTilesConfigure::orderBy('id','desc')->first();
        return view('estimate.tiles_configure.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections','grillGlassTilesCost'));
    }

    public function tilesConfigureAddPost(Request $request) {
        $request->validate([
            'configure_type' => 'required',
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'floor_number' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
            'grill_costing' => 'required|numeric|min:0',
            'tiles_glass_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|numeric|min:0',
        ]);

        $grillGlassTilesConfigure = new TilesConfigure();
        $grillGlassTilesConfigure->configure_type = $request->configure_type;
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
                $subTotalArea = ((($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]) * 1.5 );
                }else{
                $subTotalArea = (($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]);
            }

            TilesConfigureProduct::create([
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
                $totalArea += ((($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]) * 1.5 );
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
            $grillGlassTilesConfigure->total_tiles_glass_cost = ($totalArea * $request->floor_number) * $request->tiles_glass_costing;
        }
        $grillGlassTilesConfigure->save();

        return redirect()->route('tiles_configure.details', ['tilesConfigure' => $grillGlassTilesConfigure->id]);
        // return redirect()->back();
    }

    public function tilesConfigureDetails(TilesConfigure $tilesConfigure){
        return view('estimate.tiles_configure.details',compact('tilesConfigure'));
    }
    public function tilesConfigurePrint(TilesConfigure $tilesConfigure){
        return view('estimate.tiles_configure.print',compact('tilesConfigure'));
    }

    public function tilesConfigureDatatable() {
        $query = TilesConfigure::with('project','estimateFloor','estimateFloorUnit');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(TilesConfigure $tilesConfigure) {
                return $tilesConfigure->project->name??'';
            })
            ->addColumn('estimate_floor', function(TilesConfigure $tilesConfigure) {
                return $tilesConfigure->estimateFloor->name??'';
            })
            ->addColumn('estimate_floor_unit', function(TilesConfigure $tilesConfigure) {
                return $tilesConfigure->estimateFloorUnit->name??'';
            })
            ->addColumn('configure_type', function(TilesConfigure $tilesConfigure) {
                if ($tilesConfigure->configure_type == 1){
                    return '<span class="label label-success">Grill</span>' ;
                }elseif ($tilesConfigure->configure_type == 2){
                    return '<span class="label label-info">Glass</span>' ;
                }else{
                    return '<span class="label label-success">Tiles</span>' ;
                }
            })
            ->addColumn('action', function(TilesConfigure $tilesConfigure) {

                return '<a href="'.route('tiles_configure.details', ['tilesConfigure' => $tilesConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(TilesConfigure $tilesConfigure) {
                return $tilesConfigure->date;
            })
            ->rawColumns(['action','configure_type'])
            ->toJson();
    }
}
