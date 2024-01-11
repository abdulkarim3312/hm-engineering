<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSegmentDetail extends Model
{
    // use HasFactory;
    protected $fillable = ['product_segment_id','project_id','purchase_product_id','name','unit','quantity','unit_price','total'];

    public function purchaseProduct(){
        return $this->belongsTo(PurchaseProduct::class);
    }
}
