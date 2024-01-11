<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    protected $fillable = [
        'name', 'unit_id', 'code', 'description', 'status'
    ];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}
