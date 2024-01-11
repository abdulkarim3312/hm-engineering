<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeOfConcrete extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }

    public function batch(){
        return $this->belongsTo(Batch::class,'batch_id');
    }
}
