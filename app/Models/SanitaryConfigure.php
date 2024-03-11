<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanitaryConfigure extends Model
{
    use HasFactory;
    protected $table = 'sanitary_configures';
    protected $guarded = [];

    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function estimateFloor(){
        return $this->belongsTo(EstimateFloor::class,'estimate_floor');
    }
    public function estimateFloorUnit(){
        return $this->belongsTo(EstimateFloorUnit::class,'estimate_floor_unit');
    }
}
