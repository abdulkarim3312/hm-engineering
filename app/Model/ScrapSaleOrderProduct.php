<?php

namespace App\Model;

use App\Segment;
use Illuminate\Database\Eloquent\Model;

class ScrapSaleOrderProduct extends Model
{
    protected $guarded = [];

    public function product() {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id', 'id')->with('unit');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
}
