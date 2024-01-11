<?php

namespace App\Http\Controllers;

use App\FlatSalesOrder;
use App\Model\AccountHeadType;
use App\Model\AccountHeadSubType;
use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Branch;
use App\Model\Budget;
use App\Model\Cash;
use App\Model\Client;
use App\Model\Customer;
use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Model\EmployeeSalaryAdvance;
use App\Model\EmployeeWiseBill;
use App\Model\FoodCost;
use App\Model\FoodCostItem;
use App\Model\LabourBill;
use App\Model\MobileBanking;
use App\Model\Project;
use App\Model\PurchaseInventory;
use App\Model\PurchaseInventoryLog;
use App\Model\PurchaseOrder;
use App\Model\PurchasePayment;
use App\Model\PurchaseProduct;
use App\Model\Salary;
use App\Model\SalaryProcess;
use App\Model\SaleInventory;
use App\Model\SaleInventoryLog;
use App\Model\SalePayment;
use App\Model\SaleProduct;
use App\Model\SaleProductStock;
use App\Model\SalesOrder;
use App\Model\Supplier;
use App\Model\Transaction;
use App\Model\TransactionLog;
use App\Models\AccountHead;
use App\Models\ReceiptPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB as FacadesDB;
use SakibRahaman\DecimalToWords\DecimalToWords;

class ReportController extends Controller
{
    // public function ledger(Request $request)
    // {

    //     $startDate = date('Y-m-d', strtotime($request->start_date));
    //     $endDate = date('Y-m-d', strtotime($request->end_date));

    //      $month = strtotime($startDate);
    //     $end = strtotime($endDate);
    //     $monthsArray = [];
    //     while($month <= $end)
    //     {
    //         $monthGenerate = date('Y-m', $month);
    //         array_push($monthsArray,$monthGenerate);
    //         $month = strtotime("+1 month", $month);

    //     }

    //     $in_word = new DecimalToWords();

    //     $currentMonth = date('m');
    //     if ($currentMonth < 7){
    //         $currentYear = date('Y') - 1;
    //         $currentDate = date($currentYear.'-07-01');
    //     }else{
    //         $currentDate = date('Y-07-01');
    //     }

    //     $accountHeads = AccountHead::orderBy('account_code')->get();

    //     $query = AccountHead::query();

    //     if ($request->search != '' && $request->account_head != ''){
    //         $query->where('id',$request->account_head);
    //     }
    //     $accountHeadsSearch = $query->orderBy('account_code')
    //                             ->get();

    //     return view('report.ledger', compact('accountHeadsSearch','accountHeads','monthsArray','currentDate'));
    // }
    public function ledger(Request $request)
    {
        // dd($request->all());

        $startDate = date('Y-m-d', strtotime($request->start_date));
        $endDate = date('Y-m-d', strtotime($request->end_date));

        $month = strtotime($startDate);
        $end = strtotime($endDate);
        $monthsArray = [];
        while($month <= $end)
        {
            $monthGenerate = date('Y-m', $month);
            array_push($monthsArray,$monthGenerate);
            $month = strtotime("+1 month", $month);

        }

        $in_word = new DecimalToWords();

        $currentMonth = date('m');
        if ($currentMonth < 7){
            $currentYear = date('Y') - 1;
            $currentDate = date($currentYear.'-07-01');
        }else{
            $currentDate = date('Y-07-01');
        }

        $accountHeads = AccountHead::orderBy('account_code')->get();
        $query = AccountHead::query();

        if ($request->search != '' && $request->account_head != ''){
            $query->where('id',$request->account_head);
        }
        $accountHeadsSearch = $query->orderBy('account_code')
                                ->get();

        return view('report.ledger', compact('accountHeadsSearch','accountHeads','monthsArray','currentDate'));
    }

    public function trailBalance(Request $request)
    {

        $accountHeads = AccountHead::orderBy('account_code')->get();

        $query = AccountHead::query();
        if ($request->search != '' && $request->account_head != ''){
            $query->where('id',$request->account_head);
        }
        $accountHeadsSearch = $query->orderBy('account_code')
                                ->get();


        $in_word = new DecimalToWords();

        $currentMonth = date('m');
        if ($currentMonth < 7){
            $currentYear = date('Y') - 1;
            $currentDate = date($currentYear.'-07-01');
        }else{
            $currentDate = date('Y-07-01');
        }


        return view('report.trail_balance', compact('currentDate',
            'accountHeads','in_word',
                    'accountHeadsSearch'));
    }

    public function receivePayment(Request $request){

        $receipts = [];
        $payments = [];

        if ( $request->start!='' && $request->end!='') {
            $receipts = ReceiptPayment::where('transaction_type',1)
                ->whereBetween('date', [$request->start, $request->end])
                ->get();

            $payments = ReceiptPayment::where('transaction_type',2)
                ->whereBetween('date', [$request->start, $request->end])
                ->get();

        }
//        else{
//            $receipts = ReceiptPayment::where('transaction_type',1)->get();
//            $payments = ReceiptPayment::where('transaction_type',2)->get();
//        }
        return view('report.receive_payment', compact('receipts','payments'));
    }


    public function employeeAttendance(Request $request)
    {
        $employees = Employee::all();

        $attendances=[];

        if ( $request->start!='' && $request->end!='') {
            $attendances = EmployeeAttendance::whereDate('date','>=',$request->start)
                ->whereDate('date','<=', $request->end)
                ->where('status',1)
                ->get();


        }
        if ($request->start!='' && $request->end!='' && $request->employee != ''){
            $attendances = EmployeeAttendance::whereDate('date','>=',$request->start)
                ->where('employee_id', $request->employee)
                ->whereDate('date','<=', $request->end)
                ->where('status',1)
                ->get();

        }


        return view('report.employee_attendance',compact('attendances','employees'));
    }
        public function employeeAttendanceInOut(Request $request)
    {
        $employees = Employee::all();

        $attendances=[];

        if ($request->start!='' && $request->end!='' && $request->employee != ''){
            $attendances = EmployeeAttendance::select('employee_id', 'date', DB::raw('COUNT(*) as count'))
                ->whereDate('date','>=',$request->start)
                ->where('employee_id', $request->employee)
                ->whereDate('date','<=', $request->end)
                ->groupBy('employee_id', 'date')
                ->get();
        }


        return view('report.employee_attendance_in_out',compact('attendances','employees'));
    }


    public function clientStatement()
    {
        $clients=Client::where('status',1)
            ->where('type',1)
            ->where('total','>',0)
            ->orderBy('id','desc')->get();

        return view('report.client_statement',compact('clients'));
    }
    public function supplierStatement(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $appends = [];
        $query = PurchaseOrder::query();

        if ($request->start && $request->end) {

                $query->whereBetween('date', [$request->start, $request->end]);
                $appends['date'] = $request->date;

        }

        if ($request->supplier && $request->supplier != '') {
            $query->where('supplier_id', $request->supplier);
            $appends['supplier'] = $request->supplier;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $orders = $query->paginate(10);
        // $suppliers=Supplier::where('status',1)->orderBy('id','desc')->get();
        return view('report.supplier_statement',compact('orders', 'suppliers', 'appends'));
    }

    public function incomeStatement(Request $request)
    {

        $incomes = null;
        $expenses = null;
        $transactionIncomes = null;
        $transactionExpenses=null;

        $incomeAccountHead = AccountHeadType::where('transaction_type', 1)->select('id')->pluck('id')->toArray();

        if ($request->start && $request->end) {
            $incomes = TransactionLog::select(DB::raw('sum(amount) as amount, particular'))
                ->where('transaction_type', 1)
                ->whereNotNull('client_id')
                ->whereBetween('date', [$request->start, $request->end])
                ->groupBy('client_id', 'particular')
                ->orderBy('client_id')->get();

            $transactionIncomes = TransactionLog::select(DB::raw('sum(amount) as amount, particular, account_head_sub_type_id'))
                ->where('transaction_type', 1)
                ->whereNotNull('transaction_id')
                ->whereBetween('date', [$request->start, $request->end])
                ->groupBy('account_head_sub_type_id', 'account_head_sub_type_id', 'particular')
                ->orderBy('account_head_sub_type_id')->get();

            //dd($transactionIncomes);

            //$expenses = TransactionLog::whereIn('transaction_type', [3, 2])->whereBetween('date', [$request->start, $request->end])->get();

            $expenses = TransactionLog::select(DB::raw('sum(amount) as amount, particular,location'))
                ->where('transaction_type', 3)
                ->whereNotNull('supplier_id')
                ->whereBetween('date', [$request->start, $request->end])
                ->groupBy('supplier_id', 'particular','location')
                ->orderBy('supplier_id')->get();

            $transactionExpenses = TransactionLog::select(DB::raw('sum(amount) as amount, particular, account_head_sub_type_id,location'))
                ->where('transaction_type', 2)
                ->whereNotNull('transaction_id')
                ->whereBetween('date', [$request->start, $request->end])
                ->groupBy('account_head_sub_type_id', 'account_head_sub_type_id', 'particular','location')
                ->orderBy('account_head_sub_type_id')->get();


        }


        return view('report.income_statement',compact('incomes','expenses',
            'transactionIncomes','transactionExpenses'));
}


    public function salarySheet(Request $request)
    {
        // dd($request->all());
        $salaries = [];
        $working_days = null;
        if ($request->month !='' && $request->year!='') {

            $working_days=cal_days_in_month(CAL_GREGORIAN,$request->month,$request->year);

            $salaries=Salary::with('employee')
                ->where('year',$request->year)
                ->where('month',$request->month)->get();
            
            foreach ($salaries as $salary) {
                $absent = EmployeeAttendance::select(DB::raw('count(*) as absent_count'))
                    ->where('employee_id', $salary->employee_id)
                    ->whereYear('date', $request->year)
                    ->whereMonth('date',$request->month)
                    ->where('present_or_absent',0)
                    ->first();
                // dd($absent);
                
                $late = EmployeeAttendance::select(DB::raw('count(*) as late_count'))
                    ->where('employee_id', $salary->employee_id)
                    ->whereYear('date', $request->year)
                    ->whereMonth('date',$request->month)
                    ->where('late',1)
                    ->first();

                $late2=(int)($late->late_count/3);
                $salary->absent=$absent->absent_count;
                $salary->late=$late2;
            }
        }

        $salaryDates = SalaryProcess::select('year')->distinct()->get();


        return view('report.salary_sheet',compact('salaries','working_days','salaryDates'));
    }

    public function purchase(Request $request) {
        // dd($request->all());
        $suppliers = Client::orderBy('name')->get();
        $products = PurchaseProduct::orderBy('name')->get();
        $appends = [];
        $query = PurchaseOrder::query();

        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
            $appends['date'] = $request->date;
        }

        if ($request->supplier && $request->supplier != '') {
            $query->where('supplier_id', $request->supplier);
            $appends['supplier'] = $request->supplier;
        }

        if ($request->purchaseId && $request->purchaseId != '') {
            $query->where('order_no', $request->purchaseId);
            $appends['purchaseId'] = $request->purchaseId;
        }

        if ($request->product && $request->product != '') {
            $query->whereHas('products', function($q) use ($request) {
                $q->where('purchase_product_id', '=', $request->product);
            });

            $appends['product'] = $request->product;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $orders = $query->paginate(10);

        return view('report.purchase', compact('orders', 'suppliers',
            'products', 'appends'));
    }

    public function sale(Request $request) {
        $customers = Client::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $appends = [];
        $query = SalesOrder::query();

        if ($request->start && $request->end) {

                $query->whereBetween('date', [$request->start, $request->end]);
                $appends['date'] = $request->date;

        }

        if ($request->customer && $request->customer != '') {
            $query->where('client_id', $request->customer);
            $appends['customer'] = $request->customer;
        }

        if ($request->saleId && $request->saleId != '') {
            $query->where('order_no', $request->saleId);
            $appends['saleId'] = $request->saleId;
        }

        if ($request->project && $request->project != '') {
            $query->whereHas('project', function($q) use ($request) {
                $q->where('project_id', '=', $request->project);
            });

            $appends['project'] = $request->project;
        }

        $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
        $orders = $query->paginate(10);

        if ($request->customer !='') {
            $client_total_due=Client::where('id',$request->customer)->get()->sum('due');
            $client_total_paid=Client::where('id',$request->customer)->get()->sum('paid');
        }else{
            $client_total_due=Client::all()->sum('due');
            $client_total_paid=Client::all()->sum('paid');
        }

        return view('report.sale', compact('customers', 'projects',
            'appends', 'orders','client_total_due','client_total_paid'));
    }

      public function projectSummary(Request $request) {
        $projects = Project::orderBy('name')->get();
        $appends = [];
        $project_single  = null;
        $query = SalesOrder::orderBy('floor_id')->orderBy('flat_id');

        if ($request->project && $request->project != '') {
            $project_single = Project::find($request->project);
            $query->whereHas('project', function($q) use ($request) {
                $q->where('project_id', '=', $request->project);
            });

            $appends['project'] = $request->project;
        }
        if ($request->project == 'all'){
            $query = SalesOrder::query();
            $project_single=1;
        }


//        $query->orderBy('floor.name', 'desc');
        $orders = $query->paginate(100);


        return view('report.project_summary', compact( 'projects',
            'appends', 'orders','project_single'));
    }

    public function yearWisePayment(Request $request)
    {
        // Retrieve All Project List
        $projects = Project::where('status',1)->get();
        $salesOrders = [];
        $groupedData = [];
        $clientWiseTotalAmount = [];
        $yearRanges = [];
        if ($request->project != ''){
            $yearRanges = [];
            for ($i = $request->start_year;$i<=$request->end_year;$i++){
                $yearRanges [] = $i;
            }

            $salesOrders = SalesOrder::where('project_id', $request->project)
                ->orderBy('floor_id')
                ->orderBy('flat_id')
                ->with('client','flat','floor')
                ->get();

            $clientWiseTotalAmount = collect([]);
            foreach ($groupedData as $clientId => $data) {
                $clientWiseTotalAmount->put($clientId, $data->sum('total_amount'));
            }
        }


        return view('report.year_wise_payment_report', compact('yearRanges','salesOrders', 'groupedData','projects'));
    }


    public function balanceSummary() {
        $bankAccounts = BankAccount::where('status', 1)->with('bank', 'branch')->get();
        $cash = Cash::first();
        $mobile_banking = MobileBanking::first();
        $customerTotal = Client::all()->sum('total');
        $customerTotalDue = Client::all()->sum('due');
        $customerTotalPaid = Client::all()->sum('paid');
        $totalSaleProductStock = 0;
        $flatstock = SaleInventory::with('flat')->get();


        $flatPrices = DB::table('sale_inventories')
                    ->join('flat_sales_order','sale_inventories.flat_id', '=', 'flat_sales_order.flat_id')
                    ->join('projects','sale_inventories.project_id', '=', 'projects.id')
                    ->join('floors','sale_inventories.floor_id', '=', 'floors.id')
                    ->join('flats','sale_inventories.flat_id', '=', 'flats.id')
                    ->select('flat_sales_order.*', 'projects.name as project_name', 'floors.name as floor_name', 'flats.name as flat_name')
                    ->get();

        $totalflatPrice =  $flatPrices-> sum('total');

        $purchaseStock = purchaseInventory::get();
        $purchaseStocktotal = purchaseInventory::all()->sum('avg_unit_price');
        $purchaseStockQty = purchaseInventory::all()->sum('quantity');


        $suppliers = Supplier::all();
        $totalInventory = PurchaseInventory::select(DB::raw('SUM(`last_unit_price` * `quantity`) AS total'))
            ->get();
        return view('report.balance_summary', compact('customerTotal','customerTotalDue','bankAccounts',
            'cash', 'mobile_banking', 'customerTotalPaid','purchaseStocktotal','purchaseStockQty','purchaseStock', 'suppliers','flatstock', 'totalInventory','totalSaleProductStock','flatPrices','totalflatPrice'));
    }

    public function profitAndLoss(Request $request) {
        $incomes = null;
        $expenses = null;

        if ($request->start && $request->end) {
            $incomes = TransactionLog::where('transaction_type', 1)->whereBetween('date', [$request->start, $request->end])->get();
            $expenses = TransactionLog::whereIn('transaction_type', [4, 2])->whereBetween('date', [$request->start, $request->end])->get();
        }

        return view('report.profit_and_loss', compact('incomes', 'expenses'));
    }


    public function receiveAndPayment(Request $request){
        $incomes = null;
        $expenses = null;
        $incomeQuery = TransactionLog::query();
        $expenseQuery = TransactionLog::query();

        $incomeQuery->where('transaction_type', 1);
        $expenseQuery->where('transaction_type', 2);
        $incomeQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $expenseQuery->select(DB::raw('sum(amount) as amount, account_head_type_id'));
        $incomeQuery->where('account_head_type_id','!=', 0);
        $expenseQuery->where('account_head_type_id','!=', 0);

        if ($request->account_head_type != '') {
            $incomeQuery->where('account_head_type_id', $request->account_head_type);
            $expenseQuery->where('account_head_type_id', $request->account_head_type);
        }

        if ($request->start != '') {
            $incomeQuery->where('date', '>=', $request->start);
            $expenseQuery->where('date', '>=', $request->start);
        }

        if ($request->end != '') {
            $incomeQuery->where('date', '<=', $request->end);
            $expenseQuery->where('date', '<=', $request->end);
        }

        $incomeQuery->groupBy('account_head_type_id');
        $expenseQuery->groupBy('account_head_type_id');

        $incomes = $incomeQuery->get();
        $expenses = $expenseQuery->get();
        return view('report.receive_and_payment',compact('incomes','expenses'));
    }



    public function purchaseStock(Request $request) {
        $products = PurchaseProduct::with('unit')->where('status', 1)
            ->orderBy('name')->get();

        $report = null;
        $product = null;
        $selectedMonthInText = '';
        $product_single  = null;

        if ($request->year && $request->month && $request->product) {
            $product_single = PurchaseProduct::with('unit')->find($request->product);
            $selectedMonthInText = date('F Y', mktime(0,0,0,$request->month, 1, $request->year));

            $daysInMonth = Carbon::parse($request->year.'-'.$request->month)->daysInMonth;

            $report = collect();

            for($i=1; $i<=$daysInMonth; $i++) {
                $date = date('Y-m-d', mktime(0,0,0,$request->month, $i, $request->year));
                $previousDay = date('Y-m-d', strtotime($date. ' - 1 days'));

                $productionInPrevDay = PurchaseInventoryLog::where('type', 1)
                    ->where('purchase_product_id', $request->product)->where('date', '<=', $previousDay)->sum('quantity');

                $useInPrevDay = PurchaseInventoryLog::where('type', 2)
                    ->where('purchase_product_id', $request->product)->where('date', '<=', $previousDay)->sum('quantity');

                $initialStock = $productionInPrevDay - $useInPrevDay;

                $buy = PurchaseInventoryLog::select(DB::raw('sum(quantity) quantity, unit_price, supplier_id'))
                    ->where('type', 1)
                    ->where('purchase_product_id', $request->product)
                    ->where('date', $date)
                    ->groupBy('supplier_id', 'unit_price')
                    ->with('supplier')
                    ->get();


                $buyAmountCollection = collect();
                $buyAmount = 0;

                foreach ($buy as $b) {
                    $buyAmountCollection->push([
                        'supplier' => $b->supplier->name,
                        'quantity' => $b->quantity,
                        'unit_price' => $b->unit_price,
                        'total_price' => $b->quantity * $b->unit_price,
                    ]);

                    $buyAmount += $b->quantity;
                }

                $usedQuantity = PurchaseInventoryLog::where('type', 2)
                    ->where('purchase_product_id', $request->product)
                    ->where('date', $date)->sum('quantity');

                $totalQuantity = $initialStock + $buyAmount;
                $finalQuantity = $totalQuantity - $usedQuantity;

                $report->push([
                    'date' => $date,
                    'initialStock' => $initialStock,
                    'buyAmount' => $buyAmountCollection,
                    'usedQuantity' => $usedQuantity,
                    'totalQuantity' => $totalQuantity,
                    'finalQuantity' => $finalQuantity
                ]);
            }


        }


        return view('report.purchase_stock', compact('products', 'report',
             'selectedMonthInText','product_single'));
    }
    public function saleStock(Request $request) {

        $products = SaleInventory::where('status', 1)
            ->orderBy('id')->get();

        $report = null;
        $product = null;
        $selectedMonthInText = '';
        $product_single = null;

        if ($request->year && $request->month && $request->product) {
            $product_single = SaleInventory::find($request->product);
            $selectedMonthInText = date('F Y', mktime(0,0,0,$request->month, 1, $request->year));

            $daysInMonth = Carbon::parse($request->year.'-'.$request->month)->daysInMonth;

            $report = collect();

            for($i=1; $i<=$daysInMonth; $i++) {
                $date = date('Y-m-d', mktime(0,0,0,$request->month, $i, $request->year));
                $previousDay = date('Y-m-d', strtotime($date. ' - 1 days'));

                $productionInPrevDay = SaleInventoryLog::where('type', 1)
                    ->where('sale_product_id', $request->product)->where('date', '<=', $previousDay)->sum('quantity');

                $useInPrevDay = SaleInventoryLog::where('type', 2)
                    ->where('sale_product_id', $request->product)->where('date', '<=', $previousDay)->sum('quantity');

                $initialStock = $productionInPrevDay - $useInPrevDay;

                $production = SaleInventoryLog::where('type', 1)
                    ->where('sale_product_id', $request->product)
                    ->where('date', $date)->sum('quantity');

                $totalQuantity = $initialStock + $production;

                $sale = SaleInventoryLog::where('type', 2)
                    ->where('sale_product_id', $request->product)
                    ->where('date', $date)->sum('quantity');

                $balance = $totalQuantity - $sale;

                /*$buy = SaleInventoryLog::select(DB::raw('sum(quantity) quantity, unit_price, client_id'))
                    ->where('type', 2)
                    ->where('sale_product_id', $request->product)
                    ->where('date', $date)
                    ->groupBy('client_id', 'unit_price')
                    ->with('client')
                    ->get();

                $buyAmountCollection = collect();
                $buyAmount = 0;

                foreach ($buy as $b) {
                    $buyAmountCollection->push([
                        'client' => $b->client->name,
                        'quantity' => $b->quantity,
                        'unit_price' => $b->unit_price,
                        'total_price' => $b->quantity * $b->unit_price,
                    ]);

                    $buyAmount += $b->quantity;
                }



                $usedQuantity = SaleInventoryLog::where('type', 1)
                    ->where('sale_product_id', $request->product)
                    ->where('date', $date)->sum('quantity');



                $totalQuantity = $initialStock + $buyAmount;
                $finalQuantity = $usedQuantity-$totalQuantity;*/

                $report->push([
                    'date' => $date,
                    'initialStock' => $initialStock,
                    //'buyAmount' => $buyAmountCollection,
                    'production' => $production,
                    'totalQuantity' => $totalQuantity,
                    'sale' => $sale,
                    'balance' => $balance
                ]);
            }

            //dd($report);


        }

        return view('report.sale_stock', compact('products', 'report',
            'product_single', 'selectedMonthInText'));
    }

    public function cashbook(Request $request) {
        $result = null;

        if ($request->start && $request->end) {
            $result = collect();
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);

            $daysCount = $start->diffInDays($end);

            for($i=0; $i<=$daysCount; $i++) {
                $date = date('Y-m-d', strtotime($start. ' + '.$i.' days'));

                $incomes = TransactionLog::where('transaction_type', 1)->where('date', $date)->get();
                $expenses = TransactionLog::where('transaction_type', 2)->where('date', $date)->get();

                $result->push(['date' => $date, 'incomes' => $incomes, 'expenses' => $expenses]);
            }
        }
        return view('report.cashbook', compact('result'));
    }
    public function cash(Request $request){
        $type = [1,3];
        $incomes = null;
        $cashAccount = Cash::first();

        $metaData = [
            'start_date' => $request->start,
            'end_date' => $request->end,
        ];

        $result = collect();

        $initialBalance = $cashAccount->opening_balance;

        $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            $totalIncome = TransactionLog::where('transaction_type', 1)
                ->where('transaction_method', 1)
                ->whereDate('date', '<=', $previousDay)
                ->orderBy('date')
                ->sum('amount');

            $totalExpense = TransactionLog::where('transaction_type', 2)
                ->where('transaction_method', 1)
                ->whereDate('date', '<=', $previousDay)
                ->orderBy('date')
                ->sum('amount');

        $openingBalance = $initialBalance + $totalIncome - $totalExpense;
        $result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            $transactionLogs = TransactionLog::whereBetween('date', [$request->start, $request->end])
                ->where('transaction_method', 1)
                ->get();

        $balance = $openingBalance;
        $totalDebit = 0;
        $totalCredit = 0;
        foreach ($transactionLogs as $log) {
            if ($log->transaction_type == 1) {
                // Income
                $balance += $log->amount;
                $totalDebit += $log->amount;
                $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => $log->amount, 'credit' => '', 'balance' => $balance]);
            } else {
                $balance -= $log->amount;
                $totalCredit += $log->amount;
                $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => '', 'credit' => $log->amount, 'balance' => $balance]);
            }
        }
        $metaData['total_debit'] = $totalDebit;
        $metaData['total_credit'] = $totalCredit;

//        if ($request->start && $request->end) {
//        $incomes = TransactionLog::whereIn('transaction_type', $type)->where('transaction_method',1)->whereBetween('date', [$request->start, $request->end])->get();
//        //dd($incomes);
//        }
       // dd($result);
        return view('report.cash', compact('result', 'metaData'));
    }

    public function monthlyExpenditure(Request $request) {
        $selectedMonthInText = '';
        $result = null;

        if ($request->year && $request->month) {
            $result = collect();
            $selectedMonthInText = date('F, Y', mktime(0,0,0,$request->month, 1, $request->year));
            $dateObj =  Carbon::parse($request->year.'-'.$request->month);
            $startDate = $dateObj->startOfMonth()->format('Y-m-d');
            $endDate = $dateObj->endOfMonth()->format('Y-m-d');

            // Supplier Payment
            $supplierPaymentDhakaOffice = PurchasePayment::
                whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

//            $supplierPaymentFactory = PurchasePayment::where('location', 2)
//                ->whereBetween('date', [$startDate, $endDate])
//                ->sum('amount');

            $result->push([
                'particular' => 'Supplier Payment',
                'dhaka_office' => $supplierPaymentDhakaOffice,
                 'total' => $supplierPaymentDhakaOffice
            ]);

            // Transaction
            $transactionsIds = Transaction::select('account_head_type_id')
                ->where('transaction_type', 2)
                ->groupBy('account_head_type_id')
                ->whereBetween('date', [$startDate, $endDate])
                ->get()->pluck('account_head_type_id')->toArray();

            foreach ($transactionsIds as $trxId) {
                $dhakaOffice = Transaction::where('transaction_type', 2)
                    ->where('account_head_type_id', $trxId)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('amount');

                $accountHeadType = AccountHeadType::find($trxId);

                $result->push([
                    'particular' => $accountHeadType->name,
                   'dhaka_office' => $dhakaOffice,
                    'total' => $dhakaOffice
                ]);
            }
        }
        return view('report.monthly_expenditure', compact('result', 'selectedMonthInText'));
    }

    public function bankStatement(Request $request) {
        $banks = Bank::where('status', 1)->orderBy('name')->get();
        $result = null;
        $metaData = '';
        if ($request->bank && $request->branch && $request->account && $request->start && $request->end) {
            $bankAccount = BankAccount::where('id', $request->account)->first();
            $bank = Bank::where('id', $request->bank)->first();
            $branch = Branch::where('id', $request->branch)->first();

            $metaData = [
                'name' => $bank->name,
                'branch' => $branch->name,
                'account' => $bankAccount->account_no,
                'start_date' => $request->start,
                'end_date' => $request->end,
            ];

            $result = collect();

            $initialBalance = $bankAccount->opening_balance;
            $previousDay = date('Y-m-d', strtotime('-1 day', strtotime($request->start)));

            $totalIncome = TransactionLog::where('transaction_type', 1)
                ->where('bank_account_id', $request->account)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $totalExpense = TransactionLog::where('transaction_type', 2)
                ->where('bank_account_id', $request->account)
                ->whereDate('date', '<=', $previousDay)
                ->sum('amount');

            $openingBalance = $initialBalance + $totalIncome - $totalExpense;

            $result->push(['date' => $request->start_date, 'particular' => 'Opening Balance', 'debit' => '', 'credit' => '', 'balance' => $openingBalance]);

            $transactionLogs = TransactionLog::where('bank_account_id', $request->account)
                ->whereBetween('date', [$request->start, $request->end])
                ->get();

            $balance = $openingBalance;
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($transactionLogs as $log) {
                if ($log->transaction_type == 1) {
                    // Income
                    $balance += $log->amount;
                    $totalDebit += $log->amount;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => $log->amount, 'credit' => '', 'balance' => $balance]);
                } else {
                    $balance -= $log->amount;
                    $totalCredit += $log->amount;
                    $result->push(['date' => $log->date, 'particular' => $log->particular, 'debit' => '', 'credit' => $log->amount, 'balance' => $balance]);
                }
            }

            $metaData['total_debit'] = $totalDebit;
            $metaData['total_credit'] = $totalCredit;

        }

        return view('report.bank_statement', compact('banks', 'result', 'metaData'));
    }

    public function employeeList(Request $request)
    {

        $employees=Employee::with('designation','department')->get();

        return view('report.employee_list',compact('employees'));
    }


    public function priceWithStock(Request $request)
    {
        $stocks=null;
        $products=SaleInventory::where('status',1)->get();
        if ($request->product!='') {
            if ($request->product=='all') {
                $stocks=SaleInventory::where('status',1)->get();
            }else {
                $stocks=SaleInventory::where('status',1)->where('id',$request->product)->get();

            }
        }



        return view('report.price_with_stock',compact('products','stocks'));
    }
    public function priceWithOutStock(Request $request)
    {
        $stocks = null;
        $products=SaleInventory::where('status',1)->get();
        if ($request->product!='') {
            if ($request->product=='all') {
                $stocks=SaleInventory::where('status',1)->get();
            }else {
                $stocks=SaleInventory::where('status',1)->where('id',$request->product)->get();

            }
        }



        return view('report.price_without_stock',compact('products','stocks'));
    }

    public function project(Request $request){
        $projects = Project::where('status',1)->get();
        $incomes = null;
        $expenses = null;

        if ($request->project ) {
//            $incomes = TransactionLog::where('transaction_type', 1)->whereBetween('date', [$request->start, $request->end])->get();
//            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])->whereBetween('date', [$request->start, $request->end])->get();
            $incomes = TransactionLog::where('transaction_type', 1)->where('project_id', $request->project)->get();
            $expenses = TransactionLog::whereIn('transaction_type', [3, 2])->where('project_id',  $request->project)->get();
        }

        return view('report.project', compact('incomes', 'expenses','projects'));

    }
    public function client(Request $request){
        $customers = TransactionLog::where('client_id','!=',null)
            ->groupBy('client_id')->pluck('client_id');
        $clients = Client::where('status',1)->where('type',1)->get();
        $incomes = null;
        $expenses = null;
        $order = null;
        $orders = null;
        $first_date = null;
        $opening_balance = 0;

        $query = TransactionLog::where('transaction_type',1);
        if ($request->client && $request->client != '') {
            $orders = SalesOrder::where('client_id',$request->client)->get();
            $opening_balance = SalesOrder::where(['client_id'=>$request->client, 'closing_balance'=>1])->sum('closing_amount');
            //sdd($opening_balance);
        }
        if ($request->client && $request->client != ''){
            $query->where('client_id', $request->client);
            $first_date = TransactionLog::where('transaction_type',1)->where('client_id', $request->client)->first();
        }
        if ($request->start && $request->start != '' && $request->end && $request->end != ''){
            $query->whereBetween('date', [$request->start, $request->end])->whereNotNull('client_id');
       }

        $incomes = $query->get();

        return view('report.client', compact('incomes', 'expenses','clients','orders','first_date', 'opening_balance'));

    }

    public function budgetWiseReport(Request $request){

        $projects = Project::where('status',1)->get();
        $bugets = [];
        $expenses = null;

        if (!empty($request->project)) {
            $bugets = Budget::with('project')->where('project_id',$request->project)->get();
            $expenses = PurchaseOrder::where('project_id',$request->project)->with('project')->get();
        }
        return view('report.budget', compact('projects','bugets','expenses'));

    }

    public function projectwiseReport(Request $request){

        // $projects = Project::where('status',1)->get();
        // $salepayment = null;
        // $customers = [];
        // $project_single  = null;

        // if ($request->project !=null && $request->start !=null && $request->end !=null) {
        //     $project_single = Project::find($request->project);
        //     $customers = SalesOrder::where('project_id', $request->project)->whereBetween('date', [$request->start, $request->end])->get();
        //     $salepayment = Project::where('id',$request->project)->first();
        // }elseif($request->project !=null && $request->start ==null && $request->end ==null) {
        //     $customers = SalesOrder::where('project_id', $request->project)->get();
        //     $salepayment = Project::where('id',$request->project)->first();
        // }
        $projects = Project::where('status',1)->get();
        // $project = Project::where('id', $request->project)->first();
        // dd($project);
        $salepayment = null;
        $customers = [];
        $credits = [];
        $project_single  = null;

        if ($request->project !=null && $request->start !=null && $request->end !=null) {
            $customers= TransactionLog::with('project', 'accountHead',)->where('project_id',$request->project)->where('transaction_type',1)->whereBetween('date', [$request->start, $request->end])->get();
            $credits= TransactionLog::with('project', 'accountHead',)->where('project_id',$request->project)->where('transaction_type',2)->whereBetween('date', [$request->start, $request->end])->get();
        }elseif($request->project != null && $request->start == null && $request->end ==null) {
            $customers= TransactionLog::with('project', 'accountHead',)->where('project_id',$request->project)->where('transaction_type',1)->whereBetween('date', [$request->start, $request->end])->get();
            $credits= TransactionLog::with('project', 'accountHead',)->where('project_id',$request->project)->where('transaction_type',2)->whereBetween('date', [$request->start, $request->end])->get();
            
        }
        // dd($credits);
        return view('report.project_wise_report', compact('customers','projects','salepayment','project_single', 'credits'));
    }


    public function groupSummary(Request $request)
    {
        $group_summaries = AccountHeadType::where('status', 1)->get();
        $ledgers = null;
        $group_summary = null;
        $group_summary_info = null;

        if ($request->start && $request->end && $request->group_summary) {
            $ledgers = TransactionLog::where('account_head_type_id', $request->group_summary)->whereBetween('date', [$request->start, $request->end])->get();
        }
        if ($request->group_summary) {
            $group_summary_info = AccountHeadType::where('id', $request->group_summary)->first();
        }
    //    dd($group_summary);
        $ledgers = AccountHeadSubType::where(['account_head_type_id'=>$request->group_summary])->get();
        return view('report.group_summary', compact('ledgers', 'group_summaries', 'group_summary', 'group_summary_info'));
    }
    public function customerLedger(Request $request){
        $accountSubheads = AccountHeadSubType::where('status', 1)->get();
        $transactions = null;
        $first_date = null;
        $accountSubhead = null;

        if ($request->start && $request->end) {
            if ($request->account_head_sub_type_id) {
                $accountSubhead = AccountHeadSubType::where('id', $request->account_head_sub_type_id)->first();
                $transactions = TransactionLog::whereBetween('date', [$request->start, $request->end])->where('account_head_sub_type_id', $request->account_head_sub_type_id)->get();
            }else {
                $transactions = TransactionLog::whereBetween('date', [$request->start, $request->end])->get();
            }

        }
        return view('report.ledger_customer', compact('transactions','accountSubheads','accountSubhead'));
    }

    public function foodCost(Request $request){

        $attendances=[];
        if ( $request->start!='' && $request->end!='') {
            $start=$request->start;
            $end=$request->end;
            // $attendances=FoodCostItem::whereDate('date','>=',$request->start)->whereDate('date','<=', $request->end)->get();
            $attendances = FoodCostItem::whereDate('date','>=',$request->start)
            ->whereDate('date','<=', $request->end)
            ->groupBy('labour_employee_id')
            ->select(DB::raw('sum(`food_cost`) as food_cost,sum(`advance`) as advance,count(`date`) as ccc, labour_employee_id '))->get();
          }else{
            $start=date('Y-m-d',1970-01-01);
            $end=date('Y-m-d');
          //  $attendances = FoodCostItem::sum('food_cost')->groupBy('labour_employee_id');
        //$attendances = DB::table('food_cost_items')->groupBy('labour_employee_id');
             $attendances = FoodCostItem::groupBy('labour_employee_id')
          ->select(DB::raw('sum(`food_cost`) as food_cost,sum(`advance`) as advance,count(`date`) as ccc, labour_employee_id '))->get();
         }

       // dd($attendances);
        return view('report.labour_advance',compact('attendances','start','end'));
    }

    public function labourBillProcess(Request $request){
        $labourBills=[];
        if ( $request->start!='' && $request->end!='') {
            // dd($request->all());
            $labourBills=EmployeeWiseBill::whereDate('date','>=',$request->start)->whereDate('date','<=', $request->end)->get();

        }
        return view('report.labour_bill',compact('labourBills'));
    }

    public function employeeAdvanceSalary(Request $request){
        $employees=[];
        if ( $request->start!='' && $request->end!='') {

            $employees=EmployeeSalaryAdvance::whereDate('date','>=',$request->start)->whereDate('date','<=', $request->end)->get();
//            dd($employees);
        }
        return view('report.employee_advance_salary',compact('employees'));

    }



}
