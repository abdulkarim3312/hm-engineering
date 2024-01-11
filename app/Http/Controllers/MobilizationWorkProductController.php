<?php

namespace App\Http\Controllers;

use App\Model\EstimateProduct;
use App\Models\MobilizationWorkProduct;
use Illuminate\Http\Request;

class MobilizationWorkProductController extends Controller
{
    public function index() {
        $products = MobilizationWorkProduct::get();
        return view('estimate.mobilization_product.all', compact('products'));
    }

    public function add() {
        return view('estimate.mobilization_product.add');
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $product = new MobilizationWorkProduct();
        $product->name = $request->name;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('mobilization_work_product')->with('message', 'Product add successfully.');
    }

    public function edit(MobilizationWorkProduct $product) {
        return view('estimate.mobilization_product.edit', compact( 'product'));
    }

    public function editPost(MobilizationWorkProduct $product, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('mobilization_work_product')->with('message', 'Product edit successfully.');
    }

}
