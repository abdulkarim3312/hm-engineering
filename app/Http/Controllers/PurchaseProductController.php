<?php

namespace App\Http\Controllers;

use App\Model\PurchaseProduct;
use App\Model\Unit;
use Illuminate\Http\Request;

class PurchaseProductController extends Controller
{
    public function index() {
        $products = PurchaseProduct::with('unit')->get();

        return view('purchase.product.all', compact('products'));
    }

    public function add() {
        $units = Unit::orderBy('name')->get();

        return view('purchase.product.add', compact('units'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $product = new PurchaseProduct();
        $product->name = $request->name;
        $product->unit_id = $request->unit;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('purchase_product')->with('message', 'Purchase product add successfully.');
    }

    public function edit(PurchaseProduct $product) {
        $units = Unit::orderBy('name')->get();

        return view('purchase.product.edit', compact('units', 'product'));
    }

    public function editPost(PurchaseProduct $product, Request $request) {
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

        return redirect()->route('purchase_product')->with('message', 'Purchase product edit successfully.');
    }
}
