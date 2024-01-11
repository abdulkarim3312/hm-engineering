<?php

namespace App\Http\Controllers;

use App\Model\LabourDesignation;
use Illuminate\Http\Request;

class LabourDesignationController extends Controller
{
    public function index() {
        $labourDesignations = LabourDesignation::get();

        return view('labour.designation.all', compact('labourDesignations'));
    }

    public function add() {

        return view('labour.designation.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $designation = new LabourDesignation();
        $designation->name = $request->name;
        $designation->status = $request->status;
        $designation->save();

        return redirect()->route('labour_designation.all')->with('message', 'Labour Designation add successfully.');
    }

    public function edit(LabourDesignation $labourDesignation) {

        return view('labour.designation.edit', compact( 'labourDesignation'));
    }

    public function editPost(LabourDesignation $labourDesignation, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $labourDesignation->name = $request->name;
        $labourDesignation->status = $request->status;
        $labourDesignation->save();

        return redirect()->route('labour_designation.all')->with('message', 'Labour Designation edit successfully.');
    }
}
