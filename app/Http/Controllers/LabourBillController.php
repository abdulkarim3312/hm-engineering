<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Cash;
use App\Model\EmployeeWiseBill;
use App\Model\FoodCost;
use App\Model\FoodCostItem;
use App\Model\LabourBill;
use App\Model\Labour;
use App\Model\LabourEmployeeAttendace;
use App\Model\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SakibRahaman\DecimalToWords\DecimalToWords;
use Yajra\DataTables\Facades\DataTables;

class LabourBillController extends Controller
{
    public function index() {
        return view('labour.bill.all');
    }

    public function add() {
        $labourEmployees = Labour::orderby('name')->get();
        return view('labour.bill.add', compact('labourEmployees'));
    }

    public function addPost(Request $request) {

        $rules = [
            'payment_type'=>'required',
            'financial_year'=>'required',
            'labour_employee'=>'nullable',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'date'=>'required|date',
        ];
        if ($request->payment_type == 1){
            $rules['cheque_no'] = 'required';
            $rules['cheque_date'] = 'required|date';
        }

        $request->validate($rules);

        if ($request->labour_employee == '') {

            if ($request->start_date && $request->end_date) {
                $labourBill = LabourBill::where('start_date', $request->start_date)
                    ->where('end_date', $request->end_date)
                    ->where('process_type',0)
                    ->first();

                if ($labourBill) {
                    return redirect()->route('labour.bill.add')->with('error', 'This Date Labour Bill Already Payment');
                }
            }
        }else{

            if ($request->start_date && $request->end_date && $request->labour_employee ) {
                $labourBill = LabourBill::where('start_date', $request->start_date)
                    ->where('end_date', $request->end_date)
                    ->with('employeeWiseBills')
                    ->first();

                if ($labourBill) {
                    foreach ($labourBill->employeeWiseBills as $employeeWiseBill){
                        if ($employeeWiseBill->employee_id == $request->labour_employee) {
                            return redirect()->route('labour.bill.add')->with('error', 'This Date Labour Bill Already Payment');
                        }
                    }
                }
            }
        }

        $totalSalary = 0;

        if ($request->labour_employee == '') {

            $labourEmployees = Labour::where('status',1)->get();

            $employee = $labourEmployees->pluck('id')->toArray();

            $employeeWiseBills = EmployeeWiseBill::whereIn('employee_id', $employee)
                ->where('start_date', $request->start_date)
                ->where('end_date', $request->end_date)
                ->get();

            $employeeWise = $employeeWiseBills->pluck('employee_id');

            if ($employeeWise) {
                $employees = Labour::where('status',1)->whereNotIn('id', $employeeWise)->get();
            }else{
                $employees = Labour::where('status',1)->get();
            }

            foreach ($employees as $employee){
                //$totalDays = (date('d',strtotime($request->end_date)) - date('d',strtotime($request->start_date)));

                $totalAttendances = LabourEmployeeAttendace::where('labour_employee_id', $employee->id)
                    ->where('present_or_absent', 1)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->count();

                $totalFoodCosts = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('food_cost');

                $totalAdvance = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('advance');

                $bonus= Labour::where('id',$employee->id)->first();


                if ($totalAttendances && ($totalFoodCosts || $totalAdvance)) {
                    $totalBill = (($employee->per_day_amount * $totalAttendances)+$bonus->bonus - ($totalFoodCosts+$totalAdvance));
                    $totalSalary += $totalBill;
                }elseif($totalAttendances > 0){
                    $totalBill =($employee->per_day_amount * $totalAttendances)+$bonus->bonus;
                    $totalSalary += $totalBill;
                }else{
                    return redirect()->route('labour.bill.add')->with('error', 'This Date Are Attendance Or Food Cost Not Found');
                }



            }

            $labourBill = new LabourBill();
            $labourBill->date = $request->date;
            $labourBill->financial_year = financialYear($request->financial_year);
            $labourBill->start_date = $request->start_date;
            $labourBill->end_date = $request->end_date;
            $labourBill->payment_type = $request->payment_type;
            $labourBill->cheque_no = $request->cheque_no;
            $labourBill->cheque_date = $request->cheque_date ? Carbon::parse($request->cheque_date) : null;
            $labourBill->account_head_id = $request->account;
            $labourBill->total = $totalSalary;
            $labourBill->process_type = 0;
            $labourBill->save();

            foreach ($employees as $employee){
                //$totalDays = (date('d',strtotime($request->end_date)) - date('d',strtotime($request->start_date)));

                $totalAttendances = LabourEmployeeAttendace::where('labour_employee_id', $employee->id)
                    ->where('present_or_absent', 1)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->count();

                $totalFoodCosts = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('food_cost');

                $totalAdvance = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('advance');

                $bonus= Labour::where('id',$employee->id)->first();

                if ($totalAttendances && ($totalFoodCosts || $totalAdvance)) {
                    $totalBill = ($bonus->bonus+($employee->per_day_amount * $totalAttendances) - ($totalFoodCosts+$totalAdvance));
                    $totalSalary += $totalBill;
                }elseif($totalAttendances > 0){
                    $totalBill =($employee->per_day_amount * $totalAttendances)+$bonus->bonus;
                    $totalSalary += $totalBill;
                }

                $employeeWiseBill = new EmployeeWiseBill();
                $employeeWiseBill->labour_bill_id = $labourBill->id;
                $employeeWiseBill->employee_id = $employee->id;
                $employeeWiseBill->date = $request->date;
                $employeeWiseBill->start_date = $request->start_date;
                $employeeWiseBill->end_date = $request->end_date;
                $employeeWiseBill->total_attendance = $totalAttendances;
                $employeeWiseBill->per_day_amount = $employee->per_day_amount;
                $employeeWiseBill->food_cost = $totalFoodCosts??'';
                $employeeWiseBill->advance = $totalAdvance??'';
                $employeeWiseBill->bonus = $bonus->bonus ?? 0;
                $employeeWiseBill->net_bill = $totalBill;
                $employeeWiseBill->save();

                $bonus->bonus=0;
                $bonus->save();
            }

        }else{

            $employee = Labour::where('id', $request->labour_employee)->first();

            if ($employee){
                //$totalDays = (date('d',strtotime($request->end_date)) - date('d',strtotime($request->start_date)));

                $totalAttendances = LabourEmployeeAttendace::where('labour_employee_id', $employee->id)
                    ->where('present_or_absent', 1)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->count();


                $totalFoodCosts = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('food_cost');

                $totalAdvance = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('advance');

                $bonus= Labour::where('id',$employee->id)->first();

                if ($totalAttendances && ($totalFoodCosts || $totalAdvance)) {
                    $totalBill = (($employee->per_day_amount * $totalAttendances)+$bonus->bonus - ($totalFoodCosts+$totalAdvance));
                    $totalSalary += $totalBill;
                }elseif($totalAttendances > 0){
                    $totalBill =($employee->per_day_amount * $totalAttendances)+$bonus->bonus;
                    $totalSalary += $totalBill;
                }else{
                    return redirect()->route('labour.bill.add')->with('error', 'This Date Are Attendance Or Food Cost Not Found');
                }
            }

            $labourBill = new LabourBill();
            $labourBill->financial_year = financialYear($request->financial_year);
            $labourBill->date = $request->date;

            $labourBill->cheque_no = $request->cheque_no;
            $labourBill->cheque_date = $request->cheque_date ? Carbon::parse($request->cheque_date) : null;

            $labourBill->start_date = $request->start_date;
            $labourBill->end_date = $request->end_date;
            $labourBill->payment_type = $request->payment_type;
            $labourBill->account_head_id = $request->account;
            $labourBill->total = $totalSalary;
            $labourBill->process_type = 1;
            $labourBill->save();

            if ($employee){
                //$totalDays = (date('d',strtotime($request->end_date)) - date('d',strtotime($request->start_date)));

                $totalAttendances = LabourEmployeeAttendace::where('labour_employee_id', $employee->id)
                    ->where('present_or_absent', 1)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->count();


                $totalFoodCosts = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('food_cost');


                $totalAdvance = FoodCostItem::where('labour_employee_id', $employee->id)
                    ->where('date', '>=', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->sum('advance');

                $bonus= Labour::where('id',$employee->id)->first();

                if ($totalAttendances && ($totalFoodCosts || $totalAdvance)) {
                    $totalBill = (($employee->per_day_amount * $totalAttendances)+$bonus->bonus - ($totalFoodCosts+$totalAdvance));
                    $totalSalary += $totalBill;
                }elseif($totalAttendances > 0){
                    $totalBill =($employee->per_day_amount * $totalAttendances)+$bonus->bonus;
                    $totalSalary += $totalBill;
                }

                $employeeWiseBill = new EmployeeWiseBill();
                $employeeWiseBill->labour_bill_id = $labourBill->id;
                $employeeWiseBill->employee_id = $employee->id;
                $employeeWiseBill->date = $request->date;
                $employeeWiseBill->start_date = $request->start_date;
                $employeeWiseBill->end_date = $request->end_date;
                $employeeWiseBill->total_attendance = $totalAttendances??'';
                $employeeWiseBill->per_day_amount = $employee->per_day_amount;
                $employeeWiseBill->bonus = $bonus->bonus??0;
                $employeeWiseBill->food_cost = $totalFoodCosts;
                $employeeWiseBill->advance = $totalAdvance;
                $employeeWiseBill->net_bill = $totalBill;
                $employeeWiseBill->save();

                $bonus->bonus = 0;
                $bonus->save();
            }

        }

        return redirect()->route('labour.bill')->with('message', 'Labour Bill process successful.');
    }

    public function labourBillDatatable() {
        $query = LabourBill::query();
        $query->orderBy('date','desc');
        return DataTables::eloquent($query)

            ->addColumn('action', function(LabourBill $labourBill) {

                return '<a href="'.route('labour.bill.details', ['labourBill' => $labourBill->id]).'" class="btn btn-primary btn-sm">View</a>';

//                <a href="'.route('labourBill.edit', ['labourBill' => $labourBill->id]).'" class="btn btn-info btn-sm">Edit</a>

            })
            ->editColumn('date', function(LabourBill $labourBill) {
                return $labourBill->date;
            })
            ->editColumn('start_date', function(LabourBill $labourBill) {
                return $labourBill->start_date;
            })
            ->editColumn('end_date', function(LabourBill $labourBill) {
                return $labourBill->end_date;
            })
            ->editColumn('total', function(LabourBill $labourBill) {
                return ' '.number_format($labourBill->total, 2);
            })

            ->rawColumns(['action'])
            ->toJson();
    }

    public function details(LabourBill $labourBill) {
        $labourBill->amount_in_word = DecimalToWords::convert($labourBill->total,'Taka',
            'Poisa');
        return view('labour.bill.details', compact('labourBill'));
    }
}
