<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCosting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products() {
        return $this->hasMany(ExtraCostingProduct::class,'extra_costing_id','id');
    }

    public function estimateProject() {
        return $this->belongsTo(EstimateProject::class);
    }
}
