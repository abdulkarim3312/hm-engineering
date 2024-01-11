<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\SalesOrder;
use App\Models\Country;
use Illuminate\Http\Request;
use DataTables;
use Ramsey\Uuid\Uuid;

class ClientController extends Controller
{
    public function index() {
        $clients = Client::select('total','paid','due')->where('type',1)->get();
        return view('sale.client.all',compact('clients'));
    }
    public function delete(Request $request)
    {

        $select = Client::find($request->id);

        $logCheck = SalesOrder::where('client_id',$select->id)
            ->first();

        if ($logCheck){
            return response()->json(['success' => false, 'message' => "Can't Delete,It's have sales logs also. Firstly delete sale order no. ".$logCheck->order_no]);
        }
        $select->delete();
        return response()->json(['success' => true, 'message' => 'Successfully Deleted.']);


    }

    public function add() {
        $countries = Country::select('id','name','country_code')->orderBy('name')->get();
        return view('sale.client.add', compact('countries'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|string|max:255',
            'blood_group' => 'nullable',
            'country_code' => 'nullable',
            'country' => 'nullable',
            'nid_number' => 'nullable',
            'gender' => 'required',
            'birthday' => 'nullable|date_format:Y-m-d|before:today',
            'marrige_day' => 'nullable|date_format:Y-m-d|before:today',
            'status' => 'required',
        ]);


        $imagePath = null;
        if ($request->image) {

            // Upload Image
            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/public/uploads/client';
            $file->move($destinationPath, $filename);

            $imagePath = 'public/public/uploads/client/'.$filename;
        }

        $lastClient = Client::orderBy('id','desc')->where('type',1)->first()->id ?? 0;
        $defineIdNo = 'C00'.($lastClient + 1);

        $client = new Client();
        $client->type = 1;
        $client->id_no = $defineIdNo;
        $client->image = $imagePath;
        $client->name = $request->name;
        $client->company_name = $request->company_name;
        $client->blood_group = $request->blood_group;
        $client->nid_number = $request->nid_number;
        $client->mobile_no = $request->mobile_no;
        $client->address = $request->address;
        $client->email = $request->email;
        $client->country_code_id = $request->country_code;
        $client->country_id = $request->country;
        $client->birthday = $request->birthday;
        $client->marrige_day = $request->marrige_day;
        $client->gender = $request->gender;
        $client->status = $request->status;
        $client->save();

        return redirect()->route('client')->with('message', 'Client add successfully.');
    }

    public function edit(Client $client) {
//        dd(public_path($client->image));
        $countries = Country::select('id','name','country_code')->orderBy('name')->get();
        return view('sale.client.edit', compact('client','countries'));
    }

    public function editPost(Client $client, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'mobile_no' => 'nullable',
            'image' => 'nullable|mimes:jpg,png,jpeg',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|string|max:255',
            'gender' => 'required',
            'blood_group' => 'nullable',
            'country_code' => 'nullable',
            'country' => 'nullable',
            'nid_number' => 'nullable',
            'birthday' => 'nullable|date_format:Y-m-d|before:today',
            'marrige_day' => 'nullable|date_format:Y-m-d|before:today',
            'status' => 'required',
        ]);

        $imagePath= $client->image;
        if ($request->image) {

            // Previous Photo
            $previousPhoto = public_path($client->image);

            if (file_exists($previousPhoto)) {
                if ($client->image != null){
                    unlink($previousPhoto);
                }
            }

            $file = $request->file('image');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/public/uploads/client';
            $file->move($destinationPath, $filename);

            $imagePath= 'public/uploads/client/'.$filename;
        }

        $client->image = $imagePath;
        $client->name = $request->name;
        $client->company_name = $request->company_name;
        $client->blood_group = $request->blood_group;
        $client->nid_number = $request->nid_number;
        $client->mobile_no = $request->mobile_no;
        $client->address = $request->address;
        $client->email = $request->email;
        $client->country_code_id = $request->country_code;
        $client->country_id = $request->country;
        $client->gender = $request->gender;
        $client->birthday = $request->birthday;
        $client->marrige_day = $request->marrige_day;
        $client->status = $request->status;
        $client->save();

        return redirect()->route('client')->with('message', 'Client edit successfully.');
    }

    public function profile(Client $client) {
        // $client = Client::find('id',$client->id);
        return view('sale.client.profile', compact('client'));
    }

    public function datatable() {
        $query = Client::with('country')->where('type',1);

        return DataTables::eloquent($query)
            ->addColumn('action', function(Client $client) {
                $btn = '';
                $btn .= '<a class="btn btn-info btn-sm" href="'.route('client.edit', ['client' => $client->id]).'">Edit</a>';
                $btn .= ' <a class="btn btn-danger btn-sm btn-delete" data-id="'.$client->id.'"><i class="fa fa-trash-o"></i></a>';
                return $btn;
            })
            ->addColumn('total', function(Client $client) {
                return '৳ '.number_format($client->total, 2);
            })
            ->addColumn('country', function(Client $client) {
                return  $client->country->name??'';
            })

            ->addColumn('country_code', function(Client $client) {
                return  $client->country->country_code??''.$client->mobile_no;
            })
            ->addColumn('paid', function(Client $client) {
                return '৳ '.number_format($client->paid, 2);
            })
            ->addColumn('due', function(Client $client) {
                return '৳ '.number_format($client->due, 2);
            })
            ->editColumn('image', function(Client $client) {
                return '<img width="50px" heigh="50px" src="'.asset($client->image ?? 'img/no_image.png').'" />';
            })
            ->editColumn('status', function(Client $client) {
                if ($client->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status','image'])
            ->toJson();
    }
}
