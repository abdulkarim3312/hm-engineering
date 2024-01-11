<?php

namespace App\Http\Controllers;

use App\Model\Budget;
use App\Model\BudgetProduct;
use App\Model\Costing;
use App\Model\CostingType;
use App\Model\EstimateProduct;
use App\Model\EstimateProductCosting;
use App\Model\EstimateProject;
use App\Model\Project;
use App\Model\PurchaseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class
CostingController extends Controller
{
    public function index() {

        return view('estimate.costing.all');
    }

    public function add() {
        $estimateProjects = EstimateProject::where('status', 1)->orderBy('name')->get();
        $costingTypes = CostingType::where('status',1)->get();

        return view('estimate.costing.add', compact('estimateProjects','costingTypes'));
    }

    public function addPost(Request $request) {

        $request->validate([
                'estimate_project_id' => 'required',
                'costing_type_id' => 'required',
                'type_quantity' => 'required|numeric|min:0',
                'ratio' => 'required',
                'admixture_per_bag' => 'required|numeric|min:0',
                'volume' => 'required|numeric|min:0',
                'size' => 'required',
                'note' => 'nullable|max:255',
                'date' => 'required|date',
                'product.*' => 'required',
                'quantity.*' => 'required|numeric|min:0',
            ]
        );


        $costing = new Costing();
        $costing->estimate_costing_id = random_int(1000000,9999999);
        $costing->estimate_project_id = $request->estimate_project_id;
        $costing->costing_type_id = $request->costing_type_id;
        $costing->type_quantity = $request->type_quantity;
        $costing->admixture_per_bag = $request->admixture_per_bag;
        $costing->volume = $request->volume;
        $costing->size = $request->size;
        $costing->ratio = $request->ratio;
        $costing->date = $request->date;
        $costing->note = $request->note;
        $costing->total = 0;
        $costing->save();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = EstimateProduct::find($reqProduct);

            EstimateProductCosting::create([
                'costing_id' => $costing->id,
                'estimate_product_id' => $product->id,
                'name' => $product->name,
                'unit_id' => $product->unit->id,
                'unit_price' => $product->unit_price,
                'quantity' => $request->quantity[$counter],
                'costing_amount' => $product->unit_price * $request->quantity[$counter],
            ]);

            $total += $product->unit_price * $request->quantity[$counter];
            $counter++;
        }
        $costing->total = $total;
        $costing->save();

        return redirect()->route('costing.details', ['costing' => $costing->id]);
    }

    public function edit(Costing $costing){
        $products = EstimateProduct::where("status",1)->get();
        $estimateProjects = EstimateProject::where('id',$costing->estimate_project_id)->where('status', 1)->orderBy('name')->get();
        $costingTypes = CostingType::where('id',$costing->costing_type_id)->where('status',1)->get();
        return view('estimate.costing.edit', compact('estimateProjects','costing','costingTypes','products'));
    }
    public function editPost(Request $request,Costing $costing){
        $request->validate([
            'estimate_project_id' => 'required',
            'costing_type_id' => 'required',
            'type_quantity' => 'required|numeric|min:0',
            'ratio' => 'required',
            'admixture_per_bag' => 'required|numeric|min:0',
            'volume' => 'required|numeric|min:0',
            'size' => 'required',
            'note' => 'nullable|max:255',
            'date' => 'required|date',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:0',
        ]);

        $costing->estimate_project_id = $request->estimate_project_id;
        $costing->costing_type_id = $request->costing_type_id;
        $costing->type_quantity = $request->type_quantity;
        $costing->admixture_per_bag = $request->admixture_per_bag;
        $costing->volume = $request->volume;
        $costing->size = $request->size;
        $costing->ratio = $request->ratio;
        $costing->date = $request->date;
        $costing->note = $request->note;
        $costing->total = 0;
        $costing->save();

        EstimateProductCosting::where('costing_id',$costing->id)->delete();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = EstimateProduct::find($reqProduct);

            EstimateProductCosting::create([
                'costing_id' => $costing->id,
                'estimate_product_id' => $product->id,
                'name' => $product->name,
                'unit_id' => $product->unit->id,
                'unit_price' => $product->unit_price,
                'quantity' => $request->quantity[$counter],
                'costing_amount' => $product->unit_price * $request->quantity[$counter],
            ]);

            $total += $product->unit_price * $request->quantity[$counter];
            $counter++;
        }
        $costing->total = $total;
        $costing->save();

        return redirect()->route('costing.details', ['costing' => $costing->id]);

    }

    public function details(Costing $costing) {
        $costing->amount_in_word = DecimalToWords::convert($costing->total,'Taka',
            'Poisa');
        return view('estimate.costing.details', compact('costing'));
    }

    public function reportDetails(Budget $budget) {
        $budget->amount_in_word = DecimalToWords::convert($budget->total,'Taka',
            'Poisa');

        $budgetProducts = BudgetProduct::where('budget_id',$budget->id)->get();

        foreach ($budgetProducts as $budgetProduct){

            $totalProductExpenses = DB::table('purchase_order_purchase_product')->where('purchase_product_id',$budgetProduct->purchase_product_id)->get();

            return view('budget.report_details', compact('budget','totalProductExpenses'));

        }
    }

    public function costingDatatable() {
        $query = Costing::with('estimateProject');

        return DataTables::eloquent($query)
            ->addColumn('project', function(Costing $costing) {
                return $costing->estimateProject->name;
            })
            ->addColumn('action', function(Costing $costing) {

                return '<a href="'.route('costing.details', ['costing' => $costing->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('costing.edit', ['costing' => $costing->id]).'" class="btn btn-info btn-sm">Edit</a>';

            })
            ->editColumn('date', function(Costing $costing) {
                return $costing->date;
            })
            ->editColumn('total', function(Costing $costing) {
                return ' '.number_format($costing->total, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function costingReport(Request $request){

        $projects = EstimateProject::where('status',1)->get();
        $costingTypes = CostingType::where('status',1)->get();
        $costings = [];
        $expenses = null;

        if (!empty($request->project)) {
            $costings = Costing::with('estimateProject')->where('estimate_project_id',$request->project)->get();
        }
        return view('estimate.report.costing_report', compact('projects','costings','costingTypes'));

    }

    public function estimateProductJson(Request $request) {
        if (!$request->searchTerm) {
            $products = EstimateProduct::where('status', 1)->orderBy('name')->limit(10)->get();
        } else {
            $products = EstimateProduct::where('status', 1)->where('name', 'like', '%'.$request->searchTerm.'%')->orderBy('name')->limit(10)->get();
        }

        $data = array();

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'unit_price' => $product->unit_price,
                'text' => $product->name
            ];
        }

        echo json_encode($data);
    }

    public function estimateProductDetails(Request $request) {
        $product = EstimateProduct::where('id', $request->productId)
            ->first();

        if ($product) {
            $product = $product->toArray();
            return response()->json(['success' => true, 'data' => $product]);
        } else {
            return response()->json(['success' => false, 'message' => 'Not found.']);
        }
    }
}
