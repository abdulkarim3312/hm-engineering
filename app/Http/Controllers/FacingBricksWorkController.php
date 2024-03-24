<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\EstimateFloorUnit;
use App\Models\FacingBricksWork;
use App\Models\FacingBricksWorkProduct;
use App\Models\UnitSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class FacingBricksWorkController extends Controller
{
    public function facingBricksAll() {
        return view('estimate.facing_bricks_work.all');
    }

    public function facingBricksAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        return view('estimate.facing_bricks_work.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections'));
    }

    public function facingBricksAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'brick_price' => 'required|numeric|min:0',
            'silicon_price' => 'required|numeric|min:0',

            'product.*' => 'required',
            'wall_direction.*' => 'required',
            'facing_brick.*' => 'required',
        ]);

        $facingBricks = new FacingBricksWork();
        $facingBricks->estimate_project_id = $request->estimate_project;
        $facingBricks->estimate_floor_id = $request->estimate_floor;
        $facingBricks->estimate_floor_unit_id = $request->estimate_floor_unit;
        $facingBricks->total_area_with_floor = 0;
        $facingBricks->date = $request->date;
        $facingBricks->note = $request->note;
        //Price
        $facingBricks->brick_price = $request->brick_price;
        $facingBricks->silicon_price = $request->silicon_price;
        
        //Total Price
        $facingBricks->total_bricks_cost = 0;
        $facingBricks->total_silicon_cost = 0;

        $facingBricks->save();

        $counter = 0;
        $totalArea = 0;
        $totalBricks = 0;
        $totalSilicon = 0;
        foreach ($request->product as $key => $reqProduct) {
            
            $area = ($request->length[$counter] * $request->height[$counter] * $request->quantity[$counter]) ;
            $subArea = ($request->length[$counter] * $request->height[$counter]) ;

            $firstDeduction = $request->deduction_length_one[$counter] * $request->deduction_height_one[$counter];
            $secondDeduction = $request->deduction_length_two[$counter] * $request->deduction_height_two[$counter];

            $subTotalDeduction = ($firstDeduction + $secondDeduction);
            if($request->brick_price){
                $subTotalArea = (($area - $subTotalDeduction) * $request->side[$counter]);
            }
            if($request->silicon_price){
                $subTotalArea = (($area - $subTotalDeduction) * $request->side[$counter]);
            }
            
            FacingBricksWorkProduct::create([
                'paint_configure_id' => $facingBricks->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'estimate_floor_unit_id' => $request->estimate_floor_unit,
                'unit_section_id' => $reqProduct,
                'wall_direction' => $request->wall_direction[$counter],
                'facing_brick' => $request->facing_brick[$counter],
                'quantity' => $request->quantity[$counter],
                'length' => $request->length[$counter],
                'height' => $request->height[$counter],
                'deduction_length_one' => $request->deduction_length_one[$counter],
                'deduction_height_one' => $request->deduction_height_one[$counter],
                'deduction_length_two' => $request->deduction_length_two[$counter],
                'deduction_height_two' => $request->deduction_height_two[$counter],
                'side' => $request->side[$counter],
                'sub_total_deduction' => $subTotalDeduction,
                'sub_total_area' => $subArea,
                'sub_total_bricks' => $subTotalArea,
                'total_bricks_cost' => $subTotalArea * $request->brick_price,
            ]);

            $totalArea += $subTotalArea;
            $totalBricks += $subTotalArea;
            $counter++;
        }

        $facingBricks->total_area_with_floor = $totalArea;
        $facingBricks->total_bricks_cost = $totalBricks * $request->brick_price;
        $facingBricks->save();

        return redirect()->route('facing_bricks_work.details', ['facingBricks' => $facingBricks->id]);
    }

    public function facingBricksDetails(FacingBricksWork $facingBricks){
        return view('estimate.facing_bricks_work.details',compact('facingBricks'));
    }
    public function facingBricksPrint(FacingBricksWork $facingBricks){
        return view('estimate.facing_bricks_work.print',compact('facingBricks'));
    }

    public function facingBricksDatatable() {
        $query = FacingBricksWork::with('project','estimateFloor','estimateFloorUnit');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(FacingBricksWork $facingBricks) {
                return $facingBricks->project->name??'';
            })
            ->addColumn('estimate_floor', function(FacingBricksWork $facingBricks) {
                return $facingBricks->estimateFloor->name??'';
            })
            ->addColumn('estimate_floor_unit', function(FacingBricksWork $facingBricks) {
                return $facingBricks->estimateFloorUnit->name??'';
            })
            ->addColumn('action', function(FacingBricksWork $facingBricks) {

                return '<a href="'.route('facing_bricks_work.details', ['facingBricks' => $facingBricks->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(FacingBricksWork $facingBricks) {
                return $facingBricks->date;
            })
            ->rawColumns(['action','configure_type'])
            ->toJson();
    }
}
