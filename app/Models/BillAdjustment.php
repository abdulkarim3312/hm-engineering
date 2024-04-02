<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillAdjustment extends Model
{
    use HasFactory;
    protected $table = 'bill_adjustments';
    protected $guarded = [];

    public function billAdjustmentProduct() {
        return $this->hasMany(BillAdjustmentProduct::class,'bill_adjustment_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
