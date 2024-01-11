<?php

namespace App\Http\Controllers;

use App\Model\Budget;
use App\Model\BudgetProduct;
use App\Model\Project;
use App\Model\PurchaseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class BudgetController extends Controller
{
    public function index() {

        return view('budget.all');
    }

    public function add() {
        $projects = Project::where('status', 1)->orderBy('name')->get();

        return view('budget.add', compact('projects'));
    }

    public function addPost(Request $request) {

        $request->validate([
            'project_id' => 'required|unique:budgets',
            'note' => 'nullable|max:255',
            'date' => 'required|date',
            'product.*' => 'required',
            'budget_amount.*' => 'required|numeric|min:0',
        ]
    );


        $budget = new Budget();
        $budget->project_id = $request->project_id;
        $budget->date = $request->date;
        $budget->note = $request->note;
        $budget->total = 0;
        $budget->save();

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

    public function details(Budget $budget) {
        $budget->amount_in_word = DecimalToWords::convert($budget->total,'Taka',
            'Poisa');
        return view('budget.details', compact('budget'));
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

    public function budgetDatatable() {
        $query = Budget::with('project');

        return DataTables::eloquent($query)
            ->addColumn('project', function(Budget $budget) {
                return $budget->project->name;
            })
            ->addColumn('action', function(Budget $budget) {

                return '<a href="'.route('budget.details', ['budget' => $budget->id]).'" class="btn btn-primary btn-sm">View</a> <a href="'.route('budget.edit', ['budget' => $budget->id]).'" class="btn btn-info btn-sm">Edit</a>';

            })
            ->editColumn('date', function(Budget $budget) {
                return $budget->date;
            })
            ->editColumn('total', function(Budget $budget) {
                return ' '.number_format($budget->total, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
