<?php

namespace App\Http\Controllers;

use App\Model\EstimateProduct;
use App\Model\EstimateProject;
use App\Models\AssignSegment;
use App\Models\AssignSegmentItem;
use App\Models\AssignSegmentProduct;
use App\Models\CostingSegment;
use App\Models\SegmentConfigure;
use App\Models\SegmentConfigureProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignSegmentController extends Controller
{
    public function assignSegment() {
        $assignSegments = AssignSegment::get();
        return view('estimate.assign_segment.all', compact('assignSegments'));
    }

    public function assignSegmentAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $segmentConfigures = SegmentConfigure::get();
        return view('estimate.assign_segment.add',compact('estimateProjects','segmentConfigures'));
    }

    public function assignSegmentAddPost(Request $request) {

        $request->validate([
            'estimate_project' => 'required',
            'date' => 'required',
            'note' => 'required',
            'segment_configure.*' => 'required',
            'height.*' => 'required|numeric|min:0',
            'width.*' => 'required|numeric|min:0',
            'length.*' => 'required|numeric|min:0',
            'segment_quantity.*' => 'required|numeric|min:1',
        ]);

        $assignSegment = new AssignSegment();
        $assignSegment->estimate_project_id = $request->estimate_project;
        $assignSegment->date = $request->date;
        $assignSegment->note = $request->note;
        $assignSegment->save();
        $assignSegment->assign_segment_no = str_pad($assignSegment->id, 7, "0", STR_PAD_LEFT);;
        $assignSegment->save();

        $counter = 0;
        //$total = 0;
        foreach ($request->segment_configure as $reqProduct) {
            $segmentConfigure = SegmentConfigure::where('id', $reqProduct)->with('segmentConfigureProducts')->first();

            $minSquareFeet = ($segmentConfigure->segment_height * $segmentConfigure->segment_width * $segmentConfigure->segment_length);
            $assignSquareFeet = $request->height[$counter] * $request->width[$counter] * $request->length[$counter];

            $assignSegmentItem = AssignSegmentItem::create([
                'assign_segment_id' => $assignSegment->id,
                'estimate_project_id' => $request->estimate_project,
                'segment_configure_id' => $segmentConfigure->id,
                'segment_height' => $request->height[$counter],
                'segment_width' => $request->width[$counter],
                'segment_length' => $request->length[$counter],
                'segment_quantity' => $request->segment_quantity[$counter],
                'minimum_volume' => $minSquareFeet??1,
                'assign_volume' => $assignSquareFeet,
            ]);

            foreach ($segmentConfigure->segmentConfigureProducts as $segmentConfigureProduct){

                $totalEstimateProduct =  ($segmentConfigureProduct->minimum_quantity * $assignSquareFeet) * $request->segment_quantity[$counter];

                $assignSegmentProduct = new AssignSegmentProduct();
                $assignSegmentProduct->assign_segment_item_id = $assignSegmentItem->id;
                $assignSegmentProduct->assign_segment_id = $assignSegment->id;
                $assignSegmentProduct->costing_segment_id = $segmentConfigureProduct->costing_segment_id;
                $assignSegmentProduct->estimate_project_id = $request->estimate_project;
                $assignSegmentProduct->segment_configure_id = $segmentConfigure->id;
                $assignSegmentProduct->segment_unit_type = $segmentConfigureProduct->segment_unit_type;
                $assignSegmentProduct->estimate_product_id = $segmentConfigureProduct->estimate_product_id;
                $assignSegmentProduct->quantity = $totalEstimateProduct;
                $assignSegmentProduct->save();
            }

            $counter++;
        }

        return redirect()->route('assign_segment.details', ['assignSegment' => $assignSegment->id]);
    }

    public function assignSegmentDetails(AssignSegment $assignSegment){
        return view('estimate.assign_segment.details',compact('assignSegment'));
    }

    public function assignSegmentDatatable() {
        $query = AssignSegment::with('estimateProject');

        return DataTables::eloquent($query)
            ->addColumn('estimate_project', function(AssignSegment $assignSegment) {
                return $assignSegment->estimateProject->name??'';
            })
            ->addColumn('action', function(AssignSegment $assignSegment) {

                return '<a href="'.route('assign_segment.details', ['assignSegment' => $assignSegment->id]).'" class="btn btn-primary btn-sm">Details</a>';

            })
            ->editColumn('date', function(AssignSegment $assignSegment) {
                return $assignSegment->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
