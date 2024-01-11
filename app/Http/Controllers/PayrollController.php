<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\BankAccount;
use App\Model\Cash;
use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Model\EmployeeSalaryAdvance;
use App\Model\Holiday;
use App\Model\Leave;
use App\Model\MobileBanking;
use App\Model\Salary;
use App\Model\SalaryChangeLog;
use App\Model\SalaryProcess;
use App\Model\TransactionLog;
use App\Models\AccountHead;
use App\Models\ReceiptPayment;
use App\Models\ReceiptPaymentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use SakibRahaman\DecimalToWords\DecimalToWords;

class PayrollController extends Controller
{
    public function salaryUpdateIndex() {
        //$banks = Bank::where('status', 1)->orderBy('name')->get();
        return view('payroll.salary_update.all');
    }

    public function salaryUpdatePost(Request $request) {
        $rules = [
            'tax' => 'required|numeric|min:0',
            'others_deduct' => 'nullable|numeric',
            'gross_salary' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'nullable',
        ];

        if ($request->type==5) {
            $rules = [
            'bonus_salary' => 'required|numeric|min:0',
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        $others_deduct=0;
        if ($request->others_deduct) {
            $others_deduct= $request->others_deduct;
        }

        $employee = Employee::find($request->id);
        $employee->medical = round($request->gross_salary * .04);
        $employee->travel = round($request->gross_salary * .12);
        $employee->house_rent = round($request->gross_salary * .24);
        $employee->basic_salary = round($request->gross_salary * .60);
        $employee->tax = $request->tax;
        $employee->others_deduct =$others_deduct;
        $employee->gross_salary =$request->gross_salary;
        if ($request->type==6){
            $employee->bonus = $request->bonus_salary;
        }
        $employee->save();

        if ($request->type) {

            $salaryChangeLog = new SalaryChangeLog();
            $salaryChangeLog->employee_id = $employee->id;
            $salaryChangeLog->date = $request->date;
            $salaryChangeLog->type = $request->type;
            $salaryChangeLog->basic_salary = round($request->gross_salary * .60);
            $salaryChangeLog->house_rent = round($request->gross_salary * .24);
            $salaryChangeLog->travel = round($request->gross_salary * .12);
            $salaryChangeLog->medical = round($request->gross_salary * .04);
            $salaryChangeLog->tax = $request->tax;
            $salaryChangeLog->others_deduct = $request->others_deduct;
            $salaryChangeLog->gross_salary = $request->gross_salary;

            if ($request->type==6){
                $salaryChangeLog->bonus = $request->bonus_salary;
            }
            $salaryChangeLog->save();
        }


        return response()->json(['success' => true, 'message' => 'Updates has been completed.']);
    }
    public function employeeSalaryAdvancePost(Request $request) {
        $rules = [
            'year' => 'required',
            'month' => 'required',
            'payment_type' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required',
            'date' => 'required|date',
        ];

        if ($request->payment_type == '2') {
            $rules['bank'] = 'required';
            $rules['branch'] = 'required';
            $rules['account'] = 'required';
            $rules['cheque_no'] = 'nullable|string|max:255';
            $rules['cheque_image'] = 'nullable|image';
        }

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request) {
            if ($request->payment_type == 1) {
                $cash = Cash::first();

                if ($request->amount > $cash->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }elseif ($request->payment_type == 3){
                $mobileBank = MobileBanking::first();

                if ($request->amount > $mobileBank->amount)
                    $validator->errors()->add('amount', 'Insufficient balance.');
            }
            else {
                if ($request->account != '') {
                    $account = BankAccount::find($request->account);

                    if ($request->amount > $account->balance)
                        $validator->errors()->add('amount', 'Insufficient balance.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
//        dd($request->all());

        if ($request->payment_type == 1 || $request->payment_type == 3) {
            //code here
            $salaryAdvanceLog = new EmployeeSalaryAdvance();

            $salaryAdvanceLog->employee_id = $request->id;
            $salaryAdvanceLog->year = $request->year;
            $salaryAdvanceLog->month = $request->month;
            $salaryAdvanceLog->date = $request->date;
            $salaryAdvanceLog->type = $request->type;
            $salaryAdvanceLog->advance = $request->amount;
            $salaryAdvanceLog->save();

            if ($request->payment_type == 1)
                Cash::first()->decrement('amount', $request->amount);
            else
                MobileBanking::first()->decrement('amount', $request->amount);

            $log = new TransactionLog();

            $log->date = $request->date;
            $log->particular = 'Advance salary to '.$salaryAdvanceLog->employee->name.' for employee id'.$salaryAdvanceLog->employee->employee_id;
            $log->transaction_type = 2;
            $log->transaction_method = $request->payment_type;
            $log->account_head_type_id = 5;
            $log->account_head_sub_type_id = 5;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->employee_salary_advance_id = $salaryAdvanceLog->id;
            $log->save();

        } else {
            $image = 'img/no_image.png';

            if ($request->cheque_image) {
                // Upload Image
                $file = $request->file('cheque_image');
                $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
                $destinationPath = 'public/uploads/advance_payment_cheque';
                $file->move($destinationPath, $filename);

                $image = 'uploads/advance_payment_cheque/'.$filename;
            }

            //code here
            $salaryAdvanceLog = new EmployeeSalaryAdvance();
            $salaryAdvanceLog->employee_id = $request->id;
            $salaryAdvanceLog->year = $request->year;
            $salaryAdvanceLog->month = $request->month;
            $salaryAdvanceLog->date = $request->date;
            $salaryAdvanceLog->type = $request->type;
            $salaryAdvanceLog->advance = $request->amount;
            $salaryAdvanceLog->save();

            BankAccount::find($request->account)->decrement('balance', $request->amount);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Advance salary to '.$salaryAdvanceLog->employee->name.' for employee id'.$salaryAdvanceLog->employee->employee_id;
            $log->transaction_type = 2;
            $log->transaction_method = 2;
            $log->account_head_type_id = 5;
            $log->account_head_sub_type_id = 5;
            $log->bank_id = $request->bank;
            $log->branch_id = $request->branch;
            $log->bank_account_id = $request->account;
            $log->cheque_no = $request->cheque_no;
            $log->cheque_image = $image;
            $log->amount = $request->amount;
            $log->note = $request->note;
            $log->employee_salary_advance_id = $salaryAdvanceLog->id;
            $log->save();
        }

        return response()->json(['id'=>$salaryAdvanceLog->id,'success' => true, 'message' => 'Salary advance has been completed.','redirect_url' => route('employee.salary_advance_receipt', ['employeeSalaryAdvance' => $salaryAdvanceLog->id])]);
    }

    public function employeeSalaryAdvanceReceipt(EmployeeSalaryAdvance $employeeSalaryAdvance){
        $employeeSalaryAdvance->amount_in_word = DecimalToWords::convert($employeeSalaryAdvance->advance,'Taka',
            'Poisa');


        return view('payroll.salary_update.salary_advance_receipt', compact('employeeSalaryAdvance'));

    }


 public function salaryProcessIndex() {
        $employees =Employee::get();
        return view('payroll.salary_process.index',compact('employees'));
    }

    public function salaryProcessPost(Request $request) {

        $rules = [
            'account'=>'required',
            'year'=>'required',
            'month'=>'required',
            'date'=>'required|date',
        ];


        $request->validate($rules);

        $totalSalary = 0;
        $totalTax = 0;
        $advanceDeduct = 0;
        $employees = Employee::get();

//        if (count($employees) < 1){
//            return redirect()->back()
//                ->withInput()
//                ->with('error','There are no employees');
//        }
//
//        foreach ($employees as $employee){
        //dd(  $bonus= $employee->bonus);

        if ($request->employee){
            $employee = Employee::where('id',$request->employee)->first();
            // dd($employee);

            $salary = Salary::where('month',$request->month)
                ->where('year',$request->year)
                ->where('employee_id',$employee->id)
                ->first();
                // dd($salary);
            if ($salary) {
                return redirect()->route('payroll.salary_process.index')->with('error', 'This Employee Salary All Ready Processed');
            }

            $absent_count = EmployeeAttendance::where('employee_id',$employee->id)
                ->where('present_or_absent',0)
                ->whereYear('date', '=', $request->year)
                ->whereMonth('date', '=', $request->month)
                ->count();
                // dd($absent_count);
            $late_count = EmployeeAttendance::where('employee_id',$employee->id)
                ->where('present_or_absent',1)
                ->where('late',1)
                ->whereYear('date',date('Y'))
                ->whereMonth('date',$request->month)
                ->count();
            $advanceDeduct = EmployeeSalaryAdvance::where('employee_id',$employee->id)
                ->where('year',$request->year)
                ->where('month', $request->month)
                ->sum('advance');
                // dd($advanceDeduct);
            $bonus= $employee->bonus;
            // dd($bonus);
            $working_days = cal_days_in_month(CAL_GREGORIAN,$request->month,date('Y'));
            // dd($working_days);
            $late=(int)($late_count/3);

            $per_day_salary = $employee->gross_salary / $working_days;
            // dd($per_day_salary);
            $deduct_absent_salary = $absent_count + $late * $per_day_salary;
            // dd($deduct_absent_salary);


            if($employee->employee_type==1){

                $grossSalary = $employee->gross_salary;
                $providentFund = 0.05 * $grossSalary;
                $salaryAfterProvidentFund = $grossSalary - $providentFund;
                if($employee->gross_salary >=48000){
                    $totalTax = 0.05 * $employee->gross_salary;
                    $tax = Employee::find($employee->id);
                    $tax->increment('tax', $totalTax);
                    $tax->save();
                }

                $provident = Employee::find($employee->id);
                $provident->increment('provident_fund', $providentFund);
                $provident->save();

                $totalSalary += ($salaryAfterProvidentFund + $bonus) - ($advanceDeduct + $deduct_absent_salary + $employee->others_deduct)-$totalTax;

            }
            else{
                if($employee->gross_salary >=48000){
                    $totalTax = 0.05 * $employee->gross_salary;
                    $tax = Employee::find($employee->id);
                    $tax->increment('tax', $totalTax);
                    $tax->save();
                }
                $totalSalary += $bonus - ($advanceDeduct + $deduct_absent_salary + $employee->others_deduct)-$totalTax;

            }


            //$providentFundAmount = 0.05 * $totalSalary;
            //$salaryAfterProvidentFund = $totalSalary - $providentFundAmount;

            //dd($totalSalary);
            //dd($providentFund);
            //dd($providentFundAmount);
            //dd($salaryAfterProvidentFund);

            // $bankAccount = AccountHead::find($request->account);
            // dd($bankAccount);
            // if ($totalSalary > $bankAccount->opening_balance) {
            //     return redirect()->route('payroll.salary_process.index')->with('error', 'Insufficient Balance.');
            // }

            $salaryProcess = new SalaryProcess();
            $salaryProcess->financial_year = financialYear($request->financial_year);
            $salaryProcess->date = $request->date;
            $salaryProcess->month = $request->month;
            $salaryProcess->year = $request->year;
            $salaryProcess->account_head_id = $request->account;
            $salaryProcess->total = $totalSalary;
            $salaryProcess->save();

            $employee = Employee::where('id',$employee->id)->first();
            $advanceDeduct = 0;
            //        foreach ($employees as $employee) {

            $absent_count=EmployeeAttendance::where('employee_id',$employee->id)
                ->where('present_or_absent',0)
                ->whereYear('date',$request->year)
                ->whereMonth('date',$request->month)
                ->count();
            $late_count=EmployeeAttendance::where('employee_id',$employee->id)
                ->where('present_or_absent',1)
                ->where('late',1)
                ->whereYear('date',$request->year)
                ->whereMonth('date',$request->month)
                ->count();
            $advanceDeduct = EmployeeSalaryAdvance::where('employee_id',$employee->id)
                ->where('year',$request->year)
                ->where('month', $request->month)
                ->sum('advance');



            $working_days = cal_days_in_month(CAL_GREGORIAN,$request->month,date('Y'));

            $per_day_salary=$employee->gross_salary/$working_days;

            $late=(int)($late_count/3);

            $deduct_absent_salary=$absent_count+$late*$per_day_salary;

            $salary = new Salary();
            $salary->salary_process_id = $salaryProcess->id;
            $salary->employee_id = $employee->id;
            $salary->date = $request->date;
            $salary->month = $request->month;
            $salary->year = $request->year;
            $salary->basic_salary = $employee->basic_salary;
            $salary->house_rent = $employee->house_rent;
            $salary->travel = $employee->travel;
            $salary->medical = $employee->medical;
            if($employee->gross_salary >=48000) {
                $totalTax = 0.05 * $employee->gross_salary;
                $salary->tax = $totalTax;
                $salary->save();
            }
            if ($employee->bonus){
                $salary->bonus = $employee->bonus;
                $salary->save();
            }
            $salary->advance_deduct = $advanceDeduct;
            $salary->others_deduct = $employee->others_deduct;
            $salary->absent_deduct = $deduct_absent_salary;
            $salary->provident_fund = $providentFund ?? 0;
            $salary->gross_salary = $employee->gross_salary;
            $salary->save();
            $employee->others_deduct = 0;
            $employee->bonus=0;
            $employee->save();

            // AccountHead::find($request->account)->decrement('opening_balance', $totalSalary);

            $log = new TransactionLog();
            $log->date = $request->date;
            $log->particular = 'Salary';
            $log->transaction_type = 2;
            $log->transaction_method = 2;
            $log->account_head_id = $request->account;
            $log->amount = $totalSalary;
            $log->salary_process_id = $salaryProcess->id;
            $log->save();
        }

        return redirect()->back()
            ->with('message', 'Salary process successful.');

    }

    public function leaveAll() {
        $leaves = Leave::orderBy('employee_id')
            ->with('employee')
            ->get();

        return view('payroll.leave.all', compact('leaves'));
    }

    public function leaveIndex() {
        $employees = Employee::orderBy('employee_id')->get();

        return view('payroll.leave.index', compact('employees'));
    }


    public function leavePost(Request $request) {
        $request->validate([
            'employee' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
            'note' => 'nullable|max:255',
            'type' => 'required'
        ]);

        $fromObj = new Carbon($request->from);
        $toObj = new Carbon($request->to);
        $totalDays = $fromObj->diffInDays($toObj) + 1;

        $leave = new Leave();
        $leave->employee_id = $request->employee;
        $leave->type = $request->type;
        $leave->year = $toObj->format('Y');
        $leave->from = $request->from;
        $leave->to = $request->to;
        $leave->total_days = $totalDays;
        $leave->note = $request->note;
        $leave->save();

        return redirect()->route('payroll.leave.all')->with('message', 'Leave add successful.');
    }


    public function holidayIndex() {

        return view('payroll.holiday.index');
    }

    public function holidayAdd()
    {
        return view('payroll.holiday.add');
    }

    public function holidayPost(Request $request) {
        $request->validate([
            'name' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $fromObj = new Carbon($request->from);
        $toObj = new Carbon($request->to);
        $totalDays = $fromObj->diffInDays($toObj) + 1;

        $holiday = new Holiday();
        $holiday->name = $request->name;
        $holiday->year = $toObj->format('Y');
        $holiday->from = $request->from;
        $holiday->to = $request->to;
        $holiday->total_days = $totalDays;
        $holiday->save();

        return redirect()->route('payroll.holiday.index')->with('message', 'Holiday add successful.');
    }

    public function holidayEdit(Holiday $holiday)
    {
        return view('payroll.holiday.edit',compact('holiday'));
    }

    public function holidayEditPost(Holiday $holiday,Request $request)
    {
        $request->validate([
            'name' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        $fromObj = new Carbon($request->from);
        $toObj = new Carbon($request->to);
        $totalDays = $fromObj->diffInDays($toObj) + 1;

        $holiday->name = $request->name;
        $holiday->year = $toObj->format('Y');
        $holiday->from = $request->from;
        $holiday->to = $request->to;
        $holiday->total_days = $totalDays;
        $holiday->save();

        return redirect()->route('payroll.holiday.index')->with('message', 'Holiday update successful.');
    }

    public function holidayDatatable()
    {
        $query=Holiday::query();
        return DataTables::eloquent($query)
            ->editColumn('from', function(Holiday $holiday) {
                return $holiday->from->format('j F, Y');
            })
            ->editColumn('to', function(Holiday $holiday) {
                return $holiday->to->format('j F, Y');
            })

            ->addColumn('action', function(Holiday $holiday) {
                return '<a href="'.route('payroll.holiday_edit', ['holiday' => $holiday->id]).'" class="btn btn-primary btn-sm">Edit</a>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function salaryUpdateDatatable() {
        $query = Employee::with('department', 'designation');

        return DataTables::eloquent($query)
            ->addColumn('department', function(Employee $employee) {
                return $employee->department->name;
            })
            ->addColumn('designation', function(Employee $employee) {
                return $employee->designation->name;
            })
            ->addColumn('action', function(Employee $employee) {
                return ' <a class="btn btn-info btn-sm btn-update" role="button" data-id="'.$employee->id.'">Update</a> <a href="'.route('employee.details',['employee'=>$employee->id]).'" class="btn btn-info btn-sm">Logs</a>';

                //<a class="btn btn-info btn-sm btn-advance" role="button" data-id="'.$employee->id.'">Advance Salary</a>
            })
            ->editColumn('photo', function(Employee $employee) {
                return '<img src="'.asset($employee->photo).'" height="50px">';
            })
            ->editColumn('employee_type', function(Employee $employee) {
                if ($employee->employee_type == 1)
                    return '<span class="label label-success">Permanent</span>';
                else
                    return '<span class="label label-warning">Temporary</span>';
            })

            ->editColumn('gross_salary', function(Employee $employee) {
                return ' '.number_format($employee->gross_salary, 2);
            })
            ->rawColumns(['action', 'photo', 'employee_type'])
            ->toJson();
    }
}
