<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricCostingProduct extends Model
{
    use HasFactory;
    protected $table = 'electric_costing_products';
    protected $guarded = [];

    public function electricProduct(){
        return $this->belongsTo(ElectricProduct::class,'product_id','id');
    }
}
