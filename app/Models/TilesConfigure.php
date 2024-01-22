<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TilesConfigure extends Model
{
    use HasFactory;
    protected $table = 'tiles_configures';
    protected $guarded = [];

    public function grillGlassTilesConfigureProducts() {
        return $this->hasMany(TilesConfigureProduct::class,'grill_glass_tiles_configure_id','id');
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
