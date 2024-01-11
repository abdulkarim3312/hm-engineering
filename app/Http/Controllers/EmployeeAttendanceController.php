<?php

namespace App\Http\Controllers;

use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class EmployeeAttendanceController extends Controller
{
    public function index() {
        $employees=Employee::with('department','designation')
            ->get();
        return view('employee.attendance',compact('employees'));
    }

    public function singleEmployeeAttendancePost(Request $request)
    {

        if ($request->employee_photo_src) {
            $data = $request->employee_photo_src;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $imgeData = base64_decode($data);
            $image_name= "/uploads/attendance/".Uuid::uuid1()->toString().'.png';
            $path = public_path() . $image_name;
            file_put_contents($path, $imgeData);
        }
        $attendanceLogs = EmployeeAttendance::where('employee_id',auth()->user()->employee_id)
                        ->whereDate('date',date('Y-m-d'))
                        ->get();
        if (count($attendanceLogs) > 1){
            EmployeeAttendance::where('employee_id',auth()->user()->employee_id)
                ->whereDate('date',date('Y-m-d'))
                ->update([
                    'status'=>0
                ]);
            EmployeeAttendance::where('employee_id',auth()->user()->employee_id)
                ->whereDate('date',date('Y-m-d'))
                ->first()
                ->update([
                    'status'=>1
                ]);
        }

        $attendance = new EmployeeAttendance();
        $attendance->employee_category_id = Employee::find(auth()->user()->employee_id)->employee_category_id ?? null;
        $attendance->employee_id = auth()->user()->employee_id;
        $attendance->present_or_absent = 1;
        $attendance->present_time = Carbon::now();
        $attendance->attendance_image = $image_name;
        $attendance->date = Carbon::now();

        $attendance->save();

        return redirect()->route('employees.attendance')->with('message', 'Attendance successfully submitted.');
    }

    public function employeePasswordChange(){
        return view('employee.dashboard');
    }

    public function employeePasswordChangePost(Request $request)
    {
        $request->validate([
            'old_password'=>'required|min:6',
            'new_password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
        ]);
        $user = auth()->user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->fill([
                'password' => bcrypt($request->new_password),
                'plain_password' => $request->new_password,
            ])->save();
            return redirect()->back()->with('message','Password changed successful');
        }
        return redirect()->back()->with('error','Old Password does not match');
    }
}
