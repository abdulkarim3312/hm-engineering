<?php

namespace App\Http\Controllers;

use App\Floor;
use App\Model\Flat;
use Illuminate\Http\Request;
use App\Model\Project;
use App\Model\SaleInventory;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FloorController extends Controller
{
    public function index() {
        $projects =Project::where('status',1)->get();
        return view('sale.floor.all',compact('projects'));
    }

    public function add() {
        $projects=Project::select('id','name')->orderBy('name')->get();
        return view('sale.floor.add',compact('projects'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project' => 'required',
            'size' => 'nullable|max:255',
            'status' => 'required'
        ]);

        $flat = new Floor();
        $flat->name = $request->name;
        $flat->size = $request->size;
        $flat->project_id = $request->project;
        $flat->status = $request->status;
        $flat->save();

        // $inventory = new SaleInventory();
        // $inventory->flat_id = $flat->id;
        // $inventory->project_id = $request->project;
        // $inventory->date = date('Y-m-d');
        // $inventory->save();



        return redirect()->route('floor')->with('message', 'floor add successfully.');
    }

    public function edit(Floor $floor) {
        $projects=Project::select('id','name')->orderBy('name')->get();
        return view('sale.floor.edit', compact('floor','projects'));
    }

    public function editPost(Floor $floor, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project' => 'required',
            'size' => 'nullable|max:255',
            'status' => 'required'
        ]);

        $floor->size = $request->size;
        $floor->name = $request->name;
        $floor->project_id = $request->project;
        $floor->status = $request->status;
        $floor->save();

        return redirect()->route('floor')->with('message', 'Floor edit successfully.');
    }

    public function datatable() {
        $query = Floor::with('project');

//        if (request()->has('project') && request('project') != '') {
//            if (request()->has('project')) {
//                $query->where('project_id', request('project'));
//            }
//        }

        if (request()->has('project') && request('project') != '') {
            $query->where('project_id',(int)request('project'));
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function(Floor $floor) {
                $btn= '';
                $btn .= '<a class="btn btn-info btn-sm" href="'.route('floor.edit', ['floor' => $floor->id]).'">Edit</a>';
                $btn .= ' <a class="btn btn-danger btn-sm btn-delete" data-id="'.$floor->id.'" role="button"><i class="fa fa-trash-o"></i></a>';
                return $btn;
            })

            ->addColumn('project', function(Floor $floor) {
                return $floor->project->name;
            })

            ->editColumn('status', function(Floor $floor) {
                if ($floor->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function delete(Request $request)
    {
        $select = Floor::find($request->id);

        $logCheck = Flat::where('floor_id',$select->id)
            ->first();

        if ($logCheck){
            return response()->json(['success' => false, 'message' => "Can't Delete,It's have flat logs also. Firstly delete  ".$logCheck->name." flat."]);
        }
        $select->delete();
        return response()->json(['success' => true, 'message' => 'Successfully Deleted.']);


    }
    public function getFloors(Request $request) {
        $branches = Floor::where('project_id', $request->projectId)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($branches);
    }
}
