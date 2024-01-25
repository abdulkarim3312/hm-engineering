<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrickSolingConfigure extends Model
{
    use HasFactory;
    protected $table = 'brick_soling_configures';
    protected $guarded = [];

    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}
