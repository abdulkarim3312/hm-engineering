<?php

namespace App\Http\Controllers;

use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Model\Holiday;
use App\Model\Leave;
use App\Model\Project;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::orderBy('id')->get();
        return view('warehouse.all', compact('warehouses'));
    }

    public function add()
    {
        $projects = Project::where('status', 1)->get();
        return view('warehouse.add', compact('projects'));
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'maintainer_name' => 'nullable|string|max:255',
            'maintainer_mobile' => 'nullable|string|max:255',
            'address' => 'nullable',
            'status' => 'required'
        ]);

        $warehouse = new Warehouse();
        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->maintainer_name = $request->maintainer_name;
        $warehouse->maintainer_mobile = $request->maintainer_mobile;
        $warehouse->status = $request->status;
        $warehouse->save();

        return redirect()->route('warehouse.all')->with('message', 'Warehouse add successfully.');
    }

    public function edit(Request $request, $id)
    {
        $editData = Warehouse::find($id);
        $projects = Project::where('status', 1)->get();
        return view('warehouse.edit',compact('editData', 'projects'));
    }

    public function editPost(Request $request, Warehouse $warehouse)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable',
            'maintainer_name' => 'nullable|string|max:255',
            'maintainer_mobile' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $warehouse->name = $request->name;
        $warehouse->address = $request->address;
        $warehouse->maintainer_name = $request->maintainer_name;
        $warehouse->maintainer_mobile = $request->maintainer_mobile;
        $warehouse->status = $request->status;
        $warehouse->save();

        return redirect()->route('warehouse.all')->with('message', 'Warehouse update successfully.');
    }

    public function delete($id){
        $deleteData = Warehouse::find($id);
        $deleteData->delete();
        return redirect()->route('warehouse.all')->with('message', 'Warehouse Deleted successfully.');
    }
}
