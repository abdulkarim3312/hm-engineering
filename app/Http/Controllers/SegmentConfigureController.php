<?php

namespace App\Http\Controllers;

use App\Model\Costing;
use App\Model\EstimateProduct;
use App\Model\EstimateProductCosting;
use App\Model\EstimateProject;
use App\Models\CostingSegment;
use App\Models\PileConfigure;
use App\Models\PileConfigureProduct;
use App\Models\SegmentConfigure;
use App\Models\SegmentConfigureProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SegmentConfigureController extends Controller
{
    public function segmentConfigure() {
        $segmentConfigures = SegmentConfigure::get();
        return view('estimate.segment_configure.all', compact('segmentConfigures'));
    }

    public function segmentConfigureAdd() {
        $estimateProducts = EstimateProduct::where('status',1)->get();
        $costingSegments = CostingSegment::where('status',1)->get();
        return view('estimate.segment_configure.add',compact('estimateProducts','costingSegments'));
    }

    public function segmentConfigureAddPost(Request $request) {

        $request->validate([
            'costing_segment' => 'required',
            'segment_unit_type' => 'required',
            'segment_height' => 'required',
            'segment_width' => 'required',
            'segment_length' => 'required',
            'date' => 'required',
            // 'note' => 'required',
            'product.*' => 'required',
            'minimum_quantity.*' => 'required|numeric|min:0',
        ]);

        $segmentConfigure = new SegmentConfigure();
        $segmentConfigure->costing_segment_id = $request->costing_segment;
        $segmentConfigure->segment_unit_type = $request->segment_unit_type;
        $segmentConfigure->segment_height = $request->segment_height;
        $segmentConfigure->segment_width = $request->segment_width;
        $segmentConfigure->segment_length = $request->segment_length;
        $segmentConfigure->date = $request->date;
        $segmentConfigure->note = $request->note;
        $segmentConfigure->save();
        $segmentConfigure->segment_configure_no = str_pad($segmentConfigure->id, 5, "0", STR_PAD_LEFT);;
        $segmentConfigure->save();

        $counter = 0;
        //$total = 0;
        foreach ($request->product as $reqProduct) {
            $product = EstimateProduct::find($reqProduct);

            SegmentConfigureProduct::create([
                'segment_configure_id' => $segmentConfigure->id,
                'costing_segment_id' => $request->costing_segment,
                'segment_unit_type' => $request->segment_unit_type,
                'estimate_product_id' => $product->id,
                'minimum_quantity' => $request->minimum_quantity[$counter],
                //'unit_price' => $request->unit_price[$counter],
            ]);

            //$total += $product->unit_price * $request->quantity[$counter];
            $counter++;
        }
        //$segmentConfigure->total = $total;
        //$segmentConfigure->save();

        return redirect()->route('segment_configure.details', ['segmentConfigure' => $segmentConfigure->id]);
    }

    public function details(SegmentConfigure $segmentConfigure){
        return view('estimate.segment_configure.details',compact('segmentConfigure'));
    }

    public function segmentConfigureDatatable() {
        $query = SegmentConfigure::with('costingSegment');

        return DataTables::eloquent($query)
            ->addColumn('costing_segment', function(SegmentConfigure $segmentConfigure) {
                return $segmentConfigure->costingSegment->name??'';
            })
            ->addColumn('action', function(SegmentConfigure $segmentConfigure) {

                return '<a href="'.route('segment_configure.details', ['segmentConfigure' => $segmentConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('unit_type', function(SegmentConfigure $segmentConfigure) {
                if ($segmentConfigure->segment_unit_type == 1)
                return '<span class="label label-info">Meter/CM</span>';
                else
                    return '<span class="label label-warning">Feet/Inch</span>';
            })
            ->editColumn('date', function(SegmentConfigure $segmentConfigure) {
                return $segmentConfigure->date;
            })
            ->editColumn('total', function(SegmentConfigure $segmentConfigure) {
                return ' '.number_format($segmentConfigure->total, 2);
            })
            ->rawColumns(['action','unit_type'])
            ->toJson();
    }

    public function segmentConfigureEdit(EstimateProductType $estimateProductType) {
        $estimateProducts = EstimateProduct::where('status',1)->get();
        return view('estimate.product_type.edit', compact( 'estimateProductType','estimateProducts'));
    }

    public function segmentConfigureEditPost(EstimateProductType $estimateProductType, Request $request) {
        $request->validate([
            'estimate_product' => 'required',
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $estimateProductType->estimate_product_id = $request->estimate_product;
        $estimateProductType->name = $request->name;
        $estimateProductType->unit_price = $request->unit_price;
        $estimateProductType->description = $request->description;
        $estimateProductType->status = $request->status;
        $estimateProductType->save();

        return redirect()->route('estimate_product_type')->with('message', 'Estimate product Type edit successfully.');
    }

    public function pileConfigure() {
        return view('estimate.pile_configure.all');
    }

    public function pileConfigureAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        return view('estimate.pile_configure.add',compact('estimateProjects'));
    }

    public function pileConfigureAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'estimate_project' => 'required',
            'course_aggregate_type' => 'required',
            'spiral_interval' => 'required',
            'pile_quantity' => 'required',
            'first_ratio' => 'required',
            'second_ratio' => 'required',
            'third_ratio' => 'required',
            'pile_height' => 'required',
            'radius' => 'required',
            'cover' => 'required',
            'total_volume' => 'required',
            'dry_volume' => 'required',
            'total_dry_volume' => 'required',
            'date' => 'required',
            // 'note' => 'required',
            'pile_bar_costing' => 'required|numeric|min:0',
            'pile_cement_costing' => 'required|numeric|min:0',
            'pile_sands_costing' => 'required|numeric|min:0',
            'pile_aggregate_costing' => 'required|numeric|min:0',
            'pile_picked_costing' => 'required|numeric|min:0',
            'product.*' => 'required',
            'dia.*' => 'required|numeric|min:0',
            'dia_square.*' => 'required|numeric|min:0',
            'value_of_bar.*' => 'required|numeric|min:0',
            'kg_by_rft.*' => 'required|numeric|min:0',
            'kg_by_ton.*' => 'required|numeric|min:0',
            'number_of_bar.*' => 'required|numeric|min:1',
            'spiral_bar' => 'required',
        ]);

        $totalRatio = ($request->first_ratio + $request->second_ratio + $request->third_ratio);

        $totalCementCft = ($request->total_dry_volume * $request->first_ratio/$totalRatio);
        $totalCementBag = (($request->total_dry_volume * $request->first_ratio/$totalRatio) / 1.25);

        $totalSands = ($request->total_dry_volume * $request->second_ratio/$totalRatio);

        $totalAggregate = ($request->total_dry_volume * $request->third_ratio/$totalRatio);
        if ($request->course_aggregate_type == 2){
            $request->pile_aggregate_costing = 0;
            $totalPiked = ($totalAggregate * 11.11);
        }else{
            $request->pile_picked_costing = 0;
            $totalPiked = 0;
        }
       
        $pileConfigure = new PileConfigure();
        $pileConfigure->estimate_project_id = $request->estimate_project;
        $pileConfigure->spiral_bar = $request->spiral_bar * $request->pile_quantity;
        $pileConfigure->spiral_interval = $request->spiral_interval * $request->pile_quantity;
        $pileConfigure->course_aggregate_type = $request->course_aggregate_type;
        $pileConfigure->pile_quantity = $request->pile_quantity;
        $pileConfigure->first_ratio = $request->first_ratio;
        $pileConfigure->second_ratio = $request->second_ratio;
        $pileConfigure->third_ratio = $request->third_ratio;
        $pileConfigure->pile_height = $request->pile_height * $request->pile_quantity;
        $pileConfigure->radius = $request->radius * $request->pile_quantity;
        $pileConfigure->total_volume = $request->total_volume * $request->pile_quantity;
        $pileConfigure->dry_volume = $request->dry_volume * $request->pile_quantity;
        $pileConfigure->total_dry_volume = $request->total_dry_volume * $request->pile_quantity;
        $pileConfigure->cover = $request->cover * $request->pile_quantity;
        $pileConfigure->date = $request->date;
        $pileConfigure->note = $request->note;
        $pileConfigure->total_ton = 0;
        $pileConfigure->total_kg = 0;
        if($request->course_aggregate_type == 1){
            $pileConfigure->total_cement = $totalCementCft * $request->pile_quantity;
            $pileConfigure->total_cement_bag = $totalCementBag * $request->pile_quantity;
            $pileConfigure->total_sands = (($totalSands)/2 * $request->pile_quantity);
            $pileConfigure->total_s_sands =(($totalSands)/2 * $request->pile_quantity);
            $pileConfigure->total_aggregate = $totalAggregate * $request->pile_quantity;
            $pileConfigure->total_picked = 0;
        }else if($request->course_aggregate_type == 2){
            $pileConfigure->total_cement = $totalCementCft * $request->pile_quantity;
            $pileConfigure->total_cement_bag = $totalCementBag * $request->pile_quantity;
            $pileConfigure->total_sands = (($totalSands)/2 * $request->pile_quantity);
            $pileConfigure->total_s_sands =(($totalSands)/2 * $request->pile_quantity);
            $pileConfigure->total_aggregate = 0;
            $pileConfigure->total_picked = $totalPiked * $request->pile_quantity;
        }else{
            $pileConfigure->total_cement = 0;
            $pileConfigure->total_cement_bag = 0;
            $pileConfigure->total_sands = 0;
            $pileConfigure->total_s_sands =0;
            $pileConfigure->total_aggregate = 0;
            $pileConfigure->total_picked = 0;
        }
        //price
        $pileConfigure->pile_bar_per_cost = $request->pile_bar_costing;
        $pileConfigure->pile_cement_per_cost = $request->pile_cement_costing;
        $pileConfigure->pile_sands_per_cost = $request->pile_sands_costing;
        $pileConfigure->s_sands_costing = $request->s_sands_costing;
        $pileConfigure->pile_aggregate_per_cost = $request->pile_aggregate_costing??0;
        $pileConfigure->pile_picked_per_cost = $request->pile_picked_costing??0;
        //Total Price
        if($request->course_aggregate_type == 1){
            $pileConfigure->total_pile_cement_bag_price = ($totalCementBag * $request->pile_quantity) * $request->pile_cement_costing;
            $pileConfigure->total_pile_sands_price = (($totalSands/2) * $request->pile_quantity) * $request->pile_sands_costing;
            $pileConfigure->total_pile_rmc_price = $request->total_volume * $request->pile_sands_costing;
            $pileConfigure->total_pile_s_sands_price = (($totalSands/2) * $request->pile_quantity) * $request->s_sands_costing;
            $pileConfigure->total_pile_aggregate_price = ($totalAggregate * $request->pile_quantity) * $request->pile_aggregate_costing;
            $pileConfigure->total_pile_picked_price = 0;
            $pileConfigure->total_pile_rmc_price = 0;
        }else if($request->course_aggregate_type == 2){
            $pileConfigure->total_pile_cement_bag_price = ($totalCementBag * $request->pile_quantity) * $request->pile_cement_costing;
            $pileConfigure->total_pile_sands_price = (($totalSands/2) * $request->pile_quantity) * $request->pile_sands_costing;
            $pileConfigure->total_pile_rmc_price = $request->total_volume * $request->pile_sands_costing;
            $pileConfigure->total_pile_s_sands_price = (($totalSands/2) * $request->pile_quantity) * $request->s_sands_costing;
            $pileConfigure->total_pile_aggregate_price = 0;
            $pileConfigure->total_pile_picked_price = ($totalPiked * $request->pile_quantity) * $request->pile_picked_costing;
            $pileConfigure->total_pile_rmc_price = 0;
        }else{
            $pileConfigure->total_pile_rmc_price = $request->total_volume * $request->pile_sands_costing;
            $pileConfigure->total_pile_cement_bag_price = 0;
            $pileConfigure->total_pile_sands_price = 0;
            $pileConfigure->total_pile_s_sands_price = 0;
            $pileConfigure->total_pile_aggregate_price = 0;
            $pileConfigure->total_pile_picked_price = 0;
        }
        $pileConfigure->total_pile_bar_price = 0;
        $pileConfigure->save();
        $pileConfigure->pile_configure_no = str_pad($pileConfigure->id, 5, "0", STR_PAD_LEFT);
        $pileConfigure->save();

        $counter = 0;
        $totalTon = 0;
        $totalKg = 0;
        foreach ($request->product as $key => $reqProduct) {
            if ($key == 0){
                PileConfigureProduct::create([
                    'pile_configure_id' => $pileConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'bar_type' => $reqProduct,
                    'dia' => $request->dia[$counter],
                    'dia_square' => $request->dia_square[$counter],
                    'value_of_bar' => $request->value_of_bar[$counter],
                    'kg_by_rft' => $request->kg_by_rft[$counter],
                    'kg_by_ton' => $request->kg_by_ton[$counter],
                    'number_of_bar' => $request->number_of_bar[$counter] * $request->pile_quantity,
                    'lapping_nos' => $request->lapping_nos[$counter] ?? 0 * $request->pile_quantity,
                    'sub_total_kg' => (($request->number_of_bar[$counter] * $request->kg_by_rft[$counter] * $request->pile_height) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter])),
                    'sub_total_ton' => (((($request->kg_by_rft[$counter] * $request->pile_height) * $request->number_of_bar[$counter]) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter])))/$request->kg_by_ton[$counter],
                ]);
            }else{
                PileConfigureProduct::create([
                    'pile_configure_id' => $pileConfigure->id,
                    'estimate_project_id' => $request->estimate_project,
                    'bar_type' => $reqProduct,
                    'dia' => $request->dia[$counter],
                    'dia_square' => $request->dia_square[$counter],
                    'value_of_bar' => $request->value_of_bar[$counter],
                    'kg_by_rft' => $request->kg_by_rft[$counter],
                    'kg_by_ton' => $request->kg_by_ton[$counter],
                    'number_of_bar' => $request->number_of_bar[$counter] * $request->pile_quantity,
                    'lapping_nos' => $request->lapping_nos[$counter] ?? 0 * $request->pile_quantity,
                    'sub_total_kg' => (($request->number_of_bar[$counter] * $request->kg_by_rft[$counter] * $request->pile_height) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter])),
                    'sub_total_ton' => (((($request->kg_by_rft[$counter] * $request->pile_height) * $request->number_of_bar[$counter]) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter])))/$request->kg_by_ton[$counter],
                ]);
            }

            if ($key == 0){
                $totalTon += (((($request->kg_by_rft[$counter] * $request->pile_height) * $request->number_of_bar[$counter]) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter])))/$request->kg_by_ton[$counter];
                $totalKg += (($request->number_of_bar[$counter] * $request->kg_by_rft[$counter] * $request->pile_height) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter]));
            }else{
                $totalTon += (((($request->kg_by_rft[$counter] * $request->pile_height) * $request->number_of_bar[$counter]) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter])))/$request->kg_by_ton[$counter];
                $totalKg += (($request->number_of_bar[$counter] * $request->kg_by_rft[$counter] * $request->pile_height) + ($request->kg_by_rft[$counter] * $request->lapping_nos[$counter] * $request->lapping_lenght[$counter]));
            }
            $counter++;
        }

        $counter = 0;
        $totalKgTie = 0;
        $totalTonTie = 0;
        foreach ($request->tie_product as $key => $reqProduct) {

            PileConfigureProduct::create([
                'pile_configure_id' => $pileConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'tie_bar_type' => $reqProduct,
                'tie_dia' => $request->tie_dia[$counter],
                'tie_dia_square' => $request->tie_dia_square[$counter],
                'tie_value_of_bar' => $request->tie_value_of_bar[$counter],
                'tie_kg_by_rft' => $request->tie_kg_by_rft[$counter],
                'tie_kg_by_ton' => $request->tie_kg_by_ton[$counter],
                'tie_length' => $request->tie_length[$counter] * $request->pile_quantity,
                'tie_lapping_length' => $request->tie_lapping_length[$counter] ?? 0 * $request->costing_segment_quantity,
                'sub_total_kg_tie' => ((($request->tie_kg_by_rft[$counter]) * $request->tie_length[$counter]) + ($request->tie_kg_by_rft[$counter]) * $request->tie_lapping_length[$counter]),
                'sub_total_ton_tie' => ((($request->tie_kg_by_rft[$counter]) * $request->tie_length[$counter]) + ($request->tie_kg_by_rft[$counter]) * $request->tie_lapping_length[$counter]) / $request->tie_kg_by_ton[$counter],
            ]);

            $totalKgTie += ((($request->tie_kg_by_rft[$counter]) * $request->tie_length[$counter]) + ($request->tie_kg_by_rft[$counter]) * $request->tie_lapping_length[$counter]);
            $totalTonTie += ((($request->tie_kg_by_rft[$counter]) * $request->tie_length[$counter]) + ($request->tie_kg_by_rft[$counter]) * $request->tie_lapping_length[$counter]) / $request->tie_kg_by_ton[$counter];

            $counter++;
        }

        $pileConfigure->total_ton = ($totalTon + $totalTonTie) * $request->pile_quantity;
        $pileConfigure->total_kg = ($totalKg + $totalKgTie) * $request->pile_quantity;

        $pileConfigure->total_pile_bar_price =  ($totalKg + $totalKgTie) * $request->pile_bar_costing;
        $pileConfigure->save();

        return redirect()->route('pile_configure.details', ['pileConfigure' => $pileConfigure->id]);
    }

    public function pileConfigureDetails(PileConfigure $pileConfigure){
        return view('estimate.pile_configure.details',compact('pileConfigure'));
    }
    public function pileConfigurePrint(PileConfigure $pileConfigure){
        return view('estimate.pile_configure.print',compact('pileConfigure'));
    }

    public function pileConfigureDelete(PileConfigure $pileConfigure){
        PileConfigure::find($pileConfigure->id)->delete();
        PileConfigureProduct::where('pile_configure_id', $pileConfigure->id)->delete();
        return redirect()->route('pile_configure')->with('message', 'Pile Info Deleted Successfully.');
    }

    public function pileConfigureDatatable() {
        $query = PileConfigure::with('project');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(PileConfigure $pileConfigure) {
                return $pileConfigure->project->name??'';
            })
            ->addColumn('action', function(PileConfigure $pileConfigure) {
                $btn = '';
                $btn = '<a href="'.route('pile_configure.details', ['pileConfigure' => $pileConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('pile_configure.delete', ['pileConfigure' => $pileConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(PileConfigure $pileConfigure) {
                return $pileConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

}
