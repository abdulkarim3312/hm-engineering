<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatConfigure extends Model
{
    use HasFactory;

    protected $table = 'mat_configures';
    protected $guarded = [];

    public function matConfigureProducts () {
        return $this->hasMany(MatConfigureProduct::class,'common_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function costingSegment(){
        return $this->belongsTo(CostingSegment::class,'costing_segment_id');
    }
    public function footingType(){
        return $this->belongsTo(Batch::class,'footing_type_id');
    }
}
