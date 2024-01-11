<?php

namespace App\Http\Controllers;

use App\FlatSalesOrder;
use App\Floor;
use App\Model\Flat;
use App\Model\Project;
use App\Model\SaleInventory;
use App\Model\SalesOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FlatController extends Controller
{
    public function index() {
        $projects =Project::where('status',1)->get();
        $flats = Flat::orderBy('name')->get();

        return view('sale.flat.all',compact('flats','projects'));
    }
    public function delete(Request $request)
    {

        $select = Flat::find($request->id);

        $logCheck = SalesOrder::where('flat_id',$select->id)
            ->first();

        if ($logCheck){
            return response()->json(['success' => false, 'message' => "Can't Delete,It's have sales logs also. Firstly delete sale order no. ".$logCheck->order_no]);
        }
        $inventory = SaleInventory::where('flat_id',$select->id)->first();
        if ($inventory){
            $inventory->delete();
        }
        $select->delete();
        return response()->json(['success' => true, 'message' => 'Successfully Deleted.']);


    }
    public function ApartmentAdd(){
        $projects=Project::select('id','name')->orderBy('name')->get();
        return view('sale.flat.apartment_add',compact('projects'));

    }

    public function add() {
        $projects=Project::select('id','name')->orderBy('name')->get();
        return view('sale.flat.add',compact('projects'));
    }

    public function addPost(Request $request) {

        $request->validate([
            'name.*' => 'required|string|max:255',
            'project.*' => 'required',
            'floor_id.*' => 'required',
            'type.*' => 'required',
            'size.*' => 'required|max:255',
            'floor_quantity.*' => 'required|numeric|min:1',
        ]);

        $counter = 0;

        foreach ($request->project as $reqProject) {

            //dd($request->all());

//            $flat = Flat::create([
//                'project_id' => $request->project[$counter],
//                'floor_id' => $request->floor_id[$counter],
//                'type' => $request->type[$counter],
//                'name' => $request->name[$counter],
//                'size' => $request->size[$counter],
//                'floor_quantity' => $request->floor_quantity[$counter],
//                'status' => 1,
//            ]);
//
//            SaleInventory::create([
//                'flat_id' => $flat->id,
//                'floor_id' => $request->floor_id[$counter],
//                'project_id' => $request->project[$counter],
//                'date' => date('Y-m-d'),
//            ]);

            $flat = new Flat();
            $flat->name = $request->name[$counter];
            $flat->type = $request->type[$counter];
            $flat->size = $request->size[$counter];
            $flat->project_id = $request->project[$counter];
            $flat->floor_id = $request->floor_id[$counter];
            $flat->floor_quantity = $request->floor_quantity[$counter];
            $flat->status = 1;
            $flat->save();

            $inventory = new SaleInventory();
            $inventory->flat_id = $flat->id;
            $inventory->project_id = $request->project[$counter];
            $inventory->floor_id = $request->floor_id[$counter];
            $inventory->date = date('Y-m-d');
            $inventory->save();

            $counter++;
        }

        return redirect()->route('flat')->with('message', 'Flat add successfully.');
    }

    public function edit(Flat $flat) {

        $projects = Project::select('id','name')->orderBy('name')->get();

        return view('sale.flat.edit', compact('flat','projects'));
    }

    public function editPost(Flat $flat, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required',
            'floor_id' => 'required',
            'type' => 'required',
            'size' => 'nullable|max:255',
            'status' => 'required'
        ]);

        $flat->size = $request->size;
        $flat->name = $request->name;
        $flat->type = $request->type;
        $flat->project_id = $request->project_id;
        $flat->floor_id = $request->floor_id;
        $flat->status = $request->status;
        $flat->save();

        return redirect()->route('flat')->with('message', 'Flat edit successfully.');
    }

    public function datatable() {
        $query = Flat::with('project','floor');

//        dd($query);
        // $floor = Floor::with('floor');

        return DataTables::eloquent($query)
            ->addColumn('action', function(Flat $flat) {
                return '<a class="btn btn-info btn-sm" href="'.route('flat.edit', ['flat' => $flat->id]).'">Edit</a>';
            })
            ->addColumn('project', function(Flat $flat) {
                return $flat->project->name;
            })
            ->addColumn('floor', function(Flat $flat) {
                return $flat->floor->name;
            })
            ->addColumn('fname', function(Flat $flat) {
                return $flat->name;
            })
            ->addColumn('type', function(Flat $flat) {
                if ($flat->type == 1)
                    return 'Apartment';
                elseif($flat->type == 2)
                    return 'Shop';
                elseif($flat->type == 4)
                    return 'Car Parking';
                else
                    return 'Commercial Space';
            })
            ->addColumn('status', function(Flat $flat) {
                if ($flat->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function getFlats(Request $request) {
        $branches = Flat::where('floor_id', $request->projectId)
            ->orderBy('name')
            ->get()->toArray();
        return response()->json($branches);
    }
}
