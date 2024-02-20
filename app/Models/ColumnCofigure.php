<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColumnCofigure extends Model
{
    use HasFactory;

    protected $table = 'column_cofigures';
    protected $guarded = [];

    public function columnConfigureProducts() {
        return $this->hasMany(ColumnConfigureProduct::class,'column_configure_id','id');
    }
    public function project(){
        return $this->belongsTo(EstimateProject::class,'estimate_project_id');
    }
    public function columnFloor(){
        return $this->belongsTo(EstimateFloor::class,'estimate_floor_id');
    }
    public function columnType(){
        return $this->belongsTo(ColumnType::class,'column_type_id');
    }
}
