<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilizationWork extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function products() {
        return $this->hasMany(MobilizationWorkDetails::class,'mobilization_work_id','id');
    }
    public function estimateProject() {
        return $this->belongsTo(EstimateProject::class,'mobilization_project_id', 'id');
    }
}
