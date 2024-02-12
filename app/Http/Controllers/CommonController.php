<?php

namespace App\Http\Controllers;

use App\Model\AccountHeadSubType;
use App\Model\AccountHeadType;
use App\Model\BankAccount;
use App\Model\Branch;
use App\Model\Cash;
use App\Model\Designation;
use App\Model\Employee;
use App\Model\EstimateProduct;
use App\Model\EstimateProject;
use App\Model\ProductRequisition;
use App\Model\PurchaseInventory;
use App\Model\PurchaseOrder;
use App\Model\PurchaseProduct;
use App\Model\PurchasrOrderPurchaseProduct;
use App\Model\Requisition;
use App\Model\SalaryChangeLog;
use App\Model\SalaryProcess;
use App\Model\SalesOrder;
use App\Model\Unit;
use App\Models\AccountHead;
use App\Model\Client;
use App\Models\AssignSegmentItem;
use App\Models\BricksConfigureProduct;
use App\Models\CostingSegment;
use App\Models\EstimateFloor;
use App\Models\SegmentConfigure;
use App\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;


class CommonController extends Controller
{

    public function payeeJson(Request $request)
    {
        if (!$request->searchTerm) {
            $clients = Client::limit(10)->get();
        } else {
            $clients = Client::where('name', 'like','%' . $request->searchTerm.'%')
                ->limit(50)->get();
        }

        $data = array();

        foreach ($clients as $client) {
            $data[] = [
                'id' => $client->id,
                'text' =>'Name:'.$client->name.'|Designation:'.$client->designation,
            ];
        }

        echo json_encode($data);
    }


    public function accountHeadCodeJson(Request $request)
    {
        if (!$request->searchTerm) {
            $accountHeads = AccountHead::orderBy('account_code')
                ->where('account_head_type_id',1)
                ->limit(50)
                ->get();
        } else {
            $accountHeads = AccountHead::where('account_code', 'like','%' . $request->searchTerm.'%')
                ->orWhere('name', 'like','%'.$request->searchTerm.'%')
                ->orderBy('account_code','asc')
                ->where('account_head_type_id',1)
                ->limit(50)
                ->get();

        }
        // dd($accountHeads);

        $data = array();

        foreach ($accountHeads as $accountHead) {
            $data[] = [
                'id' => $accountHead->id,
                'text' =>$accountHead->name.'|'.$accountHead->account_code,
            ];
        }

        echo json_encode($data);
    }
    public function accountHeadCodeExpenseJson(Request $request)
    {
        if (!$request->searchTerm) {
            $accountHeads = AccountHead::orderBy('account_code')
                ->where('account_head_type_id',4)
                ->limit(50)
                ->get();
        } else {
            $accountHeads = AccountHead::where('account_code', 'like','%' . $request->searchTerm.'%')
                ->orWhere('name', 'like','%'.$request->searchTerm.'%')
                ->orderBy('account_code','asc')
                ->where('account_head_type_id',4)
                ->limit(50)
                ->get();

        }

        $data = array();

        foreach ($accountHeads as $accountHead) {
            $data[] = [
                'id' => $accountHead->id,
                'text' =>$accountHead->name.'|'.$accountHead->account_code,
            ];
        }

        echo json_encode($data);
    }
    public function accountHeadCodeIncomeJson(Request $request)
    {
        if (!$request->searchTerm) {
            $accountHeads = AccountHead::orderBy('account_code')
                ->where('account_head_type_id',5)
                ->limit(50)
                ->get();
        } else {
            $accountHeads = AccountHead::where('account_code', 'like','%' . $request->searchTerm.'%')
                ->orWhere('name', 'like','%'.$request->searchTerm.'%')
                ->orderBy('account_code','asc')
                ->where('account_head_type_id',5)
                ->limit(50)
                ->get();

        }

        $data = array();

        foreach ($accountHeads as $accountHead) {
            $data[] = [
                'id' => $accountHead->id,
                'text' =>$accountHead->name.'|'.$accountHead->account_code,
            ];
        }

        echo json_encode($data);
    }


    public function getBranch(Request $request) {
        $branches = Branch::where('bank_id', $request->bankId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($branches);
    }
    public function orderDetails(Request $request) {
        $order = SalesOrder::where('id', $request->orderId)->with('client')->first()->toArray();

        return response()->json($order);
    }
    public function getBankAccount(Request $request) {
        $accounts = BankAccount::where('branch_id', $request->branchId)
            ->where('status', 1)
            ->orderBy('account_no')
            ->get()->toArray();

        return response()->json($accounts);
    }

    public function getAccountHeadType(Request $request) {
        $types = AccountHeadType::where('transaction_type', $request->type)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($types);
    }
    public function getAccountHeadTypeTrx(Request $request) {
        $types = AccountHeadType::where('transaction_type', $request->type)
            ->where('status', 1)
            ->whereNotIn('id',[1,2,3,4,5,6])
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($types);
    }

    public function getAccountHeadSubType(Request $request) {
        $subTypes = AccountHeadSubType::where('account_head_type_id', $request->typeId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($subTypes);
    }
    public function getAccountHeadSubTypeTrx(Request $request) {
        $subTypes = AccountHeadSubType::where('account_head_type_id', $request->typeId)
            ->where('status', 1)
            ->whereNotIn('id',[1,2,3,4,5,6])
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($subTypes);
    }

    public function getDesignation(Request $request) {
        $designations = Designation::where('department_id', $request->departmentId)
            ->where('status', 1)
            ->orderBy('name')->get()->toArray();

        return response()->json($designations);
    }

    public function getEmployeeDetails(Request $request) {
        $employee = Employee::where('id', $request->employeeId)
            ->with('department', 'designation')->first();

        return response()->json($employee);
    }


    public function getEmployeeDetailsBonus(Request $request) {
        $employee = Employee::where('id', $request->employeeId)
            ->with('department', 'designation')->first();

        $crnt= date('d/m/Y');
        $date = Carbon::createFromFormat('d/m/Y', $crnt);

        $log= SalaryChangeLog::where('employee_id',$request->employeeId)
            ->whereMonth('date', $date->month)
            ->whereYear('date', $date->year)->first();

        if ($log){
            return response()->json(['employee'=>$employee,'log'=>$log]);
        }else{
            return response()->json(['employee'=>$employee]);
        }

    }

    public function getMonth(Request $request) {
        $salaryProcess = SalaryProcess::select('month')
            ->where('year', $request->year)
            ->get();

        $proceedMonths = [];
        $result = [];

        foreach ($salaryProcess as $item)
            $proceedMonths[] = $item->month;

        for($i=1; $i <=12; $i++) {
            if (!in_array($i, $proceedMonths)) {
                $result[] = [
                    'id' => $i,
                    'name' => date('F', mktime(0, 0, 0, $i, 10)),
                ];
            }
        }

        return response()->json($result);
    }
    public function getMonthSalaryMonth(Request $request) {

        $salaryProcess = SalaryProcess::select('month')
            ->where('year', $request->year)
            ->get();

        $proceedMonths = [];
        $result = [];

        foreach ($salaryProcess as $item)
            $proceedMonths[] = $item->month;

        for($i=1; $i <=12; $i++) {
            if (in_array($i, $proceedMonths)) {
                $result[] = [
                    'id' => $i,
                    'name' => date('F', mktime(0, 0, 0, $i, 10)),
                ];
            }
        }

        return response()->json($result);
    }

    public function cash(Request $request)
    {
        $cash = Cash::first();


        return view('cash.add', compact('cash'));
    }

    public function cashPost(Request $request)
    {

        $cash = Cash::first();

         if ($cash->amount != $request->opening_balance){

            $cash->decrement('amount',$cash->opening_balance);
            $cash->opening_balance = $request->opening_balance;
            $cash->increment('amount',$request->opening_balance);
            $cash->save();
        }



        return redirect()->back()->with('message','Cash added successfully done.');
    }

    public function requisitionProductJson(Request $request) {

//        $costingId= Costing::where('estimate_project_id',$request->projectId)
//            ->where('costing_type_id',$request->segmentId)->first();
//
//        $costingQuantity= PurchaseProductProductCosting::where('costing_id',$costingId->id)
//            ->where('purchase_product_id',$request->productId)
//            ->sum('quantity');
//
//        $requisitionIds= Requisition::where('project_id',$request->projectId)
//            ->where('product_segment_id',$request->segmentId)
//            ->pluck('id');
//
//        $requisitionQuantity= ProductRequisition::whereIn('requisition_id',$requisitionIds)
//            ->where('purchase_product_id',$request->productId)
//            ->sum('quantity');
//
//        $available= $costingQuantity-$requisitionQuantity;

        $product = PurchaseProduct::findOrFail($request->productId);

        $unit= $product->unit->name;

        //return response()->json(['success' => true, 'data' => $product,'unit'=> $unit,'available'=> $available]);
        return response()->json(['success' => true, 'data' => $product,'unit'=> $unit]);
    }

    public function getSegment(Request $request){

        $segments = Segment::where('project_id',$request->projectId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($segments);
    }
    public function getEstimateFloor(Request $request){

        $estimateFloors = EstimateFloor::where('estimate_project_id',$request->estimateProjectId)
            ->where('status', 1)
            ->orderBy('name')
            ->get()->toArray();

        return response()->json($estimateFloors);
    }
    public function getRequisition(Request $request){

        $requisitionId = PurchaseOrder::pluck('requisition_id');
        $requisitions = Requisition::where('project_id',$request->projectId)->where('status',1)->whereNotIn('id',$requisitionId)
            ->orderBy('requisition_no')
            ->get()->toArray();
        return response()->json($requisitions);
    }
    public function getUnit(Request $request){

        $product = PurchaseProduct::where('id',$request->productId)
            ->where('status', 1)
            ->with('unit')
            ->first();
        $unit = Unit::where('id',$product->unit_id)->first();

        return response()->json($unit);
    }
    public function getRequisitionApproved(Request $request){

       $productRequisition = ProductRequisition::where('requisition_id',$request->requisitionId)
            ->where('purchase_product_id',$request->productId)
            ->first();

       if ($productRequisition){
           $availableQuantity = $productRequisition->purchase_due_quantity;
       }else{
           $availableQuantity = 0;
       }

        return response()->json($availableQuantity);
    }

    public function getPurchaseOrder(Request $request){

        $purchaseOrders = PurchaseInventory::where('project_id',$request->projectId)
            ->with('purchaseOrder')
            ->get();

        return response()->json($purchaseOrders);
    }
    public function getProduct(Request $request){

        $products = PurchaseInventory::where('purchase_order_id',$request->purchaseOrderID)
            ->with('purchaseProduct')
            ->get();

        return response()->json($products);
    }
    public function requisitionProductDetails(Request $request){
        $products = ProductRequisition::where('requisition_id',$request->requisitionId)
            ->with('unit')
            ->get();

        return response()->json($products);
    }

    public function getScrapProductDetails(Request $request){

        $productDetails = PurchaseInventory::where('project_id',$request->projectId)
            ->where('purchase_product_id',$request->productId)
            ->where('scrap_status', 2)
            ->first();

        return response()->json($productDetails);
    }

    public function getScrapProduct(Request $request){

        $productIds = PurchaseInventory::where('project_id',$request->projectId)
            ->where('quantity','>', 0)
            ->where('scrap_status', 2)
            ->pluck('purchase_product_id');

        $purchaseProducts = PurchaseProduct::whereIn('id',$productIds)
            ->get();

        return response()->json($purchaseProducts);
    }

    public function getEstimateProjectSegment(Request $request){

        $assignSegmentItem = AssignSegmentItem::where('estimate_project_id',$request->projectId)
            ->select('segment_configure_id')
            ->groupBy('segment_configure_id')
            ->pluck('segment_configure_id');

        $segmentConfigure = SegmentConfigure::whereIn('id',$assignSegmentItem)
            ->select('costing_segment_id')
            ->groupBy('costing_segment_id')
            ->pluck('costing_segment_id');

        $costingSegments = CostingSegment::whereIn('id',$segmentConfigure)->get();

        return response()->json($costingSegments);
    }
    public function getEstimateProductUnit(Request $request){

        $product = EstimateProduct::where('id',$request->productID)
            ->where('status', 1)
            ->with('unit')
            ->first();
        $unit = Unit::where('id',$product->unit_id)->first();

        return response()->json($unit);
    }
    public function getBricksArea(Request $request){

        $product = BricksConfigureProduct::where('id',$request->productID)
            ->first();

        return response()->json($product);
    }

    public function getWallDirection(Request $request){

        $wallDirections = BricksConfigureProduct::where('estimate_project_id',$request->estimateProjectId)
            ->where('estimate_floor_id',$request->estimateFloorId)
            ->where('estimate_floor_unit_id',$request->estimateFloorUnitId)
            ->where('unit_section_id',$request->unitSectionId)
            ->orderBy('wall_direction')
            ->get()->toArray();

        //dd($wallDirections);

        return response()->json($wallDirections);
    }
}
