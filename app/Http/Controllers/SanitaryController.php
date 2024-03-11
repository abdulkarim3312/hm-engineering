<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\EstimateFloorUnit;
use App\Models\SanitaryConfigure;
use App\Models\SanitaryConfigureProduct;
use App\Models\SanitaryProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SanitaryController extends Controller
{
    public function sanitaryProduct() {
        return view('estimate.sanitary_product.all');
    }

    public function sanitaryProductAdd() {
        return view('estimate.sanitary_product.add');
    }

    public function sanitaryProductAddPost(Request $request) {
        // dd('ok');
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $sanitaryProduct = new SanitaryProduct();
        $sanitaryProduct->name = $request->name;
        $sanitaryProduct->status = $request->status;
        $sanitaryProduct->save();
        return redirect()->route('sanitary_product')->with('message', 'Sanitary Product add successfully.');
    }

    public function sanitaryProductEdit(SanitaryProduct $sanitaryProduct) {
        return view('estimate.sanitary_product.edit', compact('sanitaryProduct'));
    }

    public function sanitaryProductEditPost(SanitaryProduct $sanitaryProduct, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $sanitaryProduct->name = $request->name;
        $sanitaryProduct->status = $request->status;
        $sanitaryProduct->save();

        return redirect()->route('sanitary_product')->with('message', 'Sanitary Product edit successfully.');
    }

    public function sanitaryProductDatatable() {
        $query = SanitaryProduct::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(SanitaryProduct $sanitaryProduct) {
                return '<a class="btn btn-info btn-sm" href="'.route('sanitary_product.edit', ['sanitaryProduct' => $sanitaryProduct->id]).'">Edit</a>';
            })
            ->editColumn('status', function(SanitaryProduct $sanitaryProduct) {
                if ($sanitaryProduct->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function sanitaryCostingAll() {
        return view('estimate.sanitary_costing.all');
    }

    public function sanitaryCostingAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $products = SanitaryProduct::where('status',1)->get();
        return view('estimate.sanitary_costing.add',compact('estimateProjects',
            'estimateFloorUnits','products'));
    }

    public function sanitaryCostingAddPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'floor_number' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
            'product.*' => 'required',
        ]);

        $sanitaryConfigure = new SanitaryConfigure();
        $sanitaryConfigure->estimate_project_id = $request->estimate_project;
        $sanitaryConfigure->estimate_floor_unit = $request->estimate_floor_unit;
        $sanitaryConfigure->estimate_floor = $request->estimate_floor;
        $sanitaryConfigure->floor_number = $request->floor_number;
        $sanitaryConfigure->total = 0;
        $sanitaryConfigure->date = $request->date;
        $sanitaryConfigure->note = $request->note;
        $sanitaryConfigure->save();

        $counter = 0;
        $totalTk = 0;
        foreach ($request->product as $key => $reqProduct) {

            SanitaryConfigureProduct::create([
                'electric_costing_id' => $sanitaryConfigure->id,
                'estimate_project_id' => $request->estimate_project,
                'estimate_floor_id' => $request->estimate_floor,
                'product_id' => $reqProduct,
                'nos' => $request->nos[$counter],
                'amount' => $request->amount[$counter],
                'sub_total_tk' => $request->nos[$counter] * $request->amount[$counter],
            ]);


            $totalTk += $request->nos[$counter] * $request->amount[$counter];
            $counter++;
        }

        $sanitaryConfigure->total = $totalTk * $request->floor_number;
        $sanitaryConfigure->save();

        return redirect()->route('sanitary_costing.details', ['sanitaryConfigure' => $sanitaryConfigure->id]);

    }

    public function sanitaryCostingDetails(Request $request, $sanitaryConfigure){
        $sanitary = SanitaryConfigure::with('project', 'estimateFloor','estimateFloorUnit')->find($sanitaryConfigure);
        $sanitaryCostings = SanitaryConfigureProduct::with('products')->where('electric_costing_id', $sanitaryConfigure)->get();
        return view('estimate.sanitary_costing.details',compact('sanitaryCostings','sanitary'));
    }
    public function sanitaryCostingPrint(Request $request, $sanitaryConfigure){
        $sanitary = SanitaryConfigure::with('project', 'estimateFloor','estimateFloorUnit')->find($sanitaryConfigure);
        $sanitaryCostings = SanitaryConfigureProduct::with('products')->where('electric_costing_id', $sanitaryConfigure)->get();
        return view('estimate.sanitary_costing.print',compact('sanitaryCostings','sanitary'));
    }

    public function sanitaryCostingDelete(SanitaryConfigure $sanitaryConfigure){
        SanitaryConfigure::find($sanitaryConfigure->id)->delete();
        SanitaryConfigureProduct::where('electric_costing_id', $sanitaryConfigure->id)->delete();
        return redirect()->route('sanitary_costing')->with('message', 'Sanitary Info Deleted Successfully.');
    }

    public function sanitaryCostingDatatable() {
        $query = SanitaryConfigure::with('project','estimateFloor','estimateFloorUnit');
        return DataTables::eloquent($query)
            ->addColumn('project_name', function(SanitaryConfigure $sanitaryConfigure) {
                return $sanitaryConfigure->project->name ?? '';
            })
            ->addColumn('estimate_floor', function(SanitaryConfigure $sanitaryConfigure) {
                return $sanitaryConfigure->estimateFloor->name ?? '';
            })
            ->addColumn('estimate_floor_unit', function(SanitaryConfigure $sanitaryConfigure) {
                return $sanitaryConfigure->estimateFloorUnit->name ?? '';
            })
            ->addColumn('action', function(SanitaryConfigure $sanitaryConfigure) {
                $btn = '';
                $btn = '<a href="'.route('sanitary_costing.details', ['sanitaryConfigure' => $sanitaryConfigure->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('sanitary_costing.delete', ['sanitaryConfigure' => $sanitaryConfigure->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(SanitaryConfigure $sanitaryConfigure) {
                return $sanitaryConfigure->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
