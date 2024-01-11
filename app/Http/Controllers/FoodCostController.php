<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\Cash;
use App\Model\FoodCost;
use App\Model\FoodCostItem;
use App\Model\Labour;
use App\Model\TransactionLog;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class FoodCostController extends Controller
{

    public function index() {

        return view('labour.food_cost.all');
    }

    public function add() {
        $labours = Labour::orderBy('name')->get();
//        $banks = Bank::where('status', 1)->orderBy('name')->get();

        return view('labour.food_cost.add', compact('labours'));
    }

    public function addPost(Request $request) {

        $rules = [
            'date' => 'required|date',
            'financial_year' => 'required',
            'payment_type' => 'required',
            'employee.*' => 'required',
            'food_cost.*' => 'required|numeric|min:0',
            'advance.*' => 'required|numeric|min:0',
            'received_by.*' => 'required',
        ];

        if ($request->payment_type == 1){
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
        }

        $request->validate($rules);

        $foodCost = new FoodCost();
        $foodCost->food_cost_no = random_int(1000000,9999999);
        $foodCost->date = $request->date;
        $foodCost->financial_year = financialYear($request->financial_year);
        $foodCost->payment_type = $request->payment_type;
        $foodCost->account_head_id = $request->account;
        $foodCost->note = $request->note;
        $foodCost->total = 0;
        $foodCost->save();

        $counter = 0;
        $total = 0;

        foreach ($request->employee as $employee) {
            $labour = Labour::find($employee);
            FoodCostItem::create([
                'food_cost_id' => $foodCost->id,
                'labour_employee_id' => $labour->id,
                'date' => $request->date,
                'food_cost' => $request->food_cost[$counter],
                'advance' => $request->advance[$counter],
                'received_by' => $request->received_by[$counter],
            ]);

            $total += ($request->food_cost[$counter] + $request->advance[$counter]);

            $counter++;
        }
        $foodCost->total = $total;
        $foodCost->save();


        return redirect()->route('food_cost.details', ['foodCost' => $foodCost->id]);
    }

    public function edit(FoodCost $foodCost){
        $products = EstimateProduct::where("status",1)->get();
        $estimateProjects = EstimateProject::where('id',$foodCost->estimate_project_id)->where('status', 1)->orderBy('name')->get();
        $foodCostTypes = FoodCostType::where('id',$foodCost->foodCost_type_id)->where('status',1)->get();
        return view('estimate.foodCost.edit', compact('estimateProjects','foodCost','foodCostTypes','products'));
    }
    public function editPost(Request $request,FoodCost $foodCost){
        $request->validate([
            'estimate_project_id' => 'required',
            'foodCost_type_id' => 'required',
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

        $foodCost->estimate_project_id = $request->estimate_project_id;
        $foodCost->foodCost_type_id = $request->foodCost_type_id;
        $foodCost->type_quantity = $request->type_quantity;
        $foodCost->admixture_per_bag = $request->admixture_per_bag;
        $foodCost->volume = $request->volume;
        $foodCost->size = $request->size;
        $foodCost->ratio = $request->ratio;
        $foodCost->date = $request->date;
        $foodCost->note = $request->note;
        $foodCost->total = 0;
        $foodCost->save();

        EstimateProductFoodCost::where('foodCost_id',$foodCost->id)->delete();

        $counter = 0;
        $total = 0;
        foreach ($request->product as $reqProduct) {
            $product = EstimateProduct::find($reqProduct);

            EstimateProductFoodCost::create([
                'foodCost_id' => $foodCost->id,
                'estimate_product_id' => $product->id,
                'name' => $product->name,
                'unit_id' => $product->unit->id,
                'unit_price' => $product->unit_price,
                'quantity' => $request->quantity[$counter],
                'foodCost_amount' => $product->unit_price * $request->quantity[$counter],
            ]);

            $total += $product->unit_price * $request->quantity[$counter];
            $counter++;
        }
        $foodCost->total = $total;
        $foodCost->save();

        return redirect()->route('foodCost.details', ['foodCost' => $foodCost->id]);

    }

    public function details(FoodCost $foodCost) {
        $foodCost->amount_in_word = DecimalToWords::convert($foodCost->total,'Taka',
            'Poisa');
        return view('labour.food_cost.details', compact('foodCost'));
    }

    public function foodCostDatatable() {
        $query = FoodCost::query();


        return DataTables::eloquent($query)

            ->addColumn('action', function(FoodCost $foodCost) {

                return '<a href="'.route('food_cost.details', ['foodCost' => $foodCost->id]).'" class="btn btn-primary btn-sm">View</a>';

//                <a href="'.route('foodCost.edit', ['foodCost' => $foodCost->id]).'" class="btn btn-info btn-sm">Edit</a>

            })
            ->editColumn('date', function(FoodCost $foodCost) {
                return $foodCost->date;
            })
            ->editColumn('total', function(FoodCost $foodCost) {
                return ' '.number_format($foodCost->total, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }



}
