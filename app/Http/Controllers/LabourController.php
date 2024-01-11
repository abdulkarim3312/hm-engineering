<?php

namespace App\Http\Controllers;


use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Model\Labour;
use App\Model\LabourDesignation;
use App\Model\LabourEmployeeAttendace;
use App\Model\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use DataTables;


class LabourController extends Controller
{
    public function labourIndex() {
        return view('labour.labour_employee.all');
    }

    public function labourAdd() {

        $labourDesignations = LabourDesignation::where('status', 1)
            ->orderBy('name')->get();

        $projects = Project::where('status', 1)
            ->orderBy('name')->get();

        $count = Labour::count();
        $labourId = str_pad($count+1, 4, '0', STR_PAD_LEFT);

        return view('labour.labour_employee.add', compact('labourDesignations','projects','labourId'));
    }

    public function labourAddPost(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'joining_date' => 'nullable|date',
            'designation' => 'required',
            'project' => 'nullable',
            'gender' => 'required',
            'mobile_no' => 'required|digits:11',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'religion' => 'nullable',
            'per_day_amount' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        // Upload Photo
        $photo=null;
        if ($request->photo) {

            $file = $request->file('photo');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/photo';
            $file->move($destinationPath, $filename);

            $photo = 'uploads/employee/photo/'.$filename;
        }

        $labour = new Labour();
        $labour->name = $request->name;
        $labour->labour_employee_id = $request->labour_employee_id;
        $labour->joining_date = $request->joining_date;
        $labour->project_id = $request->project;
        $labour->designation_id = $request->designation;
        $labour->gender = $request->gender;
        $labour->mobile_no = $request->mobile_no;
        $labour->father_name = $request->father_name;
        $labour->mother_name = $request->mother_name;
        $labour->photo = $photo;
        $labour->present_address = $request->present_address;
        $labour->permanent_address = $request->permanent_address;
        $labour->religion = $request->religion;
        $labour->per_day_amount = $request->per_day_amount;
        $labour->status = $request->status;
        $labour->save();

        return redirect()->route('labour.all')->with('message', 'Labour add successfully.');
    }

    public function labourEdit(Labour $labour) {
        $labourDesignations = LabourDesignation::where('status', 1)
            ->orderBy('name')->get();

        $projects = Project::where('status', 1)
            ->orderBy('name')->get();

        return view('labour.labour_employee.edit', compact('labourDesignations','projects' ,'labour'));
    }

    public function labourEditPost(Labour $labour, Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'joining_date' => 'nullable|date',
            'designation' => 'required',
            'project' => 'nullable',
            'gender' => 'required',
            'mobile_no' => 'required|digits:11',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image',
            'present_address' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255',
            'religion' => 'nullable',
            'per_day_amount' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        $photo = $labour->photo;
        if ($request->photo) {
            // Previous Photo
            $previousPhoto = public_path($labour->photo);
            unlink($previousPhoto);

            $file = $request->file('photo');
            $filename = Uuid::uuid1()->toString().'.'.$file->getClientOriginalExtension();
            $destinationPath = 'public/uploads/employee/photo';
            $file->move($destinationPath, $filename);

            $photo = 'uploads/employee/photo/'.$filename;
        }

        $labour->name = $request->name;
        $labour->labour_employee_id = $request->labour_employee_id;
        $labour->joining_date = $request->joining_date;
        $labour->project_id = $request->project;
        $labour->designation_id = $request->designation;
        $labour->gender = $request->gender;
        $labour->mobile_no = $request->mobile_no;
        $labour->father_name = $request->father_name;
        $labour->mother_name = $request->mother_name;
        $labour->photo = $photo;
        $labour->present_address = $request->present_address;
        $labour->permanent_address = $request->permanent_address;
        $labour->religion = $request->religion;
        $labour->per_day_amount = $request->per_day_amount;
        $labour->status = $request->status;
        $labour->save();

        return redirect()->route('labour.all')->with('message', 'Labour Employee edit successfully.');
    }

    public function labourDetails(Labour $labour) {
        return view('labour.labour_employee.details', compact('labour'));
    }

    public function labourEmployeeList()
    {
        $employees=Labour::with('designation','project')->get();
        return view('labour.labour_employee.labour_employee_list',compact('employees'));
    }

    public function labourDatatable() {
        $query = Labour::with( 'designation','project');

        return DataTables::eloquent($query)
            ->addColumn('project', function(Labour $labour) {
                return $labour->project->name;
            })
            ->addColumn('designation', function(Labour $labour) {
                return $labour->designation->name;
            })
            ->addColumn('action', function(Labour $labour) {
                return '<a class="btn btn-info btn-sm btn-bonus" role="button" data-id="'.$labour->id.'">Bonus</a> <a class="btn btn-primary btn-sm" href="'.route('labour.details', ['labour' => $labour->id]).'">Details</a> <a class="btn btn-info btn-sm" href="'.route('labour.edit', ['labour' => $labour->id]).'">Edit</a>';
            })
            ->editColumn('photo', function(Labour $labour) {
                return '<img src="'.asset($labour->photo).'" height="50px">';
            })
            ->editColumn('status', function(Labour $labour) {
                if ($labour->status == 1) {
                    return '<span class="btn-sm label-success">Active</span>';
                }else{
                    return '<span class="btn-sm label-danger">Inactive</span>';
                }
            })


            ->rawColumns(['action', 'photo','status'])
            ->toJson();
    }


    public function getLabourDetails(Request $request){

        $labour= Labour::where('id',$request->labourId)->first();

        return response()->json($labour);

    }

    public function labourBonusEditPost(Request $request){
        $request->validate([
            //'modal_bonus_id' => 'required|numeric',
            'modal_bonus_amount' => 'nullable|numeric',
        ]);

        $labour= Labour::where('id',$request->modal_bonus_id)->first();
        $labour->bonus=$request->modal_bonus_amount;
        $labour->save();

        return response()->json(['success' => true, 'message' => 'Bonus has been updated.']);
    }

    public function labourAttendance(Request $request)
    {
        $employees=Labour::with('designation')
            ->get();
        return view('labour.attendance.add',compact('employees'));
    }

    public function labourAttendancePost(Request $request)
    {
        $this->validate($request,[
            'attend_date'=>'required'
        ]);

        $attendance_date=date('d-m-y',strtotime($request->attend_date));
        $error=$attendance_date.' Attendance Already Done';

        $check_attendance=LabourEmployeeAttendace::
        whereDate('date',$request->attend_date)->count();

        if ($check_attendance > 0) {
            return redirect()->route('labour.attendance')->with('error',$error);
        }

        $employees = Labour::get();

        foreach ($employees as $employee) {
            // Present
            $presentId = 'present_'.$employee->id;
            $noteId = 'note_'.$employee->id;
            $presentTimeId = 'present_time_'.$employee->id;

            $attendance = new LabourEmployeeAttendace();
            $attendance->labour_employee_id = $employee->id;
            $attendance->date = $request->attend_date;
            if($request->$presentId){
                $attendance->present_time =  date('H:i:s',strtotime($request->$presentTimeId ));
            }else{
                $attendance->present_time =  date('H:i:s');
            }

            if ($request->$presentId) {
                // present
                $attendance->present_or_absent = 1;
                $attendance->note = $request->$noteId;


            } else {
                // absent
                $attendance->present_or_absent = 0;
                $attendance->note = $request->$noteId;
            }
            $attendance->save();
        }
        return redirect()->route('labour.attendance')->with('message','Today Labour Employee Attendance Completed.');
    }

    public function LabourEmployeeAttendance(Request $request)
    {
        $attendances=[];

        if ( $request->start!='' && $request->end!='') {
            $attendances=LabourEmployeeAttendace::whereDate('date','>=',$request->start)->whereDate('date','<=', $request->end)->get();

        }

        return view('labour.attendance.report',compact('attendances'));
    }
}
