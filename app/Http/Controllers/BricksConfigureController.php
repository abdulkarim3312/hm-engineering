<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\CommonConfigure;
use App\Models\EstimateFloorUnit;
use App\Models\BricksConfigure;
use App\Models\BricksConfigureProduct;
use App\Models\UnitSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BricksConfigureController extends Controller
{
    public function bricksConfigure() {
        return view('estimate.bricks_configure.all');
    }

    public function bricksConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $unitSections = UnitSection::where('status',1)->get();
        $commonCost = CommonConfigure::orderBy('id','desc')->first();
        return view('estimate.bricks_configure.add',compact('estimateProjects',
            'estimateFloorUnits','unitSections','commonCost'));
    }
    public function bricksConfigureAddPost(Request $request) {
        // dd($request->all());

        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'floor_number' => 'required',
            'brick_size' => 'required|numeric|min:0',
            'morter' => 'required|numeric|min:0',
            'dry_morter' => 'required|numeric|min:0',
            'first_ratio' => 'required|numeric|min:1',
            'second_ratio' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',

            'bricks_costing' => 'required|numeric|min:0',
            'bricks_cement_costing' => 'required|numeric|min:0',
            'bricks_sands_costing' => 'required|numeric|min:0',

            'product.*' => 'required',
            'wall_direction.*' => 'required',
            'length.*' => 'required|numeric|min:0',
            'height.*' => 'required|numeric|min:0',
            'wall_number.*' => 'required|numeric|min:0',
            'deduction_length_one.*' => 'required|numeric|min:0',
            'deduction_height_one.*' => 'required|numeric|min:0',
            'deduction_length_two.*' => 'required|numeric|min:0',
            'deduction_height_two.*' => 'required|numeric|min:0',
            'deduction_length_three.*' => 'required|numeric|min:0',
            'deduction_height_three.*' => 'required|numeric|min:0',
        ]);

        $bricksConfigure = new BricksConfigure();
        $bricksConfigure->estimate_project_id = $request->estimate_project;
        $bricksConfigure->estimate_floor_id = $request->estimate_floor;
        $bricksConfigure->estimate_floor_unit_id = $request->estimate_floor_unit;
        $bricksConfigure->wall_type = $request->wall_type;
        $bricksConfigure->floor_number = $request->floor_number;
        $bricksConfigure->brick_size = $request->brick_size * $request->floor_number;
        $bricksConfigure->morter = $request->morter * $request->floor_number;
        $bricksConfigure->dry_morter = $request->dry_morter * $request->floor_number;
        $bricksConfigure->first_ratio = $request->first_ratio;
        $bricksConfigure->second_ratio = $request->second_ratio;
        $bricksConfigure->date = $request->date;
        $bricksConfigure->note = $request->note;
        $bricksConfigure->total_bricks = 0;
        $bricksConfigure->total_morters = 0;
        $bricksConfigure->total_cement = 0;
        $bricksConfigure->total_cement_bag = 0;
        $bricksConfigure->total_sands = 0;
        //price
        $bricksConfigure->bricks_costing = $request->bricks_costing;
        $bricksConfigure->bricks_cement_costing = $request->bricks_cement_costing;
        $bricksConfigure->bricks_sands_costing = $request->bricks_sands_costing;
        //Total Price
        $bricksConfigure->total_bricks_cost = 0;
        $bricksConfigure->total_bricks_cement_cost = 0;
        $bricksConfigure->total_bricks_sands_cost = 0;
        $bricksConfigure->save();
        $bricksConfigure->bricks_configure_no = str_pad($bricksConfigure->id, 5, "0", STR_PAD_LEFT);
        $bricksConfigure->save();

        $totalRatio = ($request->first_ratio + $request->second_ratio);

        $counter = 0;
        $totalBricks = 0;
        $totalMorters = 0;
        $totalCement = 0;
        $totalsands = 0;
        foreach ($request->product as $key => $reqProduct) {

            $area = ($request->length[$counter] * $request->height[$counter]) * $request->wall_number[$counter];
            // $area = ($request->length[$counter] * $request->height[$counter]) * 0.42 * $request->wall_number[$counter];
            // dd($area);
            $firstDeduction = $request->deduction_length_one[$counter] * $request->deduction_height_one[$counter];
            $secondDeduction = $request->deduction_length_two[$counter] * $request->deduction_height_two[$counter];
            $thirdDeduction = $request->deduction_length_three[$counter] * $request->deduction_height_three[$counter];

            $subTotalDeduction = ($firstDeduction + $secondDeduction + $thirdDeduction);

            $totalArea = $area - $subTotalDeduction;

            $subTotalMorters = (($totalArea/$request->brick_size * $request->morter) * $request->dry_morter);
            // $subTotalMorters = ($area * $request->dry_morter);

            $subTotalCement = $subTotalMorters * $request->first_ratio/$totalRatio;
            $subTotalSands = $subTotalMorters * $request->second_ratio/$totalRatio;


            BricksConfigureProduct::create([
                'bricks_configure_id' => $bricksConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'estimate_floor_unit_id' => $request->estimate_floor_unit,
                'unit_section_id' => $reqProduct,
                'wall_direction' => $request->wall_direction[$counter],
                'length' => $request->length[$counter] * $request->floor_number,
                'height' => $request->height[$counter] * $request->floor_number,
                'wall_number' => $request->wall_number[$counter] * $request->floor_number,
                'deduction_length_one' => $request->deduction_length_one[$counter] * $request->floor_number,
                'deduction_height_one' => $request->deduction_height_one[$counter] * $request->floor_number,
                'deduction_length_two' => $request->deduction_length_two[$counter] * $request->floor_number,
                'deduction_height_two' => $request->deduction_height_two[$counter] * $request->floor_number,
                'deduction_length_three' => $request->deduction_length_three[$counter] * $request->floor_number,
                'deduction_height_three' => $request->deduction_height_three[$counter] * $request->floor_number,
                'sub_total_area' => $totalArea * $request->floor_number,
                'sub_total_deduction' => $subTotalDeduction * $request->floor_number,
                'sub_total_cement' => $subTotalCement * $request->floor_number,
                'sub_total_sands' => $subTotalSands * $request->floor_number,
                'sub_total_bricks' => ($totalArea/$request->brick_size) * $request->floor_number,
                'sub_total_morters' => ((($totalArea/$request->brick_size * $request->morter) * $request->dry_morter) * $request->floor_number),
            ]);

            $totalBricks += $totalArea/$request->brick_size;
            $totalMorters += (($totalArea/$request->brick_size * $request->morter) * $request->dry_morter);

            $totalCement += $subTotalCement;
            $totalsands += $subTotalSands;

            $counter++;
        }

        $bricksConfigure->total_bricks = $totalBricks * $request->floor_number;
        $bricksConfigure->total_morters = $totalMorters * $request->floor_number;
        $bricksConfigure->total_cement = $totalCement * $request->floor_number;
        $bricksConfigure->total_sands = $totalsands * $request->floor_number;
        $bricksConfigure->total_cement_bag = ($totalCement * 0.8) * $request->floor_number;

        $bricksConfigure->total_bricks_cost = ($totalBricks * $request->floor_number) * $request->bricks_costing;
        $bricksConfigure->total_bricks_cement_cost = (($totalCement * 0.8) * $request->floor_number) * $request->bricks_cement_costing;
        $bricksConfigure->total_bricks_sands_cost = ($totalsands * $request->floor_number) * $request->bricks_sands_costing;
        $bricksConfigure->save();

        return redirect()->route('bricks_configure.details', ['bricksConfigure' => $bricksConfigure->id]);
    }

    public function bricksConfigureDetails(BricksConfigure $bricksConfigure){
        return view('estimate.bricks_configure.details',compact('bricksConfigure'));
    }
    public function bricksConfigurePrint(BricksConfigure $bricksConfigure){
        return view('estimate.bricks_configure.print',compact('bricksConfigure'));
    }

    public function bricksConfigureDelete(BricksConfigure $bricksConfigure){
        BricksConfigure::find($bricksConfigure->id)->delete();
        BricksConfigureProduct::where('bricks_configure_id', $bricksConfigure->id)->delete();
        return redirect()->route('bricks_configure')->with('message', 'Bricks Info Deleted Successfully.');
    }

    public function bricksConfigureDatatable() {
        $query = BricksConfigure::with('project','estimateFloor','estimateFloorUnit','unitSection');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(BricksConfigure $bricksConfigure) {
                return $bricksConfigure->project->name??'';
            })
            ->addColumn('estimate_floor', function(BricksConfigure $bricksConfigure) {
                return $bricksConfigure->estimateFloor->name??'';
            })
            ->addColumn('estimate_floor_unit', function(BricksConfigure $bricksConfigure) {
                return $bricksConfigure->estimateFloorUnit->name??'';
            })
            ->addColumn('unit_section', function(BricksConfigure $bricksConfigure) {
                return $bricksConfigure->unitSection->name??'';
            })
            ->addColumn('action', function(BricksConfigure $bricksConfigure) {
                $btn = '';
                $btn = '<a href="'.route('bricks_configure.details', ['bricksConfigure' => $bricksConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('bricks_configure.delete', ['bricksConfigure' => $bricksConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(BricksConfigure $bricksConfigure) {
                return $bricksConfigure->date;
            })
            ->addColumn('wall_type', function(BricksConfigure $bricksConfigure) {
                if ($bricksConfigure->wall_type == 1)
                    return '<span class="badge badge-dark" style="background: #8f1ec9; font-size: 14px;">3" wall</span>';
                else if($bricksConfigure->wall_type == 2)
                    return '<span class="badge badge-success" style="background: #04D89D; font-size: 14px;">5" wall</span>';
                else
                    return '<span class="badge badge-warning" style="background: #FFC107; color:#000000; font-size: 14px;">10" wall</span>';
            })
            ->rawColumns(['action', 'wall_type'])
            ->toJson();
    }
}
