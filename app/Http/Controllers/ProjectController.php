<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DataTables;
use Ramsey\Uuid\Uuid;

class ProjectController extends Controller
{
    public function index() {

        return view('administrator.project.all');
    }

    public function add() {
        $clients = Client::where('type',1)->get();
        return view('administrator.project.add',compact('clients'));
    }

//    public function addPost(Request $request) {
//        $request->validate([
//            'name' => 'required|string|max:255|unique:projects',
//            'address' => 'nullable|max:255',
//            'status' => 'required'
//        ]);
//
//        $project = new Project();
//        $project->name = $request->name;
//        $project->address = $request->address;
//        $project->status = $request->status;
//        $project->save();
//
//        return redirect()->route('project')->with('message', 'Project add successfully.');
//    }
//
//    public function edit(Project $project) {
//        return view('administrator.project.edit', compact('project'));
//    }
//
//    public function editPost(Project $project, Request $request) {
//        $request->validate([
//            'name' => 'required|string|max:255|unique:projects,name,'.$project->id,
//            'address' => 'nullable|max:255',
//            'status' => 'required'
//        ]);
//
//        $project->name = $request->name;
//        $project->address = $request->address;
//        $project->status = $request->status;
//        $project->save();
//
//        return redirect()->route('project')->with('message', 'Project edit successfully.');
//    }
//
//    public function datatable() {
//        $query = Project::query();
//
//        return DataTables::eloquent($query)
//            ->addColumn('action', function(Project $project) {
//                return '<a class="btn btn-info btn-sm" href="'.route('project.edit', ['project' => $project->id]).'">Edit</a>';
//            })
//
//            ->editColumn('status', function(Project $project) {
//                if ($project->status == 1)
//                    return '<span class="label label-success">Active</span>';
//                else
//                    return '<span class="label label-danger">Inactive</span>';
//            })
//            ->rawColumns(['action', 'status'])
//            ->toJson();
//    }


    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|max:255',
            'phone_number' => 'nullable|max:11',
            // 'client' => 'required',
            'amount' => 'required',
            'duration_start' => 'date|nullable',
            'duration_end' => 'date|nullable',
            'project_progress' => 'nullable|max:255',
            'attachment' => 'nullable|mimes:doc,pdf,docx',
            'attachment1' => 'nullable|mimes:doc,pdf,docx',
            'attachment2' => 'nullable|mimes:doc,pdf,docx',
            'type' => 'required',
            'status' => 'required'
        ]);

        $attachment=null;
        if ($request->attachment) {
            $file = $request->file('attachment');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/project/attachment';
            $file->move($destinationPath, $filename);

            $attachment = 'uploads/project/attachment/'.$filename;
        }
        $attachment1=null;
        if ($request->attachment1) {
            $file = $request->file('attachment1');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/project/attachment1';
            $file->move($destinationPath, $filename);

            $attachment1 = 'uploads/project/attachment1/'.$filename;
        }
        $attachment2=null;
        if ($request->attachment2) {
            $file = $request->file('attachment2');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/project/attachment2';
            $file->move($destinationPath, $filename);

            $attachment2 = 'uploads/project/attachment2/'.$filename;
        }

        $project = new Project();
        $project->name = $request->name;
        $project->client_id = $request->client;
        $project->type = $request->type;
        $project->address = $request->address;
        $project->phone_number = $request->phone_number;
        $project->amount = $request->amount;
        $project->due = $request->amount;
        $project->duration_start = $request->duration_start;
        $project->duration_end = $request->duration_end;
        $project->project_progress = $request->project_progress;
        $project->attachment = $attachment;
        $project->attachment1 = $attachment1;
        $project->attachment2 = $attachment2;
        $project->status = $request->status;
        $project->save();

        return redirect()->route('project')->with('message', 'Project add successfully.');
    }

    public function edit(Project $project) {
        $clients = Client::where('status',1)->get();
        return view('administrator.project.edit', compact('clients','project'));
    }

    public function editPost(Project $project, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|max:255',
            'phone_number' => 'nullable|max:255',
            // 'client' => 'required',
            'amount' => 'required',
            'type' => 'required',
            'duration_start' => 'date|nullable',
            'duration_end' => 'date|nullable',
            'project_progress' => 'nullable|max:255',
            'attachment' => 'nullable|mimes:doc,pdf,docx',
            'attachment1' => 'nullable|mimes:doc,pdf,docx',
            'attachment2' => 'nullable|mimes:doc,pdf,docx',
            'status' => 'required'
        ]);

        $attachment = $project->attachment;
        if ($request->attachment) {
            // Previous Photo
            $previousFile = public_path($project->attachment);

            if (file_exists($previousFile)) {
                if ($project->attachment != null) {
                    unlink($previousFile);
                }
            }
            $file = $request->file('attachment');
            $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/project/attachment';
            $file->move($destinationPath, $filename);

            $attachment = 'uploads/project/attachment/' . $filename;
        }

        $attachment1 = $project->attachment1;
        if ($request->attachment1) {
            // Previous Photo
            $previousFile = public_path($project->attachment1);

            if (file_exists($previousFile)) {
                if ($project->attachment1 != null) {
                    unlink($previousFile);
                }
            }
            $file = $request->file('attachment1');
            $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/project/attachment1';
            $file->move($destinationPath, $filename);

            $attachment1 = 'uploads/project/attachment1/' . $filename;
        }

        $attachment2 = $project->attachment2;
        if ($request->attachment2) {
            // Previous Photo
            $previousFile = public_path($project->attachment2);

            if (file_exists($previousFile)) {
                if ($project->attachment2 != null) {
                    unlink($previousFile);
                }
            }
            $file = $request->file('attachment2');
            $filename = Uuid::uuid1()->toString() . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/project/attachment2';
            $file->move($destinationPath, $filename);

            $attachment2 = 'uploads/project/attachment2/' . $filename;
        }

        $project->name = $request->name;
        $project->client_id = $request->client;
        $project->type = $request->type;
        $project->address = $request->address;
        $project->phone_number = $request->phone_number;
        $project->amount = $request->amount;
        $project->due = $request->amount;
        $project->duration_start = $request->duration_start;
        $project->duration_end = $request->duration_end;
        $project->project_progress = $request->project_progress;
        $project->attachment = $attachment;
        $project->attachment1 = $attachment1;
        $project->attachment2 = $attachment2;
        $project->status = $request->status;
        $project->save();

        return redirect()->route('project')->with('message', 'Project edit successfully.');
    }

    public function details(Project $project) {
        return view('administrator.project.attachment_details', compact('project'));
    }
    public function details1(Project $project) {
        return view('administrator.project.attachment_details1', compact('project'));
    }
    public function details2(Project $project) {
        return view('administrator.project.attachment_details2', compact('project'));
    }

    public function datatable() {
        $query = Project::with('client');

        return DataTables::eloquent($query)
            ->addColumn('action', function(Project $project) {
                return '<a class="btn btn-info btn-sm" href="'.route('project.edit', ['project' => $project->id]).'">Edit</a>';
            })
            ->addColumn('attachment', function(Project $project) {

                $btns = '<a class="btn btn-info btn-sm" href="'.route('project_attachment.details', ['project' => $project->id]).'">File</a> ';

                if ($project->attachment1 != null)
                    $btns .= '<a class="btn btn-success btn-sm" href="'.route('project_attachment.details1', ['project' => $project->id]).'">File1</a> ';
                if ($project->attachment2 != null)
                    $btns .= '<a class="btn btn-primary btn-sm" href="'.route('project_attachment.details2', ['project' => $project->id]).'">File2</a> ';
                return $btns;

            })

            ->editColumn('client', function (Project $project) {
                return $project->client->name ?? '';
            })
            ->editColumn('project_progress', function (Project $project) {
                return $project->project_progress ?? '';
            })

            ->editColumn('amount', function (Project $project) {
                return $project->amount ?? '';
            })

            ->editColumn('type', function (Project $project) {
                if ($project->type == 1)
                    return '<span class="label label-success">Construction</span>';
                else
                    return '<span class="label label-info">Consultancy</span>';
            })

            ->editColumn('category', function (Project $project) {
                if ($project->category == 1)
                    return '<span class="label label-warning">Consultancy</span>';
                else
                    return '<span class="label label-danger">Construction</span>';
            })

            ->editColumn('status', function(Project $project) {
                if ($project->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status','type','category','attachment'])
            ->toJson();
    }

}
