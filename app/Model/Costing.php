<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Costing extends Model
{
    protected $guarded = [];

    public function estimateProducts() {
        return $this->hasMany(EstimateProductCosting::class,'costing_id','id');
    }

    public function estimateProject() {
        return $this->belongsTo(EstimateProject::class);
    }
}
