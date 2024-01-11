<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryAdvance extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];


    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
