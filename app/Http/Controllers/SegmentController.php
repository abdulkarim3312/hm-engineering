<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Segment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DataTables;

class SegmentController extends Controller
{
//    public function segmentProjects(){
//        $projects = Project::where('id',Auth::user()->project_id)->where('status', 1)->get();
//        return view('administrator.segment.projects', compact('projects'));
//
//    }

    public function index() {
        return view('administrator.segment.all');
    }

    public function add() {
        $projects= Project::where('status',1)->get();
        return view('administrator.segment.add',compact('projects'));
    }

    public function addPost(Request $request) {

        $request->validate([
            'name' => [
                'required','max:255','string',
                Rule::unique('segments')
                    ->where('name', $request->name)
                    ->where('project_id', $request->project)
            ],
            'project' => 'required',
            'description' => 'nullable|string|max:255',
            'segment_percent' => 'required|numeric|max:100|min:0',
            'segment_unit' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

//        $totalProjectPercentage = ProductSegment::where('project_id',Auth::user()->project_id)->sum('segment_percentage');
//        if($totalProjectPercentage + $request->segment_percentage>100){
//            return redirect()->back()->withInput()->with('message','Total Project Percentage is more then 100');
//        }

        $segment = new Segment();
        $segment->name = $request->name;
        $segment->project_id = $request->project;
        $segment->description = $request->description;
        $segment->segment_percent = $request->segment_percent;
        $segment->segment_unit = $request->segment_unit;
        $segment->status = $request->status;
        $segment->save();

        return redirect()->route('segment')->with('message', 'Segment add successfully.');
    }

    public function edit(Segment $segment) {

        $projects= Project::where('status',1)->get();
        return view('administrator.segment.edit', compact( 'segment','projects'));
    }

    public function editPost(Segment $segment, Request $request) {
        $request->validate([
            'name' => [
                'required','max:255','string',
                Rule::unique('segments')
                    ->ignore($segment)
                    ->where('name', $request->name)
                    ->where('project_id', $request->project)
            ],
            'project' => 'required',
            'description' => 'nullable|string|max:255',
            'segment_percent' => 'required|numeric|max:100|min:0',
            'segment_unit' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

        $segment->name = $request->name;
        $segment->project_id = $request->project;
        $segment->description = $request->description;
        $segment->segment_percent = $request->segment_percent;
        $segment->segment_unit = $request->segment_unit;
        $segment->status = $request->status;
        $segment->save();

        return redirect()->route('segment')->with('message', 'Segment edit successfully.');
    }

    public function datatable() {
        $query = Segment::with('project');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(Segment $segment) {
                return $segment->project->name??'';
            })
            ->addColumn('action', function(Segment $segment) {
                return '<a class="btn btn-info btn-sm" href="'.route('segment.edit', ['segment' => $segment->id]).'">Edit</a>';
            })

            ->editColumn('status', function(Segment $segment) {
                if ($segment->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
}
