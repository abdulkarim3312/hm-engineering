<?php

namespace App\Models;

use App\Model\EstimateProductCosting;
use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SegmentConfigure extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function segmentConfigureProducts() {
        return $this->hasMany(SegmentConfigureProduct::class,'segment_configure_id','id');
    }
    public function costingSegment(){
        return $this->belongsTo(CostingSegment::class,'costing_segment_id');
    }
}
