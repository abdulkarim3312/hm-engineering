<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColumnCofigure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function columnConfigureProducts() {
        return $this->hasMany(ColumnConfigureProduct::class,'column_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
}
