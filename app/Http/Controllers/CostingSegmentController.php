<?php

namespace App\Http\Controllers;

use App\Models\CostingSegment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DataTables;

class CostingSegmentController extends Controller
{
    public function index() {
        return view('estimate.costing_segment.all');
    }

    public function add() {
        return view('estimate.costing_segment.add');
    }

    public function addPost(Request $request) {

        $request->validate([
            'name' => [
                'required','max:255','string',
                Rule::unique('costing_segments')
                    ->where('name', $request->name)
            ],
            'description' => 'nullable|string|max:255',
            'segment_unit' => 'nullable|numeric|min:0',
            'status' => 'required'
        ]);

        $segment = new CostingSegment();
        $segment->name = $request->name;
        $segment->description = $request->description;
        $segment->segment_unit = $request->segment_unit;
        $segment->status = $request->status;
        $segment->save();

        return redirect()->route('costing_segment')->with('message', 'Costing Segment add successfully.');
    }

    public function edit(CostingSegment $costingSegment) {
        return view('estimate.costing_segment.edit', compact('costingSegment' ));
    }

    public function editPost(CostingSegment $costingSegment, Request $request) {
        $request->validate([
            'name' => [
                'required','max:255','string',
                Rule::unique('costing_segments')
                    ->ignore($costingSegment)
                    ->where('name', $request->name)
            ],
            'description' => 'nullable|string|max:255',
            'segment_unit' => 'required|numeric|min:0',
            'status' => 'required'
        ]);
        $costingSegment->name = $request->name;
        $costingSegment->description = $request->description;
        $costingSegment->segment_unit = $request->segment_unit;
        $costingSegment->status = $request->status;
        $costingSegment->save();

        return redirect()->route('costing_segment')->with('message', 'Costing Segment edit successfully.');
    }

    public function datatable() {
        $query = CostingSegment::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(CostingSegment $costingSegment) {
                return '<a class="btn btn-info btn-sm" href="'.route('costing_segment.edit', ['costingSegment' => $costingSegment->id]).'">Edit</a>';
            })

            ->editColumn('status', function(CostingSegment $costingSegment) {
                if ($costingSegment->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
}
