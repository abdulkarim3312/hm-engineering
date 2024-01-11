<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'company_name', 'mobile','email', 'address', 'status'
    ];

    public function payments() {
        return $this->hasMany(PurchasePayment::class);
    }
    public function getOrderTotalAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('total');
    }
    public function getOrderPaidAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('paid');
    }
    public function getOrderDueAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('due');
    }
    public function getOrderDiscountAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('discount');
    }
    public function getOrderRefundAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('refund');
    }
}
