<?php

namespace App\Model;

use App\Segment;
use Illuminate\Database\Eloquent\Model;

class PurchaseProductUtilize extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function product() {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id', 'id')->with('unit');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
}
