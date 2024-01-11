<?php

namespace App\Http\Controllers;

use App\Model\EstimateProduct;
use App\Model\Unit;
use App\Models\EstimateProductType;
use Illuminate\Http\Request;

class EstimateProductController extends Controller
{
    public function index() {
        $products = EstimateProduct::get();
        return view('estimate.product.all', compact('products'));
    }

    public function add() {
        $units = Unit::where('status',1)->get();
        return view('estimate.product.add',compact('units'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $product = new EstimateProduct();
        $product->name = $request->name;
        $product->unit_id = $request->unit;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('estimate_product')->with('message', 'Estimate product add successfully.');
    }

    public function edit(EstimateProduct $product) {
        $units = Unit::where('status',1)->get();
        return view('estimate.product.edit', compact( 'product','units'));
    }

    public function editPost(EstimateProduct $product, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->unit_id = $request->unit;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('estimate_product')->with('message', 'Estimate product edit successfully.');
    }


    public function estimateProductType() {
        $products = EstimateProductType::get();
        return view('estimate.product_type.all', compact('products'));
    }

    public function estimateProductTypeAdd() {
        $estimateProducts = EstimateProduct::where('status',1)->get();
        return view('estimate.product_type.add',compact('estimateProducts'));
    }

    public function estimateProductTypeAddPost(Request $request) {

        $request->validate([
            'estimate_product' => 'required',
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $product = new EstimateProductType();
        $product->estimate_product_id = $request->estimate_product;
        $product->name = $request->name;
        $product->unit_price = $request->unit_price;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('estimate_product_type')->with('message', 'Estimate product Type add successfully.');
    }

    public function estimateProductTypeAddEdit(EstimateProductType $estimateProductType) {
        $estimateProducts = EstimateProduct::where('status',1)->get();
        return view('estimate.product_type.edit', compact( 'estimateProductType','estimateProducts'));
    }

    public function estimateProductTypeAddEditPost(EstimateProductType $estimateProductType, Request $request) {
        $request->validate([
            'estimate_product' => 'required',
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $estimateProductType->estimate_product_id = $request->estimate_product;
        $estimateProductType->name = $request->name;
        $estimateProductType->unit_price = $request->unit_price;
        $estimateProductType->description = $request->description;
        $estimateProductType->status = $request->status;
        $estimateProductType->save();

        return redirect()->route('estimate_product_type')->with('message', 'Estimate product Type edit successfully.');
    }
}
