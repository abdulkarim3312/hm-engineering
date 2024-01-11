<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodCostItem extends Model
{
    protected $guarded = [];

    public function labourEmployee(){
        return $this->belongsTo(Labour::class,'labour_employee_id');
    }

    public function labourEmployeeAttendence($s,$e){
        return $this->hasMany(LabourEmployeeAttendace::class,'labour_employee_id','labour_employee_id')
        ->whereDate('date','>=',$s)
        ->whereDate('date','<=', $e)->count('present_or_absent');
    }
}
