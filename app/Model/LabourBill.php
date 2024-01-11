<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LabourBill extends Model
{
    protected $guarded = [];

    public function employeeWiseBills() {
        return $this->hasMany(EmployeeWiseBill::class,'labour_bill_id','id');
    }
}
