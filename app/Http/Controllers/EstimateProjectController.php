<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use Illuminate\Http\Request;
use DataTables;

class EstimateProjectController extends Controller
{
    public function index() {

        return view('estimate.project.all');
    }

    public function add() {

        return view('estimate.project.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:estimate_projects',
            'address' => 'nullable|max:255',
            'status' => 'required'
        ]);

        $project = new EstimateProject();
        $project->name = $request->name;
        $project->address = $request->address;
        $project->status = $request->status;
        $project->save();

        return redirect()->route('estimate_project')->with('message', 'Project add successfully.');
    }

    public function edit(EstimateProject $project) {
        return view('estimate.project.edit', compact('project'));
    }

    public function editPost(EstimateProject $project, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,'.$project->id,
            'address' => 'nullable|max:255',
            'status' => 'required'
        ]);

        $project->name = $request->name;
        $project->address = $request->address;
        $project->status = $request->status;
        $project->save();

        return redirect()->route('estimate_project')->with('message', 'Project edit successfully.');
    }

    public function datatable() {
        $query = EstimateProject::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(EstimateProject $project) {
                return '<a class="btn btn-info btn-sm" href="'.route('estimate_project.edit', ['project' => $project->id]).'">Edit</a>';
            })

            ->editColumn('status', function(EstimateProject $project) {
                if ($project->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
}
