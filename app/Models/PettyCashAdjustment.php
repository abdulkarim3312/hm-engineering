<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashAdjustment extends Model
{
    use HasFactory;
    protected $table = 'petty_cash_adjustments';
    protected $guarded = [];
    public function pettyCashProduct() {
        return $this->hasMany(PettyCashAdjustmentProduct::class,'petty_cash_adjustment_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'estimate_project');
    }
}
