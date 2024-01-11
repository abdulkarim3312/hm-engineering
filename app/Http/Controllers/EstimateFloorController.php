<?php

namespace App\Http\Controllers;
use App\Model\EstimateProject;
use App\Model\Project;
use App\Models\EstimateFloor;
use App\Models\EstimateFloorUnit;
use App\Models\UnitSection;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EstimateFloorController extends Controller
{
    public function index() {

        return view('estimate.estimate_floor.all');
    }

    public function add() {
        $estimateProjects = EstimateProject::orderBy('name')->get();
        return view('estimate.estimate_floor.add',compact('estimateProjects'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'estimate_project' => 'required',
            'status' => 'required'
        ]);

        $estimateFloor = new EstimateFloor();
        $estimateFloor->name = $request->name;
        $estimateFloor->estimate_project_id = $request->estimate_project;
        $estimateFloor->status = $request->status;
        $estimateFloor->save();
        return redirect()->route('estimate_floor')->with('message', 'Floor add successfully.');
    }

    public function edit(EstimateFloor $estimateFloor) {
        $estimateProjects = EstimateProject::orderBy('name')->get();
        return view('estimate.estimate_floor.edit', compact('estimateFloor','estimateProjects'));
    }

    public function editPost(EstimateFloor $estimateFloor, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'estimate_project' => 'required',
            'status' => 'required'
        ]);

        $estimateFloor->name = $request->name;
        $estimateFloor->estimate_project_id = $request->estimate_project;
        $estimateFloor->status = $request->status;
        $estimateFloor->save();

        return redirect()->route('estimate_floor')->with('message', 'EstimateFloor edit successfully.');
    }

    public function datatable() {
        $query = EstimateFloor::with('estimateProject');

        return DataTables::eloquent($query)
            ->addColumn('action', function(EstimateFloor $estimateFloor) {
                return '<a class="btn btn-info btn-sm" href="'.route('estimate_floor.edit', ['estimateFloor' => $estimateFloor->id]).'">Edit</a>';
            })
            ->addColumn('estimate_project', function(EstimateFloor $estimateFloor) {
                return $estimateFloor->estimateProject->name;
            })
            ->editColumn('status', function(EstimateFloor $estimateFloor) {
                if ($estimateFloor->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function getEstimateFloors(Request $request) {
        $estimateFloors = EstimateFloor::where('estimate_project_id', $request->estimateProjectId)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($estimateFloors);
    }

    public function unit() {

        return view('estimate.estimate_floor_unit.all');
    }

    public function unitAdd() {
        return view('estimate.estimate_floor_unit.add');
    }

    public function unitAddPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $estimateFloorUnit = new EstimateFloorUnit();
        $estimateFloorUnit->name = $request->name;
        $estimateFloorUnit->status = $request->status;
        $estimateFloorUnit->save();
        return redirect()->route('estimate_floor_unit')->with('message', 'Floor Unit add successfully.');
    }

    public function unitEdit(EstimateFloorUnit $estimateFloorUnit) {
        return view('estimate.estimate_floor_unit.edit', compact('estimateFloorUnit'));
    }

    public function unitEditPost(EstimateFloorUnit $estimateFloorUnit, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $estimateFloorUnit->name = $request->name;
        $estimateFloorUnit->status = $request->status;
        $estimateFloorUnit->save();

        return redirect()->route('estimate_floor_unit')->with('message', 'EstimateFloorUnit edit successfully.');
    }

    public function unitDatatable() {
        $query = EstimateFloorUnit::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(EstimateFloorUnit $estimateFloorUnit) {
                return '<a class="btn btn-info btn-sm" href="'.route('estimate_floor_unit.edit', ['estimateFloorUnit' => $estimateFloorUnit->id]).'">Edit</a>';
            })
            ->editColumn('status', function(EstimateFloorUnit $estimateFloorUnit) {
                if ($estimateFloorUnit->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function unitSection() {

        return view('estimate.unit_section.all');
    }

    public function unitSectionAdd() {
        return view('estimate.unit_section.add');
    }

    public function unitSectionAddPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $unitSection = new UnitSection();
        $unitSection->name = $request->name;
        $unitSection->status = $request->status;
        $unitSection->save();
        return redirect()->route('unit_section')->with('message', 'Unit Section add successfully.');
    }

    public function unitSectionEdit(UnitSection $unitSection) {
        return view('estimate.unit_section.edit', compact('unitSection'));
    }

    public function unitSectionEditPost(UnitSection $unitSection, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $unitSection->name = $request->name;
        $unitSection->status = $request->status;
        $unitSection->save();

        return redirect()->route('unit_section')->with('message', 'Unit Section edit successfully.');
    }

    public function unitSectionDatatable() {
        $query = UnitSection::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(UnitSection $unitSection) {
                return '<a class="btn btn-info btn-sm" href="'.route('unit_section.edit', ['unitSection' => $unitSection->id]).'">Edit</a>';
            })
            ->editColumn('status', function(UnitSection $unitSection) {
                if ($unitSection->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function floorConfigureIndex() {

        return view('estimate.estimate_floor_configure.all');
    }
}
