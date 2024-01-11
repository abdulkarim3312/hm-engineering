<?php

namespace App\Http\Controllers;

use App\Model\EstimateProduct;
use App\Model\EstimateProject;
use App\Model\PurchaseProduct;
use App\Models\ExtraCosting;
use App\Models\ExtraCostingProduct;
use App\Models\MobilizationWork;
use App\Models\MobilizationWorkDetails;
use App\Models\MobilizationWorkProduct;
use Illuminate\Http\Request;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class MobilizationWorkController extends Controller
{
    public function index() {

        return view('estimate.mobilization_work.all');
    }

    public function add() {
        $projects = EstimateProject::where('status', 1)->orderBy('name')->get();
        // dd($projects);
        return view('estimate.mobilization_work.add', compact('projects'));
    }

    public function addPost(Request $request) {

        $request->validate([
                'project_id' => 'required',
                'product.*' => 'required',
                'cost_amount.*' => 'required|numeric|min:0',
            ]
        );


        $mobilizationWork = new MobilizationWork();
        $mobilizationWork->mobilization_project_id = $request->project_id;
        $mobilizationWork->date = $request->date;
        $mobilizationWork->note = $request->note;
        $mobilizationWork->total = 0;
        $mobilizationWork->save();
        $mobilizationWork->costing_no = str_pad($mobilizationWork->id, 6, "0", STR_PAD_LEFT);
        $mobilizationWork->save();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = MobilizationWorkProduct::find($reqProduct);

            $mobilizationWorkDetails = new MobilizationWorkDetails();
            $mobilizationWorkDetails->mobilization_work_id = $mobilizationWork->id;
            $mobilizationWorkDetails->mobilization_product_id = $product->id;
            $mobilizationWorkDetails->amount=$request->cost_amount[$counter];
            $mobilizationWorkDetails->save();

            $total += $request->cost_amount[$counter];
            $counter++;
        }
        $mobilizationWork->total = $total;
        $mobilizationWork->save();

        return redirect()->route('mobilization_work.details', ['mobilizationWork' => $mobilizationWork->id]);
    }
//    public function edit(Budget $budget){
//        $products = PurchaseProduct::where("status",1)->get();
//        $projects = Project::where('id', $budget->project_id)->orderBy('name')->get();
//        return view('budget.edit', compact('projects','budget','products'));
//    }

    public function edit(MobilizationWork $mobilizationWork) {
        $products = MobilizationWorkProduct::where('status', 1)->orderBy('name')->get();
        $projects = EstimateProject::where('status', 1)->orderBy('name')->get();
        return view('estimate.mobilization_work.edit', compact('projects','mobilizationWork','products'));
    }
    public function editPost(Request $request,MobilizationWork $mobilizationWork){

        $request->validate([
            'project_id' => 'required',
            'product.*' => 'required',
            'cost_amount.*' => 'required|numeric|min:0',
        ]);

        $mobilizationWork->mobilization_project_id = $request->project_id;
        $mobilizationWork->date = $request->date;
        $mobilizationWork->note = $request->note;
        $mobilizationWork->total = 0;
        $mobilizationWork->save();

        MobilizationWorkDetails::where('mobilization_work_id',$mobilizationWork->id)->delete();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = MobilizationWorkProduct::find($reqProduct);

            $mobilizationWorkDetails = new MobilizationWorkDetails();
            $mobilizationWorkDetails->mobilization_work_id = $mobilizationWork->id;
            $mobilizationWorkDetails->mobilization_product_id = $product->id;
            $mobilizationWorkDetails->amount=$request->cost_amount[$counter];
            $mobilizationWorkDetails->save();

            $total += $request->cost_amount[$counter];
            $counter++;
        }
        $mobilizationWork->total = $total;
        $mobilizationWork->save();

        return redirect()->route('mobilization_work.details', ['mobilizationWork' => $mobilizationWork->id]);

    }


    public function details(MobilizationWork $mobilizationWork) {

        $mobilizationWork->amount_in_word = DecimalToWords::convert($mobilizationWork->total,'Taka',
            'Poisa');
        return view('estimate.mobilization_work.details', compact('mobilizationWork'));
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
        $query = MobilizationWork::with('estimateProject');

        return DataTables::eloquent($query)
            ->addColumn('project', function(MobilizationWork $mobilizationWork) {
                return $mobilizationWork->estimateProject->name??'';
            })
            ->addColumn('action', function(MobilizationWork $mobilizationWork) {

                return '<a href="'.route('mobilization_work.details', ['mobilizationWork' => $mobilizationWork->id]).'" class="btn btn-primary btn-sm">View</a>
                        <a href="'.route('mobilization_work.edit', ['mobilizationWork' => $mobilizationWork->id]).'" class="btn btn-info btn-sm">Edit</a>';

            })
            ->editColumn('date', function(MobilizationWork $mobilizationWork) {
                return $mobilizationWork->date;
            })
            ->editColumn('total', function(MobilizationWork $mobilizationWork) {
                return ' '.number_format($mobilizationWork->total, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
