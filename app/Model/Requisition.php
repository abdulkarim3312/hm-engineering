<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Segment;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];

    public function requisitionProducts() {
        return $this->hasMany(ProductRequisition::class,'requisition_id','id');
    }

    public function approvedQuantity() {
        return $this->hasMany(ProductRequisition::class,'requisition_id','id')->sum('approved_quantity');
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function segment() {
        return $this->belongsTo(Segment::class, 'segment_id');
    }
    public function requisitionDetails(){
        return $this->hasMany(ProductRequisitionsDetail::class);
    }
    public function payments() {
        return $this->hasMany(PurchasePayment::class,'requisition_id','id')
            ->with('requisition');
    }
}
