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
}
