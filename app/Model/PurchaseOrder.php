<?php

namespace App\Model;

use App\Segment;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'order_no', 'project_id','supplier_id', 'date', 'received_at','warehouse_id', 'total', 'paid', 'due'
    ];

    protected $dates = ['date', 'received_at'];

    public function products() {
        return $this->belongsToMany(PurchaseProduct::class)
            ->withPivot('id', 'purchase_order_id','name', 'unit', 'quantity', 'unit_price', 'total');
    }

    public function supplier() {
        return $this->belongsTo(Client::class,'supplier_id','id');
    }
    public function project() {
        return $this->belongsTo(Project::class);
    }
    public function segment() {
        return $this->belongsTo(Segment::class);
    }
    public function requisition() {
        return $this->belongsTo(Requisition::class);
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function payments() {
        return $this->hasMany(PurchasePayment::class);
    }
}
