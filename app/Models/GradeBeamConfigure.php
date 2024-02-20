<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeBeamConfigure extends Model
{
    use HasFactory;
    protected $table = 'grade_beam_configures';
    protected $guarded = [];

    public function gradeBeamConfigureProducts() {
        return $this->hasMany(GradeBeamConfigureProduct::class,'beam_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }

    public function estimateFloor(){
        return $this->belongsTo(EstimateFloor::class,'estimate_floor_id');
    }

    public function gradeBeamType(){
        return $this->belongsTo(GradeBeamType::class,'beam_type_id');
    }
}
