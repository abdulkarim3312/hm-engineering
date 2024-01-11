<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Labour extends Model
{
    protected $guarded = [];

    public function designation() {
        return $this->belongsTo(LabourDesignation::class);
    }

    public function project() {
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
