<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignSegment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function assignSegmentItems() {
        return $this->hasMany(AssignSegmentItem::class,'assign_segment_id','id');
    }
    public function estimateProject(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}
