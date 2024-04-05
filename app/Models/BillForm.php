<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillForm extends Model
{
    use HasFactory;
    protected $table = 'bill_forms';
    protected $guarded = [];

    public function billFormProduct() {
        return $this->hasMany(BillFormProduct::class,'bill_adjustment_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
