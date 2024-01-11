<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchaseOrder extends Model
{
    // use HasFactory;
    protected $guarded = [];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
    public function product() {
        return $this->belongsTo(PurchaseProduct::class);
    }

}
