<?php

namespace App\Http\Controllers;

use App\Models\BeamType;
use App\Models\ColumnType;
use Illuminate\Http\Request;

class EstimateTypeController extends Controller
{
    public function beamType() {
        $beamTypes = BeamType::get();
        return view('estimate.beam_type.all', compact('beamTypes'));
    }

    public function beamTypeAdd() {
        return view('estimate.beam_type.add');
    }

    public function beamTypeAddPost(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $beamType = new BeamType();
        $beamType->name = $request->name;
        $beamType->status = $request->status;
        $beamType->save();

        return redirect()->route('beam_type')->with('message', 'Beam Type add successfully.');
    }

    public function beamTypeEdit(BeamType $beamType) {
        return view('estimate.beam_type.edit', compact( 'beamType'));
    }

    public function beamTypeEditPost(BeamType $beamType, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $beamType->name = $request->name;
        $beamType->status = $request->status;
        $beamType->save();

        return redirect()->route('beam_type')->with('message', 'Beam Type edit successfully.');
    }

    public function columnType() {
        $columnTypes = ColumnType::get();
        return view('estimate.column_type.all', compact('columnTypes'));
    }

    public function columnTypeAdd() {
        return view('estimate.column_type.add');
    }

    public function columnTypeAddPost(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $columnType = new ColumnType();
        $columnType->name = $request->name;
        $columnType->status = $request->status;
        $columnType->save();

        return redirect()->route('column_type')->with('message', 'Column Type add successfully.');
    }

    public function columnTypeEdit(ColumnType $columnType) {
        return view('estimate.column_type.edit', compact( 'columnType'));
    }

    public function columnTypeEditPost(ColumnType $columnType, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $columnType->name = $request->name;
        $columnType->status = $request->status;
        $columnType->save();

        return redirect()->route('column_type')->with('message', 'Column Type edit successfully.');
    }
}
