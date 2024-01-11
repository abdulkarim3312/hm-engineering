<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id', 'year', 'from', 'to', 'total_days', 'note', 'type'
    ];

    protected $dates = ['from', 'to'];
    
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
