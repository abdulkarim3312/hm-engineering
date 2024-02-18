<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SandFilling extends Model
{
    use HasFactory;
    protected $table = 'sand_fillings';
    protected $guarded = [];

    public function sandFillingConfigureProducts() {
        return $this->hasMany(SandFillingConfigure::class,'sand_filling_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}
