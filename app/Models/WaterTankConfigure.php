<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterTankConfigure extends Model
{
    use HasFactory;
    protected $table = 'water_tank_configures';
    protected $guarded = [];
      public function waterTankConfigureProducts() {
        return $this->hasMany(WaterTankConfigureProduct::class,'common_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function costingSegment(){
        return $this->belongsTo(CostingSegment::class,'costing_segment_id');
    }
}
