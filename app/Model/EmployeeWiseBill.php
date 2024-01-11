<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmployeeWiseBill extends Model
{
    protected $guarded = [];

    public function labourEmployee(){
        return $this->belongsTo(Labour::class,'employee_id');
    }
}
