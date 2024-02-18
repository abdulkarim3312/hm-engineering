<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarthWork extends Model
{
    use HasFactory;
    protected $table = 'earth_works';
    protected $guarded = [];
    public function earthWorkConfigureProducts() {
        return $this->hasMany(EarthWorkConfigure::class,'earth_work_id','id');
    }
     public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}
