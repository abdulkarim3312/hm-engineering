<?php

namespace App\Models;

use App\Model\EstimateProduct;
use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignSegmentItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assignSegmenProducts() {
        return $this->hasMany(AssignSegmentProduct::class,'assign_segment_item_id','id');
    }
    public function segmentConfigure(){
        return $this->belongsTo(SegmentConfigure::class,'segment_configure_id');
    }
    public function estimateProject(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function estimateProduct(){
        return $this->belongsTo(EstimateProduct::class,'estimate_product_id');
    }
}
