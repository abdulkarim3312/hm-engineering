<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LabourEmployeeAttendace extends Model
{
    protected $guarded=[];

    public function employee()
    {
        return $this->belongsTo(Labour::class,'labour_employee_id')->with('designation','project');
    }
}
