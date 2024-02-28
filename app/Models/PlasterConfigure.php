<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlasterConfigure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function plasterConfigureProducts() {
        return $this->hasMany(PlasterConfigureProduct::class,'plaster_configure_id','id');
    }

    public function plasterConfigureProduct() {
        return $this->belongsTo(PlasterConfigureProduct::class,'plaster_configure_id','id');
    }

    public function project() {
        return $this->belongsTo(EstimateProject::class,'estimate_project_id','id');
    }
    public function floor() {
        return $this->belongsTo(EstimateFloor::class,'estimate_floor_id','id');
    }
    public function floorUnit() {
        return $this->belongsTo(EstimateFloorUnit::class,'estimate_floor_unit_id','id');
    }
}
