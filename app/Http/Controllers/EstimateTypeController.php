<?php

namespace App\Http\Controllers;

use App\Models\BeamType;
use App\Models\ColumnType;
use App\Models\GradeBeamType;
use App\Models\ShortColumnType;
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

    public function shortColumnType() {
        $shortColumnTypes = ShortColumnType::get();
        return view('estimate.short_column_type.all', compact('shortColumnTypes'));
    }
    public function shortColumnTypeAdd() {
        return view('estimate.short_column_type.add');
    }

    public function shortColumnTypeAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $shortColumnType = new ShortColumnType();
        $shortColumnType->name = $request->name;
        $shortColumnType->status = $request->status;
        $shortColumnType->save();

        return redirect()->route('short_column_type')->with('message', 'Short Column Type add successfully.');
    }

    public function shortColumnTypeEdit(ShortColumnType $shortColumnType) {
        return view('estimate.short_column_type.edit', compact( 'shortColumnType'));
    }

    public function shortColumnTypeEditPost(ShortColumnType $shortColumnType, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $shortColumnType->name = $request->name;
        $shortColumnType->status = $request->status;
        $shortColumnType->save();

        return redirect()->route('short_column_type')->with('message', 'Short Column Type edit successfully.');
    }

    public function gradeBeamType() {
        $gradeBeamTypes = GradeBeamType::get();
        return view('estimate.grade_beam_type.all', compact('gradeBeamTypes'));
    }

    public function gradeBeamTypeAdd() {
        return view('estimate.grade_beam_type.add');
    }

    public function gradeBeamTypeAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $gradeBeamType = new GradeBeamType();
        $gradeBeamType->name = $request->name;
        $gradeBeamType->status = $request->status;
        $gradeBeamType->save();

        return redirect()->route('grade_beam_type')->with('message', 'Grade Beam Type add successfully.');
    }

    public function gradeBeamTypeEdit(GradeBeamType $gradeBeamType) {
        return view('estimate.grade_beam_type.edit', compact( 'gradeBeamType'));
    }

    public function gradeBeamTypeEditPost(GradeBeamType $gradeBeamType, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $gradeBeamType->name = $request->name;
        $gradeBeamType->status = $request->status;
        $gradeBeamType->save();

        return redirect()->route('grade_beam_type')->with('message', 'Grade Beam Type edit successfully.');
    }
}
