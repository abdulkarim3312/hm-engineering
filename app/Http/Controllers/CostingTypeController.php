<?php

namespace App\Http\Controllers;

use App\Model\CostingType;
use Illuminate\Http\Request;

class CostingTypeController extends Controller
{
    public function index() {
        $costingTypes = CostingType::get();
        return view('estimate.costing_type.all', compact('costingTypes'));
    }

    public function add() {
        return view('estimate.costing_type.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $costingType = new CostingType();
        $costingType->name = $request->name;
        $costingType->description = $request->description;
        $costingType->status = $request->status;
        $costingType->save();

        return redirect()->route('costing_type')->with('message', 'Costing Type add successfully.');
    }

    public function edit(CostingType $costingType) {
        return view('estimate.costing_type.edit', compact( 'costingType'));
    }

    public function editPost(CostingType $costingType, Request $request) {
        //dd($costingType);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $costingType->name = $request->name;
        $costingType->description = $request->description;
        $costingType->status = $request->status;
        $costingType->save();

        return redirect()->route('costing_type')->with('message', 'Costing Type edit successfully.');
    }
}
