<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BricksSoling extends Model
{
    use HasFactory;
    protected $table = 'bricks_solings';
    protected $guarded = [];
     public function bricksSolingConfigureProducts() {
        return $this->hasMany(BrickSolingConfigure::class,'brick_soling_id','id');
    }
     public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}
