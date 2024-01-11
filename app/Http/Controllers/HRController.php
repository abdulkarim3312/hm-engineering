<?php

namespace App\Http\Controllers;

use App\Model\Department;
use App\Model\DesignationLog;
use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Model\Leave;
use App\Model\Project;
use App\Model\SalaryChangeLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HRController extends Controller
{
    public function employeeIndex() {
        $departments = Department::where('status', 1)
            ->orderBy('name')->get();

        return view('hr.employee.all', compact('departments'));
    }

    public function employeeAdd() {
        $departments = Department::where('status', 1)
            ->orderBy('name')->get();
        return view('hr.employee.add', compact('departments'));
    }

    public function employeeAddPost(Request $request) {
//        return($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date',
            'department' => 'required',
            'employee_id' => 'required|max:50',
            'designation' => 'required',
            // 'project' => 'required',
            'education_qualification' => 'nullable|max:255',
            'employee_type' => 'required',
            'reporting_to' => 'nullable|string|max:255',
            'gender' => 'required',
            'marital_status' => 'required',
            'mobile_no' => 'nullable|digits:11',
            'parents_number1' => 'nullable|digits:11',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'signature' => 'nullable|image',
            'photo' => 'nullable|image',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'religion' => 'required',
            'cv' => 'nullable|mimes:doc,pdf,docx',
            'gross_salary' => 'required',
            'bank_name' => 'nullable|max:255',
            'bank_branch' => 'nullable|max:255',
            'bank_account' => 'nullable',
            'previous_salary' => 'nullable',
            'advance_qualification' => 'nullable',
        ]);

        // Upload Signature
        $signature=null;
        if ($request->signature) {

            $file = $request->file('signature');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/signature';
            $file->move($destinationPath, $filename);

            $signature = 'uploads/employee/signature/'.$filename;

        }

        // Upload Photo
        $photo=null;
        if ($request->photo) {

            $file = $request->file('photo');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/photo';
            $file->move($destinationPath, $filename);

            $photo = 'uploads/employee/photo/'.$filename;
        }


        // Upload CV
        $cv = null;
        if ($request->cv) {
            $file = $request->file('cv');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/cv';
            $file->move($destinationPath, $filename);

            $cv = 'uploads/employee/cv/'.$filename;
        }

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->employee_id = $request->employee_id;
        $employee->dob = $request->date_of_birth;
        $employee->joining_date = $request->joining_date;
        $employee->advance_qualification = $request->advance_qualification;
        $employee->confirmation_date = $request->confirmation_date;
        $employee->department_id = $request->department;
        $employee->designation_id = $request->designation;
        $employee->education_qualification = $request->education_qualification;
        $employee->employee_type = $request->employee_type;
        $employee->reporting_to = $request->reporting_to;
        $employee->parents_number1 = $request->parents_number1;
        $employee->parents_number2 = $request->parents_number2;
        $employee->previous_company = $request->previous_company;
        $employee->nid_number = $request->nid_number;
        $employee->gender = $request->gender;
        $employee->marital_status = $request->marital_status;
        $employee->mobile_no = $request->mobile_no;
        $employee->father_name = $request->father_name;
        $employee->mother_name = $request->mother_name;
        $employee->signature = $signature;
        $employee->photo = $photo;
        $employee->present_address = $request->present_address;
        $employee->permanent_address = $request->permanent_address;
        $employee->email = $request->email;
        $employee->religion = $request->religion;
        $employee->cv = $cv;


        $employee->previous_salary = $request->previous_salary ? $request->previous_salary : 0;
        $employee->gross_salary = $request->gross_salary;
        $employee->bank_name = $request->bank_name;
        $employee->bank_branch = $request->bank_branch;
        $employee->bank_account = $request->bank_account;

        $employee->medical = round($request->gross_salary * .04);
        $employee->travel = round($request->gross_salary * .12);
        $employee->house_rent = round($request->gross_salary * .24);
        $employee->basic_salary = round($request->gross_salary * .60);
        $employee->tax = 0;
        $employee->others_deduct =0;
        $employee->save();

        if($request->family_member){
            if (isset($request->family_member[0])) {
                $employee->family_member1 = $request->family_member[0];
            }
            if (isset($request->family_member[1])) {
                $employee->family_member2 = $request->family_member[1];
            }
            $employee->family_member3 = $request->family_member[2] ?? null;
            $employee->family_member4 = $request->family_member[3] ?? null;
            $employee->family_member5 = $request->family_member[4] ?? null;
            $employee->family_member6 = $request->family_member[5] ?? null;
            $employee->save();
        }

        $user = new User();
        $user->employee_id = $employee->id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile_no = $request->mobile_no;
        $user->password = bcrypt('12345678');
        $user->save();

        $designationLog = new DesignationLog();
        $designationLog->employee_id = $employee->id;
        $designationLog->department_id = $request->department;
        $designationLog->designation_id = $request->designation;
        $designationLog->date = date('Y-m-d');
        $designationLog->save();


        $salaryChangeLog = new SalaryChangeLog();
        $salaryChangeLog->employee_id = $employee->id;
        $salaryChangeLog->date = date('Y-m-d');
        $salaryChangeLog->basic_salary = round($request->gross_salary * .60);
        $salaryChangeLog->house_rent = round($request->gross_salary * .24);
        $salaryChangeLog->travel = round($request->gross_salary * .12);
        $salaryChangeLog->medical = round($request->gross_salary * .04);
        $salaryChangeLog->tax = 0;
        $salaryChangeLog->others_deduct = 0;
        $salaryChangeLog->gross_salary = round($request->gross_salary);
        $salaryChangeLog->type = 5;
        $salaryChangeLog->save();

        return redirect()->route('employee.all')->with('message', 'Employee add successfully.');
    }

    public function employeeEdit(Employee $employee) {
        $departments = Department::where('status', 1)
            ->orderBy('name')->get();

        return view('hr.employee.edit', compact('departments', 'employee'));
    }

    public function employeeEditPost(Employee $employee, Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'confirmation_date' => 'nullable|date',
            'education_qualification' => 'nullable|max:255',
            'advance_qualification' => 'nullable|max:255',
            'employee_id' => 'required|max:50',
            /*'department' => 'required',
            'designation' => 'required',*/
            'employee_type' => 'required',
            'reporting_to' => 'nullable|string|max:255',
            'gender' => 'required',
            'marital_status' => 'required',
            'mobile_no' => 'nullable|digits:11',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'signature' => 'nullable|image',
            'photo' => 'nullable|image',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'religion' => 'required',
            'cv' => 'nullable|mimes:doc,pdf,docx',
            'bank_name' => 'nullable|max:255',
            'bank_branch' => 'nullable|max:255',
            'bank_account' => 'nullable|max:255',
        ]);

        $signature = $employee->signature;
        if ($request->signature) {
            // Previous Photo
            $previousPhoto = public_path($employee->signature);
            if (file_exists($previousPhoto)) {
                if ($employee->image != null){
                    unlink($previousPhoto);
                }
            }

            $file = $request->file('signature');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/signature';
            $file->move($destinationPath, $filename);

            $signature = 'uploads/employee/signature/'.$filename;
        }

        $photo = $employee->photo;
        if ($request->photo) {
            // Previous Photo
            $previousPhoto = public_path($employee->photo);
            if (file_exists($previousPhoto)) {
                if ($employee->photo != null){
                    unlink($previousPhoto);
                }
            }

            $file = $request->file('photo');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/photo';
            $file->move($destinationPath, $filename);

            $photo = 'uploads/employee/photo/'.$filename;
        }

        // Upload CV
        $cv = $employee->cv;
        if ($request->cv) {
            // Previous CV
            $previousCv = public_path($employee->cv);

            if (file_exists($previousCv)) {
                if ($employee->cv != null){
                    unlink($previousCv);
                }
            }

            $file = $request->file('cv');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/cv';
            $file->move($destinationPath, $filename);

            $cv = 'uploads/employee/cv/'.$filename;
        }

        $employee->name = $request->name;
        $employee->dob = $request->date_of_birth;
        $employee->joining_date = $request->joining_date;
        $employee->confirmation_date = $request->confirmation_date;
        $employee->education_qualification = $request->education_qualification;
        $employee->advance_qualification = $request->advance_qualification;
        $employee->employee_id = $request->employee_id;
        /*$employee->department_id = $request->department;
        $employee->designation_id = $request->designation;*/
        $employee->employee_type = $request->employee_type;
        $employee->reporting_to = $request->reporting_to;
        $employee->gender = $request->gender;
        $employee->marital_status = $request->marital_status;
        $employee->mobile_no = $request->mobile_no;
        $employee->father_name = $request->father_name;
        $employee->mother_name = $request->mother_name;
        $employee->emergency_contact = $request->emergency_contact;
        $employee->signature = $signature;
        $employee->photo = $photo;
        $employee->present_address = $request->present_address;
        $employee->permanent_address = $request->permanent_address;
        $employee->email = $request->email;
        $employee->religion = $request->religion;
        $employee->cv = $cv;
        $employee->bank_name =$request->bank_name;
        $employee->bank_branch =$request->bank_branch;
        $employee->bank_account =$request->bank_account;
        $employee->save();

        return redirect()->route('employee.all')->with('message', 'Employee edit successfully.');
    }

    public function employeeDetails(Employee $employee) {
        $leaves = Leave::where('employee_id', $employee->id)
            ->where('year', date('Y'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('hr.employee.details', compact('employee', 'leaves'));
    }

    public function getLeave(Request $request) {
        $leaves = Leave::where('employee_id', $request->employeeId)
            ->where('year', $request->year)
            ->orderBy('created_at', 'desc')
            ->get();

        $html = view('partials.leave_table', compact('leaves'))->render();

        return response()->json(['html' => $html]);
    }

    public function employeeDesignationUpdate(Request $request) {
        $rules = [
            'department' => 'required',
            'designation' => 'required',
            'date' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $employee = Employee::find($request->id);
        $employee->department_id = $request->department;
        $employee->designation_id = $request->designation;
        $employee->save();

        $log = new DesignationLog();
        $log->employee_id = $employee->id;
        $log->department_id = $request->department;
        $log->designation_id = $request->designation;
        $log->date = $request->date;
        $log->save();

        return response()->json(['success' => true, 'message' => 'Update has been completed.']);
    }

    public function employeeDatatable() {
        $query = Employee::with('department', 'designation','project');
        //dd($query);
        return DataTables::eloquent($query)
            ->addColumn('department', function(Employee $employee) {
                return $employee->department->name??'';
            })
            ->addColumn('project', function(Employee $employee) {
                return $employee->project->name??'';
            })
            ->addColumn('designation', function(Employee $employee) {
                return $employee->designation->name??'';
            })
            ->addColumn('action', function(Employee $employee) {
                return '<a class="btn btn-primary btn-sm btn-change-designation" role="button" data-id="'.$employee->id.'">Change Designation</a> <a class="btn btn-primary btn-sm" href="'.route('employee.details', ['employee' => $employee->id]).'">Details</a> <a class="btn btn-info btn-sm" href="'.route('employee.edit', ['employee' => $employee->id]).'">Edit</a>';
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


            ->rawColumns(['action', 'photo', 'employee_type'])
            ->toJson();
    }

    public function employeeAttendance(Request $request)
    {

        $employees=Employee::with('department','designation')
            ->get();

        return view('hr.attendance.add',compact('employees'));
    }



    public function employeeAttendancePost(Request $request)
    {
        $this->validate($request,[
            'attend_date'=>'required'
        ]);

        $attendance_date=date('d-m-y',strtotime($request->attend_date));
        $error=$attendance_date.' Attendance Already Done';


        $check_attendance=EmployeeAttendance::
        whereDate('date',$request->attend_date)->count();

        if ($check_attendance > 0) {

            return redirect()->route('employee.attendance')->with('error',$error);
        }

        $employees = Employee::get();

        foreach ($employees as $employee) {
            // Present
            $presentId = 'present_'.$employee->id;
            $noteId = 'note_'.$employee->id;
            $lateId = 'late_'.$employee->id;
            $lateTimeId = 'late_time_'.$employee->id;
            $presentTimeId = 'present_time_'.$employee->id;

            $attendance = new EmployeeAttendance();
            $attendance->employee_id = $employee->id;
            $attendance->date = $request->attend_date;

            if ($request->$presentId) {
                // present
                $attendance->present_or_absent = 1;
                $attendance->note = $request->$noteId;
                $attendance->present_time =  date('H:i:s',strtotime($request->$presentTimeId ));

                if ($request->$lateId) {
                    $attendance->late = 1;
                    $attendance->late_time = date('H:i:s',strtotime($request->$lateTimeId ));
                    $attendance->note = $request->$noteId;
                } else {
                    $attendance->late = 0;
                }
            } else {
                // absent
                $attendance->present_or_absent = 0;
                $attendance->late = 0;
                $attendance->late_time = null;
                $attendance->note = $request->$noteId;
            }

            $attendance->save();

        }

        return redirect()->route('employee.attendance')->with('message','Today Employee Attendance Completed.');
    }
}
