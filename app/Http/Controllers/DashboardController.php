<?php

namespace App\Http\Controllers;

use App\Model\Cash;
use App\Model\Client;
use App\Model\PurchaseOrder;
use App\Model\SalePayment;
use App\Model\SalesOrder;
use App\Model\TransactionLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {

        $todaySale = SalesOrder::whereDate('date', date('Y-m-d'))->sum('total');
        $totalSale = SalesOrder::sum('total');
        $todayDue = SalesOrder::whereDate('date', date('Y-m-d'))->sum('due');
        $todayExpense = TransactionLog::whereDate('date', date('Y-m-d'))
            ->whereIn('transaction_type', [2])->sum('amount');
        $totalExpense = TransactionLog::whereIn('transaction_type', [2])->sum('amount');
        $todayPurchaseReceipt = PurchaseOrder::whereDate('date', date('Y-m-d'))
            ->with('supplier')
            ->orderBy('created_at', 'desc')->paginate(10);
        $todayPurchaseReceipt->setPageName('purchase_receipt');
        $todaySaleReceipt = SalesOrder::whereDate('date', date('Y-m-d'))
        ->with('client')
        ->orderBy('created_at', 'desc')->paginate(10);
        $todaySaleReceipt->setPageName('sale_receipt');


        $data = [
            'todaySale' => $todaySale,
            'todayDue' => $todayDue,
            'totalSale' => $totalSale,
            'todayExpense' => $todayExpense,
            'totalExpense' => $totalExpense,
            'todayPurchaseReceipt' => $todayPurchaseReceipt,
            'todaySaleReceipt' => $todaySaleReceipt,
        ];
        return view('dashboard', $data);
    }

    public function paymentDetails()
    {
        return view('payment_details');
    }
}
