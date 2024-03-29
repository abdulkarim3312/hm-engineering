<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\GrillGlassTilesConfigure;
use App\Models\GrillGlassTilesConfigureProduct;
use App\Models\EstimateFloorUnit;
use App\Models\UnitSection;
use App\Models\GlassConfigure;
use App\Models\GlassConfigureProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GlassConfigureController extends Controller
{
    public function glassConfigure() {
        return view('estimate.glass_configure.all');
    }

    public function glassConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        $grillGlassTilesCost = GrillGlassTilesConfigure::orderBy('id','desc')->first();
        return view('estimate.glass_configure.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections','grillGlassTilesCost'));
    }

    public function glassConfigureAddPost(Request $request) {
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
            'thai_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|numeric|min:0',
        ]);

        $grillGlassTilesConfigure = new GlassConfigure();
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
        $grillGlassTilesConfigure->thai_costing = $request->thai_costing;
        $grillGlassTilesConfigure->tiles_glass_costing = $request->tiles_glass_costing;
        //Total Price
        $grillGlassTilesConfigure->total_grill_cost = 0;
        $grillGlassTilesConfigure->total_tiles_glass_cost = 0;

        $grillGlassTilesConfigure->save();
        $grillGlassTilesConfigure->grill_glass_tiles_configure_no = str_pad($grillGlassTilesConfigure->id, 5, "0", STR_PAD_LEFT);
        $grillGlassTilesConfigure->save();

        $counter = 0;
        $totalArea = 0;
        $totalRft = 0;
        foreach ($request->product as $key => $reqProduct) {

            
            $subTotalArea = (($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]);
            $subTotalRft = (((($request->length[$counter] + $request->height[$counter])) * 2) * $request->quantity[$counter]);
            

            GlassConfigureProduct::create([
                'grill_glass_tiles_configure_id' => $grillGlassTilesConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'estimate_floor_unit_id' => $request->estimate_floor_unit,
                'unit_section_id' => $reqProduct,
                'length' => $request->length[$counter],
                'height' => $request->height[$counter],
                'quantity' => $request->quantity[$counter],
                'sub_total_area' => $subTotalArea,
                'sub_total_rft' => $subTotalRft,
            ]);

            $totalArea += (($request->length[$counter] * $request->height[$counter]) * $request->quantity[$counter]);
            // $counter++;
            $totalRft += $subTotalRft;
            $counter++;
        }

        $grillGlassTilesConfigure->total_area_with_floor = $totalArea * $request->floor_number;
        $grillGlassTilesConfigure->total_rft_with_floor = $totalRft * $request->floor_number;
        $grillGlassTilesConfigure->total_area_without_floor = $totalArea;
        $grillGlassTilesConfigure->total_grill_cost = ($totalArea * $request->floor_number) * $request->grill_costing;
        $grillGlassTilesConfigure->total_thai_cost = $totalRft * $request->thai_costing;
        
        $grillGlassTilesConfigure->save();

        return redirect()->route('glass_configure.details', ['glassConfigure' => $grillGlassTilesConfigure->id]);
        // return redirect()->back();
    }

    public function glassConfigureDetails(GlassConfigure $glassConfigure){
        return view('estimate.glass_configure.details',compact('glassConfigure'));
    }
    public function glassConfigurePrint(GlassConfigure $glassConfigure){
        return view('estimate.glass_configure.print',compact('glassConfigure'));
    }

    public function glassConfigureDelete(GlassConfigure $glassConfigure){
        GlassConfigure::find($glassConfigure->id)->delete();
        GlassConfigureProduct::where('grill_glass_tiles_configure_id', $glassConfigure->id)->delete();
        return redirect()->route('glass_configure')->with('message', 'Glass Info Deleted Successfully.');
    }

    public function glassConfigureDatatable() {
        $query = GlassConfigure::with('project','estimateFloor','estimateFloorUnit');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(GlassConfigure $glassConfigure) {
                return $glassConfigure->project->name ??'';
            })
            ->addColumn('estimate_floor', function(GlassConfigure $glassConfigure) {
                return $glassConfigure->estimateFloor->name ??'';
            })
            ->addColumn('estimate_floor_unit', function(GlassConfigure $glassConfigure) {
                return $glassConfigure->estimateFloorUnit->name ??'';
            })
            ->addColumn('configure_type', function(GlassConfigure $glassConfigure) {
                return $glassConfigure->configure_type ?? '';
            })
            ->addColumn('action', function(GlassConfigure $glassConfigure) {
                $btn = '';
                $btn = '<a href="'.route('glass_configure.details', ['glassConfigure' => $glassConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('glass_configure.delete', ['glassConfigure' => $glassConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(GlassConfigure $glassConfigure) {
                return $glassConfigure->date;
            })
            ->rawColumns(['action','configure_type'])
            ->toJson();
    }
}
