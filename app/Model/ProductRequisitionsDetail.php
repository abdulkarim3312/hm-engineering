<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequisitionsDetail extends Model
{
    // use HasFactory;
    protected $fillable = [
        'requisition_id','project_id','segment_id','purchase_product_id','name','unit_price',
        'quantity','total','approved_at','delivered_at','received_at','approved_quantity','remaining_quantity',
        'delivered_quantity','status',
    ];

    public function product() {
        return $this->belongsTo(PurchaseProduct::class);
    }
}
