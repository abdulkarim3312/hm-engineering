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
            'date' => 'required',
            'note' => 'nullable',
        ]);

        $paintConfigure = new PaintConfigure();
        $paintConfigure->estimate_project_id = $request->estimate_project;
        $paintConfigure->estimate_floor_id = $request->estimate_floor;
        $paintConfigure->estimate_floor_unit_id = $request->estimate_floor_unit;
        $paintConfigure->main_paint_type = $request->main_paint_type;
        $paintConfigure->date = $request->date;
        $paintConfigure->note = $request->note;
        $paintConfigure->total_polish = 0;
        $paintConfigure->total_inside = 0;
        $paintConfigure->total_outside = 0;
        $paintConfigure->total_putty = 0;
        $paintConfigure->total_putty_area = 0;
        $paintConfigure->total_outside_area = 0;
        $paintConfigure->total_inside_area = 0;
        $paintConfigure->total_polish_area = 0;

        $paintConfigure->save();
        $paintConfigure->paint_configure_no = str_pad($paintConfigure->id, 5, "0", STR_PAD_LEFT);
        $paintConfigure->save();

        if($request->main_paint_type == 1){
            $counter = 0;
            $totalPolishArea = 0;
            $totalPolishLiter = 0;
            foreach ($request->product as $key => $reqProduct) {

                $area = ($request->length[$counter] * $request->height[$counter]);

                $firstDeduction = $request->deduction_length_one[$counter] * $request->deduction_height_one[$counter];
                $secondDeduction = $request->deduction_length_two[$counter] * $request->deduction_height_two[$counter];

                $subTotalDeduction = ($firstDeduction + $secondDeduction);

                $subTotalArea = (($area - $subTotalDeduction) * $request->side[$counter]) * $request->code_nos[$counter];
                $subTotalPaint = ((($area * $request->quantity[$counter] * $request->price[$counter]) - $subTotalDeduction) * $request->side[$counter]) * $request->code_nos[$counter];
                PaintConfigureProduct::create([
                    'paint_configure_id' => $paintConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'estimate_floor_id' => $request->estimate_floor,
                    'estimate_floor_unit_id' => $request->estimate_floor_unit,
                    'unit_section_id' => $reqProduct,
                    'wall_direction' => $request->wall_direction[$counter],
                    'polish_type' => $request->polish_paint_type[$counter],
                    'unit' => $request->unit[$counter],
                    'quantity' => $request->quantity[$counter],
                    'price' => $request->price[$counter],
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
                    'sub_total_paint_liter' => $subTotalPaint,
                    'status' => 1
                ]);

                $totalPolishArea += $subTotalArea;
                $totalPolishLiter += $subTotalPaint;
                $counter++;
            }
            $paintConfigure->total_polish_area = $totalPolishArea;
            $paintConfigure->total_polish = $totalPolishLiter;
        }elseif($request->main_paint_type == 2){
            $counter = 0;
            $totalInsideArea = 0;
            $totalInsideLiter = 0;
            foreach ($request->product_inside as $key => $reqProduct) {

                $area = ($request->length_inside[$counter] * $request->height_inside[$counter]);

                $firstDeduction = $request->deduction_length_one_inside[$counter] * $request->deduction_height_one_inside[$counter];
                $secondDeduction = $request->deduction_length_two_inside[$counter] * $request->deduction_height_two_inside[$counter];

                $subTotalDeduction = ($firstDeduction + $secondDeduction);

                $subTotalArea = (($area - $subTotalDeduction) * $request->side_inside[$counter]) * $request->code_nos_inside[$counter];
                $subTotalPaint = ((($area * $request->quantity_inside[$counter] * $request->price_inside[$counter]) - $subTotalDeduction) * $request->side_inside[$counter]) * $request->code_nos_inside[$counter];
                PaintConfigureProduct::create([
                    'paint_configure_id' => $paintConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'estimate_floor_id' => $request->estimate_floor,
                    'estimate_floor_unit_id' => $request->estimate_floor_unit,
                    'unit_section_id' => $reqProduct,
                    'wall_direction' => $request->wall_direction_inside[$counter],
                    'inside_type' => $request->inside_paint_type[$counter],
                    'unit' => $request->unit_inside[$counter],
                    'quantity' => $request->quantity_inside[$counter],
                    'price' => $request->price_inside[$counter],
                    'length' => $request->length_inside[$counter],
                    'height' => $request->height_inside[$counter],
                    'deduction_length_one' => $request->deduction_length_one_inside[$counter],
                    'deduction_height_one' => $request->deduction_height_one_inside[$counter],
                    'deduction_length_two' => $request->deduction_length_two_inside[$counter],
                    'deduction_height_two' => $request->deduction_height_two_inside[$counter],
                    'side' => $request->side_inside[$counter],
                    'code_nos' => $request->code_nos_inside[$counter],
                    'sub_total_deduction' => $subTotalDeduction,
                    'sub_total_area' => $subTotalArea,
                    'sub_total_paint_liter' => $subTotalPaint,
                    'status' => 2
                ]);

                $totalInsideArea += $subTotalArea;
                $totalInsideLiter += $subTotalPaint;
                $counter++;
            }
            $paintConfigure->total_inside_area = $totalInsideArea;
            $paintConfigure->total_inside = $totalInsideLiter;
        }elseif($request->main_paint_type == 3){
            $counter = 0;
            $totalOutsideArea = 0;
            $totalOutsideLiter = 0;
            foreach ($request->product_outside as $key => $reqProduct) {

                $area = ($request->length_outside[$counter] * $request->height_outside[$counter]);

                $firstDeduction = $request->deduction_length_one_outside[$counter] * $request->deduction_height_one_outside[$counter];
                $secondDeduction = $request->deduction_length_two_outside[$counter] * $request->deduction_height_two_outside[$counter];

                $subTotalDeduction = ($firstDeduction + $secondDeduction);

                $subTotalArea = (($area - $subTotalDeduction) * $request->side_outside[$counter]) * $request->code_nos_outside[$counter];
                $subTotalPaint = ((($area * $request->quantity_outside[$counter] * $request->price_outside[$counter]) - $subTotalDeduction) * $request->side_outside[$counter]) * $request->code_nos_outside[$counter];
                PaintConfigureProduct::create([
                    'paint_configure_id' => $paintConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'estimate_floor_id' => $request->estimate_floor,
                    'estimate_floor_unit_id' => $request->estimate_floor_unit,
                    'unit_section_id' => $reqProduct,
                    'wall_direction' => $request->wall_direction_outside[$counter],
                    'outside_type' => $request->outside_paint_type[$counter],
                    'unit' => $request->unit_outside[$counter],
                    'quantity' => $request->quantity_outside[$counter],
                    'price' => $request->price_outside[$counter],
                    'length' => $request->length_outside[$counter],
                    'height' => $request->height_outside[$counter],
                    'deduction_length_one' => $request->deduction_length_one_outside[$counter],
                    'deduction_height_one' => $request->deduction_height_one_outside[$counter],
                    'deduction_length_two' => $request->deduction_length_two_outside[$counter],
                    'deduction_height_two' => $request->deduction_height_two_outside[$counter],
                    'side' => $request->side_outside[$counter],
                    'code_nos' => $request->code_nos_outside[$counter],
                    'sub_total_deduction' => $subTotalDeduction,
                    'sub_total_area' => $subTotalArea,
                    'sub_total_paint_liter' => $subTotalPaint,
                    'status' => 3
                ]);

                $totalOutsideArea += $subTotalArea;
                $totalOutsideLiter += $subTotalArea;
                $counter++;
            }
            $paintConfigure->total_outside_area = $totalOutsideArea;
            $paintConfigure->total_outside = $totalOutsideLiter;
        }else{
            $counter = 0;
            $totalPuttyArea = 0;
            $totalPuttyPaint = 0;
            foreach ($request->product_putty as $key => $reqProduct) {

                $area = ($request->length_putty[$counter] * $request->height_putty[$counter]);

                $firstDeduction = $request->deduction_length_one_putty[$counter] * $request->deduction_height_one_putty[$counter];
                $secondDeduction = $request->deduction_length_two_putty[$counter] * $request->deduction_height_two_putty[$counter];

                $subTotalDeduction = ($firstDeduction + $secondDeduction);

                $subTotalArea = (($area - $subTotalDeduction) * $request->side_putty[$counter]) * $request->code_nos_putty[$counter];
                $subTotalPaint = ((($area * $request->quantity_putty[$counter] * $request->price_putty[$counter]) - $subTotalDeduction) * $request->side_putty[$counter]) * $request->code_nos_putty[$counter];
                PaintConfigureProduct::create([
                    'paint_configure_id' => $paintConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'estimate_floor_id' => $request->estimate_floor,
                    'estimate_floor_unit_id' => $request->estimate_floor_unit,
                    'unit_section_id' => $reqProduct,
                    'wall_direction' => $request->wall_direction_putty[$counter],
                    'putty_type' => $request->putty_paint_type[$counter],
                    'unit' => $request->unit_putty[$counter],
                    'quantity' => $request->quantity_putty[$counter],
                    'price' => $request->price_putty[$counter],
                    'length' => $request->length_putty[$counter],
                    'height' => $request->height_putty[$counter],
                    'deduction_length_one' => $request->deduction_length_one_putty[$counter],
                    'deduction_height_one' => $request->deduction_height_one_putty[$counter],
                    'deduction_length_two' => $request->deduction_length_two_putty[$counter],
                    'deduction_height_two' => $request->deduction_height_two_putty[$counter],
                    'side' => $request->side_putty[$counter],
                    'code_nos' => $request->code_nos_putty[$counter],
                    'sub_total_deduction' => $subTotalDeduction,
                    'sub_total_area' => $subTotalArea,
                    'sub_total_paint_liter' => $subTotalPaint,
                    'status' => 4
                ]);

                $totalPuttyArea += $subTotalArea;
                $totalPuttyPaint += $subTotalPaint;
                $counter++;
            }
            $paintConfigure->total_putty_area = $totalPuttyArea;
            $paintConfigure->total_putty = $totalPuttyPaint;
        }
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
            ->addColumn('main_paint_type', function(PaintConfigure $paintConfigure) {
                $btn = '';
                if($paintConfigure->main_paint_type == 1){
                    $btn = '<span class="badge badge-dark" style="background: #8f1ec9; font-size: 14px;">Polish Work</span>';
                }else if($paintConfigure->main_paint_type == 2){
                    $btn .= '<span class="badge badge-success" style="background: #04D89D; font-size: 14px;">Inside Work</span>';
                }else if($paintConfigure->main_paint_type == 3){
                    $btn .= '<span class="badge badge-warning" style="background: #FFC107; color:#000000; font-size: 14px;">Outside Work</span>';
                }else{
                    $btn .= '<span class="badge badge-info" style="background: #65db1b; color:#000000; font-size: 14px;">Putty Work</span>';
                }
                return $btn;
            })
            ->addColumn('total_area', function(PaintConfigure $paintConfigure) {
                $btn = '';
                if($paintConfigure->main_paint_type == 1){
                    $btn = $paintConfigure->total_polish_area;
                }else if($paintConfigure->main_paint_type == 2){
                    $btn .= $paintConfigure->total_inside_area;
                }else if($paintConfigure->main_paint_type == 3){
                    $btn .= $paintConfigure->total_outside_area;
                }else{
                    $btn .= $paintConfigure->total_putty_area;
                }
                return $btn;
            })
            ->addColumn('action', function(PaintConfigure $paintConfigure) {

                return '<a href="'.route('paint_configure.details', ['paintConfigure' => $paintConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(PaintConfigure $paintConfigure) {
                return $paintConfigure->date;
            })
            ->rawColumns(['action','configure_type','main_paint_type','total_area'])
            ->toJson();
    }
}
