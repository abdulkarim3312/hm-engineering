<?php

namespace App\Http\Controllers;

use App\Model\EstimateProject;
use App\Models\ElectricCosting;
use App\Models\ElectricCostingProduct;
use App\Models\ElectricProduct;
use App\Models\EstimateFloorUnit;
use App\Models\GrillGlassTilesConfigure;
use App\Models\UnitSection;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ElectricCalculationController extends Controller
{
    public function electricProduct() {
        return view('estimate.electric_product.all');
    }

    public function electricProductAdd() {
        return view('estimate.electric_product.add');
    }

    public function electricProductAddPost(Request $request) {
        // dd('ok');
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $electricProduct = new ElectricProduct();
        $electricProduct->name = $request->name;
        $electricProduct->status = $request->status;
        $electricProduct->save();
        return redirect()->route('electric_product')->with('message', 'Electric Product add successfully.');
    }

    public function electricProductEdit(ElectricProduct $electricProduct) {
        return view('estimate.electric_product.edit', compact('electricProduct'));
    }

    public function electricProductEditPost(ElectricProduct $electricProduct, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $electricProduct->name = $request->name;
        $electricProduct->status = $request->status;
        $electricProduct->save();

        return redirect()->route('electric_product')->with('message', 'Electric Product edit successfully.');
    }

    public function electricProductDatatable() {
        $query = ElectricProduct::query();

        return DataTables::eloquent($query)
            ->addColumn('action', function(ElectricProduct $electricProduct) {
                return '<a class="btn btn-info btn-sm" href="'.route('electric_product.edit', ['electricProduct' => $electricProduct->id]).'">Edit</a>';
            })
            ->editColumn('status', function(ElectricProduct $electricProduct) {
                if ($electricProduct->status == 1)
                    return '<span class="label label-success">Active</span>';
                else
                    return '<span class="label label-danger">Inactive</span>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function electricCostingAll() {
        return view('estimate.electric_costing.all');
    }

    public function electricCostingAdd() {
        $estimateProjects = EstimateProject::where('status',1)->get();
        $estimateFloorUnits = EstimateFloorUnit::where('status',1)->get();
        $products = ElectricProduct::where('status',1)->get();
        $grillGlassTilesCost = GrillGlassTilesConfigure::orderBy('id','desc')->first();
        return view('estimate.electric_costing.add',compact('estimateProjects',
            'estimateFloorUnits','products','grillGlassTilesCost'));
    }

    public function electricCostingAddPost(Request $request) {
        $request->validate([
            'estimate_project' => 'required',
            'estimate_floor' => 'required',
            'estimate_floor_unit' => 'required',
            'floor_number' => 'required|numeric|min:1',
            'date' => 'required',
            'note' => 'nullable',
            'product.*' => 'required',
        ]);

        $electricCosting = new ElectricCosting();
        $electricCosting->estimate_project_id = $request->estimate_project;
        $electricCosting->estimate_floor_unit = $request->estimate_floor_unit;
        $electricCosting->estimate_floor = $request->estimate_floor;
        $electricCosting->floor_number = $request->floor_number;
        $electricCosting->total = 0;
        $electricCosting->date = $request->date;
        $electricCosting->note = $request->note;
        $electricCosting->save();

        $counter = 0;
        $totalTk = 0;
        foreach ($request->product as $key => $reqProduct) {
            ElectricCostingProduct::create([
                'electric_costing_id' => $electricCosting->id,
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

        $electricCosting->total = $totalTk * $request->floor_number;
        $electricCosting->save();

        return redirect()->route('electric_costing.details', ['electricCosting' => $electricCosting->id]);
    }

    public function electricCostingDetails(Request $request, $electricCosting){
        $electricCost = ElectricCosting::with('project', 'estimateFloor','estimateFloorUnit')->find($electricCosting);
        $electricCostings = ElectricCostingProduct::with('electricProduct')->where('electric_costing_id', $electricCosting)->get();
        return view('estimate.electric_costing.details',compact('electricCostings','electricCost'));
    }
    public function electricCostingPrint(Request $request, $electricCosting){
        $electricCost = ElectricCosting::with('project', 'estimateFloor','estimateFloorUnit')->find($electricCosting);
        $electricCostings = ElectricCostingProduct::with('electricProduct')->where('electric_costing_id', $electricCosting)->get();
        return view('estimate.electric_costing.print',compact('electricCostings','electricCost'));
    }

    public function electricCostingDelete(ElectricCosting $electricCosting){
        ElectricCosting::find($electricCosting->id)->delete();
        ElectricCostingProduct::where('electric_costing_id', $electricCosting->id)->delete();
        return redirect()->route('electric_costing')->with('message', 'Info Deleted Successfully.');
    }

    public function electricCostingDatatable() {
        $query = ElectricCosting::with('project','estimateFloor','estimateFloorUnit');

        return DataTables::eloquent($query)
            ->addColumn('project_name', function(ElectricCosting $electricCosting) {
                return $electricCosting->project->name??'';
            })
            ->addColumn('estimate_floor', function(ElectricCosting $electricCosting) {
                return $electricCosting->estimateFloor->name??'';
            })
            ->addColumn('estimate_floor_unit', function(ElectricCosting $electricCosting) {
                return $electricCosting->estimateFloorUnit->name??'';
            })
            ->addColumn('action', function(ElectricCosting $electricCosting) {
                $btn = '';
                $btn = '<a href="'.route('electric_costing.details', ['electricCosting' => $electricCosting->id]).'" class="btn btn-primary btn-sm">Details</a>';
                $btn .= '<a href="'.route('electric_costing.delete', ['electricCosting' => $electricCosting->id]).'" onclick="return confirm(`Are you sure remove this item ?`)" class="btn btn-danger btn-sm btn_delete" style="margin-left: 3px;">Delete</a>';
                return $btn;
            })
            ->editColumn('date', function(ElectricCosting $electricCosting) {
                return $electricCosting->date;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
