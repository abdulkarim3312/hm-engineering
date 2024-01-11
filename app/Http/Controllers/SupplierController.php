<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\Project;
use App\Model\Supplier;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Client::select('total','paid','due')->where('type',2)->get();
        return view('purchase.supplier.all',compact('suppliers'));
    }

    public function add() {
        return view('purchase.supplier.add');
    }

    public function addPost(Request $request) {
    //    dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'project_type' => 'nullable',
            'company_name' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'email' => 'nullable|email',
            'mobile_no' => 'required|digits:11',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);
        $imagePath = null;
        if ($request->image) {

            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/supplier';
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/supplier/'.$filename;
        }

        $lastSupplier = Client::orderBy('id','desc')
                ->where('type',2)->first()->id ?? 0;
        $defineIdNo = 'S00'.($lastSupplier + 1);

        $supplier = new Client();
        $supplier->type = 2;
        $supplier->project_type = $request->project_type;
        $supplier->id_no = $defineIdNo;
        $supplier->name = $request->name;
        $supplier->image = $imagePath;
        $supplier->company_name = $request->company_name;
        $supplier->mobile_no = $request->mobile_no;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->status = $request->status;
        $supplier->save();

        return redirect()->route('supplier')->with('message', 'Supplier add successfully.');
    }

    public function edit(Client $client) {
        return view('purchase.supplier.edit', compact('client'));
    }

    public function editPost(Client $client, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_type' => 'nullable',
            'company_name' => 'required|string|max:255',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'email' => 'nullable|email',
            'mobile_no' => 'required|digits:11',
            'address' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $imagePath = $client->image;

        if ($request->image) {

            if ($client->image){
                // Previous Photo
                $previousPhoto = public_path($client->image);
                unlink($previousPhoto);
            }

            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/supplier';
            $file->move($destinationPath, $filename);

            $imagePath = 'uploads/supplier/'.$filename;
        }
        $client->image = $imagePath;
        $client->project_type = $request->project_type;
        $client->name = $request->name;
        $client->company_name = $request->company_name;
        $client->mobile_no = $request->mobile_no;
        $client->email = $request->email;
        $client->address = $request->address;
        $client->status = $request->status;
        $client->save();


        return redirect()->route('supplier')->with('message', 'Supplier edit successfully.');
    }


    public function datatable() {
        $query = Client::where('type',2);

        return DataTables::eloquent($query)
            ->addColumn('action', function(Client $client) {
                return '<a class="btn btn-info btn-sm" href="'.route('supplier.edit', ['client' => $client->id]).'">Edit</a>';
            })
            ->editColumn('image', function(Client $client) {
                return '<img width="50px" heigh="50px" src="'.asset($client->image ?? 'img/no_image.png').'" />';
            })
            ->editColumn('total', function(Client $client) {
                return ' '.number_format($client->total, 2);
            })
            ->editColumn('paid', function(Client $client) {
                return ' '.number_format($client->paid, 2);
            })
            ->editColumn('due', function(Client $client) {
                return ' '.number_format($client->due, 2);
            })
            ->editColumn('discount', function(Client $client) {
                return ' '.number_format($client->discount, 2);
            })
            ->editColumn('project_type', function (Client $client) {
                if ($client->project_type == 1)
                    return '<span class="label label-success">Construction</span>';
                elseif($client->project_type == 2)
                    return '<span class="label label-info">Consultancy</span>';
                else
                    return '<span class="label label-danger">Trading</span>';
            })
            ->editColumn('status', function(Client $client) {
                if ($client->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status','image','project_type'])
            ->toJson();
    }
}
