<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BricksConfigure extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bricksConfigureProducts() {
        return $this->hasMany(BricksConfigureProduct::class,'bricks_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function estimateFloor(){
        return $this->belongsTo(EstimateFloor::class,'estimate_floor_id');
    }
    public function estimateFloorUnit(){
        return $this->belongsTo(EstimateFloorUnit::class,'estimate_floor_unit_id');
    }
    public function unitSection(){
        return $this->belongsTo(UnitSection::class,'unit_section_id');
    }
}
