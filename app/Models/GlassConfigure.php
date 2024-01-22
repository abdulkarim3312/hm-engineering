<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\EstimateProject;
use App\Models\GrillGlassTilesConfigure;
use App\Models\GrillGlassTilesConfigureProduct;
use App\Models\EstimateFloorUnit;
use App\Models\UnitSection;
use App\Models\GlassConfigure;
use App\Models\GlassConfigureProduct;

class GlassConfigure extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function grillGlassTilesConfigureProducts() {
        return $this->hasMany(GlassConfigureProduct::class,'grill_glass_tiles_configure_id','id');
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
}
