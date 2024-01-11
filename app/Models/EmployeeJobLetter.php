<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeJobLetter extends Model
{
    use HasFactory;
    protected $table = 'employee_job_letter';
    protected $guarded = [];
}
