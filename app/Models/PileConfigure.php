<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PileConfigure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pileConfigureProducts() {
        return $this->hasMany(PileConfigureProduct::class,'pile_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}

