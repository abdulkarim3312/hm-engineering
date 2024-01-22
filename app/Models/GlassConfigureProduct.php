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

class GlassConfigureProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function unitSection(){
        return $this->belongsTo(UnitSection::class,'unit_section_id');
    }
}
