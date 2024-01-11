<?php

namespace App\Model;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $gurded = [];

    public function payments() {
        return $this->hasMany(SalePayment::class);
    }
    public function getOrderTotalAttribute() {
        return SalesOrder::where('client_id', $this->id)->sum('total');
    }
    public function getOrderDueAttribute() {
        return SalesOrder::where('client_id', $this->id)->sum('due');
    }
    public function getOrderPaidAttribute() {
        return SalesOrder::where('client_id', $this->id)->sum('paid');
    }
    public function getOrderDiscountAttribute() {
        return SalesOrder::where('client_id', $this->id)->sum('discount');
    }
    public function getRefundAttribute() {
        return SalesOrder::where('client_id', $this->id)->sum('refund');
    }
    //Import Supplier Copy

    public function purchasePayments() {
        return $this->hasMany(PurchasePayment::class);
    }
    public function getPurchaseOrderTotalAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('total');
    }
    public function getPurchaseOrderPaidAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('paid');
    }
    public function getPurchaseOrderDueAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('due');
    }
    public function getPurchaseOrderDiscountAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('discount');
    }
    public function getPurchaseOrderRefundAttribute() {
        return PurchaseOrder::where('supplier_id', $this->id)->sum('refund');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function saleOrder() {
        return $this->hasOne(SalesOrder::class,'client_id');
    }
}
