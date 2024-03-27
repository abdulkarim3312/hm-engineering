<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillStatement extends Model
{
    use HasFactory;
    protected $table = 'bill_statements';
    protected $guarded = [];
    public function billStatementDescription() {
        return $this->hasMany(BillStatementDescription::class,'bill_statements_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'estimate_project','id');
    }
}
