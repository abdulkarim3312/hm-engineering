<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseProduct;
use App\Model\SalesOrder;
use App\Model\TransactionLog;
use App\Models\AccountHead;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use App\Models\ReceiptPaymentFile;
use App\Models\SalesOrderDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\DataTables;

class TradingSaleController extends Controller
{
    public function tradingSaleOrder(){
        $customers = Client::where('type',1)->where('status', 1)->orderBy('name')->get();
        $products = PurchaseProduct::where('status', 1)->get();
        $accountHeads = AccountHead::orderBy('account_code')
                ->where('account_head_type_id',1)
                ->limit(50)
                ->get();
        // dd($accountHeads);
        return view('trading_sale.sale_order.create', compact('customers','products', 'accountHeads'));
    }

    public function stockInventoryDetails(Request $request){
        $product = PurchaseInventory::where('purchase_product_id', $request->productId)
            ->where('warehouse_id', $request->warehouseId)
            ->whereNull('project_id')
            ->where('quantity', '>', 0)
            ->with('product')
            ->first();
        //dd($product);

        if ($product) {
            $product = $product->toArray();
            return response()->json(['success' => true, 'data' => $product, 'count' => $product['quantity']]);
        } else {
            return response()->json(['success' => false, 'message' => 'Not found.']);
        }
    }

    public function tradingSaleOrderPost(Request $request)
    {
        $total = $request->total;
        $due = $request->due_total;
        $rules = [
            'date' => 'required|date',
        ];

        $rules = [
            'customer' => 'required',
            'warehouse' => 'required',
            'note' => 'nullable|max:255',
            'date' => 'required|date',
            'supporting_document' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'product.*' => 'required',
            'unit.*' => 'nullable',
            'quantity.*' => 'required|numeric|min:.01',
            'unit_price.*' => 'required|numeric|min:0',
            'selling_price.*' => 'required|numeric|min:0',
        ];

        if ($request->payment_type == 1) {
            $rules['cheque_date'] = 'required|date';
            $rules['cheque_no'] = 'required|string|max:255';
            $rules['cheque_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            $rules['issuing_bank_name'] = 'required';
            $rules['issuing_branch_name'] = 'required';
        }
        if ($due > 0)
            $rules['next_payment'] = 'required|date';

        $request->validate($rules);

        $available = true;
        $message = '';

        if (!empty($request->product)) {
            foreach ($request->product as $index => $reqProduct) {
                $inventory = PurchaseInventory::with('product')
                    ->where('purchase_product_id', $reqProduct)
                    ->where('warehouse_id', $request->warehouse)
                    ->whereNull('project_id')
                    ->first();
                if ($inventory) {
                    if ($request->quantity[$index] > $inventory->quantity) {
                        $available = false;
                        $message = 'Insufficient ' . $inventory->product->name;
                        break;
                    }
                }
            }
        }

        if (!$available) {
            return redirect()->back()->withInput()->with('message', $message);
        }

        $customer = Client::find($request->customer);

        $order = new SalesOrder();
        $order->client_id = $customer->id;
        $order->warehouse_id = $request->warehouse;
        $order->received_by = $request->received_by;
        $order->date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : null;
        $order->note = $request->note;
        $order->sub_total = 0;
        $order->vat_percent = $request->vat;
        $order->vat = 0;
        $order->discount_percentage = $request->discount_percentage;
        $order->discount = $request->discount;
        $order->transport_cost = $request->transport_cost;
        $order->total = 0;
        $order->paid = $request->paid;
        $order->due = 0;
        $order->save();
        $order->order_no = 'HM-B-' . date('M-Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $order->save();
        $subTotal = 0;
        if (!empty($request->product)) {
            foreach ($request->product as $key => $reqProduct) {
                // Check if the arrays have elements at the current index
                if (isset($request->unit[$key]) && isset($request->quantity[$key]) && isset($request->unit_price[$key])) {
                    $product = PurchaseProduct::find($reqProduct);

                    $inventory = PurchaseInventory::where('purchase_product_id', $request->product[$key])
                        ->where('warehouse_id', $request->warehouse)
                        ->with('product')
                        ->first();
                    // dd($inventory);
                    SalesOrderDetails::create([
                        'sales_order_id' => $order->id,
                        'purchase_product_id' => $product->id,
                        'warehouse_id' => $request->warehouse,
                        'customer_id' => $request->customer,
                        'name' => $product->name,
                        'unit' => $request->unit[$key] ?? 0,
                        'quantity' => $request->quantity[$key],
                        'unit_price' => $request->unit_price[$key],
                        'total' => $request->quantity[$key] * $request->unit_price[$key],
                    ]);

                    $subTotal += $request->quantity[$key] * $request->unit_price[$key];

                    $inventoryLog = new PurchaseInventoryLog();
                    $inventoryLog->sales_order_id = $order->id;
                    $inventoryLog->purchase_product_id = $product->id;
                    // $inventoryLog->purchase_inventory_id = $inventory->id;
                    $inventoryLog->warehouse_id = $request->warehouse;
                    $inventoryLog->customer_id = $request->customer;
                    $inventoryLog->type = 2;
                    $inventoryLog->date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : null;
                    $inventoryLog->unit = $request->unit[$key] ?? 0;
                    $inventoryLog->quantity = $request->quantity[$key];
                    $inventoryLog->unit_price = $request->unit_price[$key];
                    $inventoryLog->total = $request->quantity[$key] * $request->unit_price[$key];
                    $inventoryLog->note = 'Sale Product';
                    $inventoryLog->save();

                    $inventory->decrement('quantity', $request->quantity[$key]);
                }
            }
        }

        $order->sub_total = $subTotal;
        $vat = ($subTotal * $request->vat) / 100;
        $order->vat = $vat;
        $total = $subTotal + $request->transport_cost + $vat - $request->discount;
        $order->total = $total;
        $due = $total - $request->paid;
        $order->due = $due;
        $order->next_date = $due > 0 ? Carbon::parse($request->next_payment)->format('Y-m-d') : null;
        $order->save();

        return redirect()->route('trading_sale_receipt.details', ['order' => $order->id]);
    }

    public function TradingSalesReceipt(){
        return view('trading_sale.receipt.all');
    }

    public function TradingSalesReceiptDatatable(){
        $query = SalesOrder::with('client');
        // dd($query);
        $dataTable = new DataTables();

        return $dataTable->eloquent($query)
            ->addColumn('customer_name', function (SalesOrder $order) {
                return $order->client->name ?? '';
            })
            ->addColumn('action', function (SalesOrder $order) {
                $btn = '<a href="' . route('trading_sale_receipt.details', ['order' => $order->id]) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> </a> ';
                return $btn;
            })
            ->addColumn('status', function (SalesOrder $order) {

            })
            ->editColumn('total', function (SalesOrder $order) {
                return '৳' . number_format($order->total, 2);
            })
            ->editColumn('paid', function (SalesOrder $order) {
                return '৳' . number_format($order->paid, 2);
            })
            ->editColumn('due', function (SalesOrder $order) {
                return '৳' . number_format($order->due, 2);
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    public function tradingSaleReceiptDetails(SalesOrder $order) {
        $order->amount_in_word = DecimalToWords::convert($order->total,'Taka',
            'Poisa');
        return view('trading_sale.receipt.details', compact('order'));
    }

    public function tradingSaleReceiptDetailsPrint(SalesOrder $order) {
        return view('trading_sale.receipt.print', compact('order'));
    }

}
