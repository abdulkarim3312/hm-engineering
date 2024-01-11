<?php

namespace App\Http\View\Composers;

use App\Model\Category;
use App\Model\Client;
use App\Model\Employee;
use App\Model\EmployeeAttendance;
use App\Model\Holiday;
use App\Model\Leave;
use App\Model\PackageCategory;
use App\Model\Requisition;
use App\Model\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\View\View;
use Cart;

class AdminComposer
{
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $requisitions = Requisition::where('status', 0)
                    ->orderBy('id','DESC')
                    ->get();

        $purchaseOrders = PurchaseOrder::where('warehouse_id', null)
            ->orderBy('id','DESC')
            ->get();


        //MissingAttendance

        $missingEmployeeIds=[];
        $employees=[];
        $today = Carbon::today();
        $isHoliday = Holiday::where(function ($query) use ($today) {
            $query->where('from', '<=', $today)
                ->where('to', '>=', $today);
        })->exists();

        $currentTime = Carbon::now();

        $nineAM = Carbon::today()->setHour(9)->setMinute(15)->setSecond(0);

        if ($currentTime->greaterThan($nineAM)) {

            if ($isHoliday ) {
                return($missingEmployeeIds);
            }else{
                $leaveEmployeeIds = Leave::where(function ($query) use ($today) {
                    $query->where('from', '<=', $today)
                        ->where('to', '>=', $today);
                })->pluck('employee_id');

                $missingEmployeeIds = Employee::whereNotIn('id', function ($query) use ($today) {
                    $query->select('employee_id')
                        ->from('employee_attendances')
                        ->whereDate('created_at','!=', Carbon::FRIDAY)
                        ->whereDate('created_at', $today);
                })
                    ->pluck('id');
                $employees = Employee::whereIn('id',$missingEmployeeIds)->whereNotIn('id',$leaveEmployeeIds)->get();

            }
        }


        $data = [
            'requisitions' => $requisitions,
            'purchaseOrders' => $purchaseOrders,
            'employees' => $employees,
        ];

        $view->with('layoutData', $data);
    }
}
