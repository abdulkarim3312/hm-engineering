<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodCost extends Model
{
    protected $guarded = [];

    public function foodCostItems() {
        return $this->hasMany(FoodCostItem::class,'food_cost_id','id');
    }
}
