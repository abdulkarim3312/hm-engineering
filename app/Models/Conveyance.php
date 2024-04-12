<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conveyance extends Model
{
    use HasFactory;
    protected $table = 'conveyances';
    protected $guarded = [];
    public function conveyanceDetails() {
        return $this->hasMany(ConveyanceDetails::class,'conveyance_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
