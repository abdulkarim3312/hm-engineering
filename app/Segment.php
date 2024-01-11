<?php

namespace App;

use App\Model\Project;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
   protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }
}
