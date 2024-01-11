<?php

namespace App\Model;

use App\Floor;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    protected $fillable = [
        'name','status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function saleOrders()
    {
        return $this->hasMany(SalesOrder::class,'flat_id');
    }
}
