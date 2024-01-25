<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PileCapConfigure extends Model
{
    use HasFactory;
    protected $table = 'pile_cap_configures';
    protected $guarded = [];

    public function commonConfigureProducts() {
        return $this->hasMany(CommonConfigureProduct::class,'common_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function costingSegment(){
        return $this->belongsTo(CostingSegment::class,'costing_segment_id');
    }
}
