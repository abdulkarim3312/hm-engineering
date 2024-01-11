<?php

namespace App\Models;

use App\Model\EstimateProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateProductType extends Model
{
    protected $guarded = [];

    public function estimateProduct() {
        return $this->belongsTo(EstimateProduct::class);
    }
}
