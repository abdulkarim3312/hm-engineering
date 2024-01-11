<?php

namespace App\Model;

use App\Segment;
use Illuminate\Database\Eloquent\Model;

class PurchaseInventoryLog extends Model
{
    protected $fillable = [
        'purchase_product_id','purchase_order_id','project_id','type', 'date', 'quantity', 'unit_price', 'supplier_id',
        'note'
    ];

    protected $dates = ['date'];

    public function supplier() {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function product() {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_product_id', 'id')->with('unit');
    }
    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
    public function requisition() {
        return $this->belongsTo(Requisition::class);
    }
}
