<?php

namespace App\Models;

use App\Model\EstimateProduct;
use App\Model\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCostingProduct extends Model
{
    protected $guarded = [];

    public function product(){
        return $this->belongsTo(EstimateProduct::class,'estimate_product_id');
    }
    public function unit() {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}
