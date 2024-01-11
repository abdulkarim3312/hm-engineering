<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductRequisition extends Model
{
    protected $guarded = [];

    public function product() {
        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id');
    }
    public function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
