<?php

namespace App\Http\Controllers;

use App\Model\EstimateProduct;
use App\Model\EstimateProject;
use App\Model\PurchaseProduct;
use App\Models\ExtraCosting;
use App\Models\ExtraCostingProduct;
use App\Models\ExtraCostProduct;
use App\Models\MobilizationWorkProduct;
use Illuminate\Http\Request;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class ExtraCostingController extends Controller
{
    public function index() {

        return view('estimate.extra_costing.all');
    }

    public function add() {
        $projects = EstimateProject::where('status', 1)->orderBy('name')->get();
        $products = ExtraCostProduct::where('status', 1)->orderBy('name')->get();
        // dd($products);
        return view('estimate.extra_costing.add', compact('projects', 'products'));
    }

    // public function addPost(Request $request) {

    //     $request->validate([
    //             'project_id' => 'required',
    //             'note' => 'nullable|max:255',
    //             'date' => 'required|date',
    //             'product.*' => 'required',
    //             'quantity.*' => 'required',
    //             'extra_costing_per_unit.*' => 'required|numeric|min:0',
    //         ]
    //     );


    //     $extraCosting = new ExtraCosting();
    //     $extraCosting->estimate_project_id = $request->project_id;
    //     $extraCosting->date = $request->date;
    //     $extraCosting->note = $request->note;
    //     $extraCosting->total = 0;
    //     $extraCosting->save();
    //     $extraCosting->costing_no = str_pad($extraCosting->id, 6, "0", STR_PAD_LEFT);
    //     $extraCosting->save();

    //     $counter = 0;
    //     $total = 0;
    //     foreach ($request->product as $reqProduct) {
    //         $product = EstimateProduct::find($reqProduct);

    //         ExtraCostingProduct::create([
    //             'extra_costing_id' => $extraCosting->id,
    //             'estimate_product_id' => $product->id,
    //             'name' => $product->name,
    //             'unit_id' => $product->unit->id,
    //             'quantity' => $request->quantity[$counter],
    //             'costing_amount_per_unit' => $request->extra_costing_per_unit[$counter],
    //             'total' => $request->extra_costing_per_unit[$counter] * $request->quantity[$counter],
    //         ]);

    //         $total += $request->extra_costing_per_unit[$counter] * $request->quantity[$counter];
    //         $counter++;
    //     }
    //     $extraCosting->total = $total;
    //     $extraCosting->save();

    //     return redirect()->route('extra_costing.details', ['extraCosting' => $extraCosting->id]);
    // }


    public function addPost(Request $request) {
        // dd($request->all());
        $request->validate([
                'project_id' => 'required',
                'product.*' => 'required',
                'cost_amount.*' => 'required|numeric|min:0',
            ]
        );


        $extraCosting = new ExtraCosting();
        $extraCosting->estimate_project_id = $request->project_id;
        $extraCosting->date = $request->date;
        $extraCosting->note = $request->note;
        $extraCosting->total = 0;
        $extraCosting->save();
        $extraCosting->costing_no = str_pad($extraCosting->id, 6, "0", STR_PAD_LEFT);
        $extraCosting->save();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = EstimateProduct::find($reqProduct);

            ExtraCostingProduct::create([
                'extra_costing_id' => $extraCosting->id,
                'estimate_product_id' => $product->id,
                'name' => $product->name,
                'unit_id' => $product->unit_amount,
                // 'quantity' => $request->quantity[$counter],
                // 'costing_amount_per_unit' => $request->extra_costing_per_unit[$counter],
                'total' => $request->cost_amount[$counter]
            ]);

            $total += $request->cost_amount[$counter];
            $counter++;
        }
        $extraCosting->total = $total;
        $extraCosting->save();

        return redirect()->route('extra_costing.details', ['extraCosting' => $extraCosting->id]);
    }






    public function edit(Budget $budget){
        $products = PurchaseProduct::where("status",1)->get();
        $projects = Project::where('id', $budget->project_id)->orderBy('name')->get();
        return view('budget.edit', compact('projects','budget','products'));
    }
    public function editPost(Request $request,Budget $budget){
        $request->validate([
            'project' => 'required',
            'note' => 'nullable|max:255',
            'date' => 'required|date',
            'product.*' => 'required',
            'budget_amount.*' => 'required|numeric|min:0',
        ]);

        $budget->project_id = $request->project;
        $budget->date = $request->date;
        $budget->note = $request->note;
        $budget->total = 0;
        $budget->save();

        BudgetProduct::where('budget_id',$budget->id)->delete();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = PurchaseProduct::find($reqProduct);

            BudgetProduct::create([
                'budget_id' => $budget->id,
                'purchase_product_id' => $product->id,
                'name' => $product->name,
                'unit_name' => $product->unit->name,
                'budget_amount' => $request->budget_amount[$counter],
            ]);

            $total += $request->budget_amount[$counter];
            $counter++;
        }

        $budget->total = $total;
        $budget->save();

        return redirect()->route('budget.details', ['budget' => $budget->id]);

    }

    public function details(ExtraCosting $extraCosting) {
        $extraCosting->amount_in_word = DecimalToWords::convert($extraCosting->total,'Taka',
            'Poisa');
        return view('estimate.extra_costing.details', compact('extraCosting'));
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

    public function extraCostingDatatable() {
        $query = ExtraCosting::with('estimateProject');

        return DataTables::eloquent($query)
            ->addColumn('project', function(ExtraCosting $extraCosting) {
                return $extraCosting->estimateProject->name??'';
            })
            ->addColumn('action', function(ExtraCosting $extraCosting) {

                return '<a href="'.route('extra_costing.details', ['extraCosting' => $extraCosting->id]).'" class="btn btn-primary btn-sm">View</a>';
//<a href="'.route('budget.edit', ['budget' => $budget->id]).'" class="btn btn-info btn-sm">Edit</a>';

            })
            ->editColumn('date', function(ExtraCosting $extraCosting) {
                return $extraCosting->date;
            })
            ->editColumn('total', function(ExtraCosting $extraCosting) {
                return ' '.number_format($extraCosting->total, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function extraCostProductIndex() {
        $products = ExtraCostProduct::get();
        return view('estimate.extra_cost_product.all', compact('products'));
    }

    public function extraCostProductAdd() {
        return view('estimate.extra_cost_product.add');
    }

    public function extraCostProductPost(Request $request) {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $product = new ExtraCostProduct();
        $product->name = $request->name;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('extra_cost_product')->with('message', 'Product add successfully.');
    }

    public function extraCostProductEdit(ExtraCostProduct $product) {
        // dd($product);
        return view('estimate.extra_cost_product.edit', compact( 'product'));
    }

    public function extraCostProductEditPost(ExtraCostProduct $product, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('extra_cost_product')->with('message', 'Product edit successfully.');
    }
}
