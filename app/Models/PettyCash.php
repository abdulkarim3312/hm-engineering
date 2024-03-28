<?php

namespace App\Models;

use App\Model\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;
    protected $table = 'petty_cashes';
    protected $guarded = [];

    public function pettyCashProduct() {
        return $this->hasMany(PettyCashProduct::class,'petty_cash_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'estimate_project');
    }
}
