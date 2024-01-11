<?php

namespace App\Models;

use App\Model\EstimateProject;
use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateFloor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function estimateProject()
    {
        return $this->belongsTo(EstimateProject::class);
    }
}
