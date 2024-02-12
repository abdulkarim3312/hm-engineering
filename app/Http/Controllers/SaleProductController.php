<?php

namespace App\Http\Controllers;

use App\Model\Project;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseProduct;
use App\Model\Unit;
use App\Model\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleProductController extends Controller
{
    public function index() {
        $products = SaleProduct::with('unit')->get();

        return view('sale.product.all', compact('products'));
    }

    public function add() {
        $units = Unit::orderBy('name')->get();

        return view('sale.product.add', compact('units'));
    }

    public function addPost(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'price' => 'required|numeric',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $product = new SaleProduct();
        $product->name = $request->name;
        $product->unit_id = $request->unit;
        $product->price = $request->price;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('sale_product')->with('message', 'Sale product add successfully.');
    }

    public function edit(SaleProduct $product) {
        $units = Unit::orderBy('name')->get();

        return view('sale.product.edit', compact('units', 'product'));
    }

    public function editPost(SaleProduct $product, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'price' => 'required|numeric',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->unit_id = $request->unit;
        $product->price = $request->price;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('sale_product')->with('message', 'Sale product edit successfully.');
    }

    public function scrapProduct(){
        return view('sale.scrap_product.all');
    }
    public function scrapProductAdd(){
        $projects = PurchaseInventory::with('project')
            ->select('project_id')
            ->groupBy('project_id')
            ->get();
        $warehouses = Warehouse::all();
        $products = PurchaseProduct::all();
        return view('sale.scrap_product.add', compact('projects','warehouses','products'));
    }

    public function scrapProductAddPost(Request $request){
        $request->validate([
            'project.*' => 'required',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:.01',
            'unit_price.*' => 'required|numeric|min:.01',
            'date' => 'required'
        ]);


        $counter = 0;

        foreach ($request->project as $reqProject) {

           $purchaseInventory =  PurchaseInventory::where('project_id',$reqProject)
                ->where('purchase_product_id', $request->product[$counter])
                ->where('scrap_status', 2)
                ->first();

           if ($purchaseInventory){
               $purchaseInventory->increment('quantity',$request->quantity[$counter]);
               $purchaseInventory->update([
                   'last_unit_price' => $request->unit_price[$counter],
                   'avg_unit_price' => $request->unit_price[$counter],
               ]);

               $inventoryLog = new PurchaseInventoryLog();
               $inventoryLog->purchase_product_id = $request->product[$counter];
               $inventoryLog->project_id =  $request->project[$counter];
               $inventoryLog->type = 6;//scrap In
               $inventoryLog->date = $request->date;
               $inventoryLog->quantity = $request->quantity[$counter];
               $inventoryLog->unit_price = $request->unit_price[$counter];
               $inventoryLog->save();

           }else{
               $inventory = new PurchaseInventory();
               $inventory->purchase_product_id = $request->product[$counter];
               $inventory->project_id = $reqProject;
               $inventory->quantity = $request->quantity[$counter];
               $inventory->last_unit_price = $request->unit_price[$counter];
               $inventory->avg_unit_price = $request->unit_price[$counter];
               $inventory->scrap_status = 2;
               $inventory->save();

               $inventoryLog = new PurchaseInventoryLog();
               $inventoryLog->purchase_product_id = $request->product[$counter];
               $inventoryLog->project_id =  $request->project[$counter];
               $inventoryLog->type = 6;//scrap In
               $inventoryLog->date = $request->date;
               $inventoryLog->quantity = $request->quantity[$counter];
               $inventoryLog->unit_price = $request->unit_price[$counter];
               $inventoryLog->save();
           }

            $counter++;
        }

        return redirect()->route('scrap_product.all')->with('message', 'Scrap product add successfully.');


    }

    public function scrapProductDatatable() {

        $query = PurchaseInventory::with('warehouse','product','project','segment')
        ->where('scrap_status',2);

        return DataTables::eloquent($query)
            ->addColumn('product', function(PurchaseInventory $inventory) {
                return $inventory->product->name;
            })
            ->addColumn('project', function(PurchaseInventory $inventory) {
                return $inventory->project->name;
            })
            ->addColumn('unit', function(PurchaseInventory $inventory) {
                return $inventory->product->unit->name;
            })
            ->addColumn('action', function(PurchaseInventory $inventory) {
                return '<a href="'.route('purchase_inventory.details',
                        ['project' => $inventory->project_id,'product' => $inventory->purchase_product_id]).'" class="btn btn-primary btn-sm">Details</a>';
            })

            ->addColumn('total', function(PurchaseInventory $inventory) {
                return ' '.number_format($inventory->last_unit_price * $inventory->quantity, 2);
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
