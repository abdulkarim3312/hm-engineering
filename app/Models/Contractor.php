<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    use HasFactory;
    protected $table = 'contractors';
    protected $guarded = [];
    public function projects(){
        return $this->belongsTo(Project::class, 'project_id');
    }
}
