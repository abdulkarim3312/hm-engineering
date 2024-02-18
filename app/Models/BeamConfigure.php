<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeamConfigure extends Model
{
    use HasFactory;
    protected $table = 'beam_configures';
    protected $guarded = [];

    public function beamConfigureProducts() {
        return $this->hasMany(BeamConfogureProduct::class,'beam_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function estimateFloor(){
        return $this->belongsTo(EstimateFloor::class,'estimate_floor_id');
    }
    public function beamType(){
        return $this->belongsTo(BeamType::class,'beam_type_id');
    }
}
