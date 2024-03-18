<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Models\Contractor;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContractorController extends Controller
{
    public function contractorAll() {
        $contractors = Contractor::where('status', 1)->get();
        return view('labour.contractor.all',compact('contractors'));
    }

    public function contractorAdd() {
        $projects = Project::where('status', 1)->get();
        return view('labour.contractor.add', compact('projects'));
    }

    public function contractorAddPost(Request $request) {
    //    dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable',
            'contractor_id' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'nid' => 'nullable',
            'mobile' => 'required|digits:11',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $imagePath = null;
        if ($request->image) {
            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/contractor';
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/contractor/'.$filename;
        }

        $contractor = new Contractor();
        $contractor->name = $request->name;
        $contractor->contractor_id = $request->contractor_id;
        $contractor->project_id = $request->project_id;
        $contractor->trade = $request->trade;
        $contractor->image = $imagePath;
        $contractor->mobile = $request->mobile;
        $contractor->nid = $request->nid;
        $contractor->address = $request->address;
        $contractor->status = $request->status;
        $contractor->save();

        return redirect()->route('contractor.all')->with('message', 'Contractor add successfully.');
    }

    public function contractorEdit(Contractor $contractor) {
        $projects = Project::where('status', 1)->get();
        return view('labour.contractor.edit', compact('contractor','projects'));
    }

    public function contractorEditPost(Contractor $contractor, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable',
            'contractor_id' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'nid' => 'nullable',
            'mobile' => 'required|digits:11',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $imagePath = $contractor->image;

        if ($request->image) {

            if ($contractor->image){
                // Previous Photo
                $previousPhoto = public_path($contractor->image);
                unlink($previousPhoto);
            }

            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getcontractorOriginalExtension();
            $destinationPath = 'public/uploads/contractor';
            $file->move($destinationPath, $filename);

            $imagePath = 'uploads/contractor/'.$filename;
        }
        $contractor->name = $request->name;
        $contractor->contractor_id = $request->contractor_id;
        $contractor->project_id = $request->project_id;
        $contractor->trade = $request->trade;
        $contractor->image = $imagePath;
        $contractor->mobile = $request->mobile;
        $contractor->nid = $request->nid;
        $contractor->address = $request->address;
        $contractor->status = $request->status;
        $contractor->save();


        return redirect()->route('contractor.all')->with('message', 'Contractor edit successfully.');
    }


    public function datatable() {
        $query = Contractor::with('projects');

        return DataTables::eloquent($query)
            ->addColumn('project', function(Contractor $contractor) {
                return $contractor->projects->name ?? '';
            })
            ->addColumn('action', function(Contractor $contractor) {
                return '<a class="btn btn-info btn-sm" href="'.route('contractor.edit', ['contractor' => $contractor->id]).'">Edit</a>';
            })
            ->editColumn('image', function(Contractor $contractor) {
                return '<img width="50px" heigh="50px" src="'.asset($contractor->image ?? 'img/no_image.png').'" />';
            })
            ->editColumn('total', function(Contractor $contractor) {
                return ' '.number_format($contractor->total, 2);
            })
            ->editColumn('paid', function(Contractor $contractor) {
                return ' '.number_format($contractor->paid, 2);
            })
            ->editColumn('due', function(Contractor $contractor) {
                return ' '.number_format($contractor->due, 2);
            })
            ->editColumn('trade', function (Contractor $contractor) {
                if ($contractor->trade == 1)
                    return '<span class="label label-success">Civil Contractor</span>';
                elseif($contractor->trade == 2)
                    return '<span class="label label-warning">Paint Contractor</span>';
                elseif($contractor->trade == 3)
                    return '<span class="label label-primary">Sanitary Contractor</span>';
                elseif($contractor->trade == 4)
                    return '<span class="label label-info">Tiles Contractor</span>';
                elseif($contractor->trade == 5)
                    return '<span class="label label-info">MS Contractor</span>';
                elseif($contractor->trade == 6)
                    return '<span class="label label-info">Wood Contractor</span>';
                elseif($contractor->trade == 7)
                    return '<span class="label label-info">Electric Contractor</span>';
                elseif($contractor->trade == 8)
                    return '<span class="label label-info">Thai Contractor</span>';
                else
                    return '<span class="label label-danger">Other Contractor</span>';
            })
            ->editColumn('status', function(Contractor $contractor) {
                if ($contractor->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status','image','trade'])
            ->toJson();
    }
}
