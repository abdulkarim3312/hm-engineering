<?php

namespace App\Model;

use App\Floor;
use App\Models\ReceiptPayment;
use App\Models\SalesOrderDetails;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'order_no', 'project_id', 'flat_id','floor_id','client_id',
        'date', 'delivery_at','total','due','paid','payment_type',
    ];

    public function flats(){
        return $this->belongsToMany(Flat::class)
            ->withPivot('id', 'project_name','flat_id', 'flat_name', 'price','car','utility','other');
    }

    public function products() {
        return $this->hasMany(SalesOrderDetails::class,'sales_order_id');
    }

    public function total(){
        return $this->hasMany(SalesOrderDetails::class)->sum('total');
    }

    public function receiptPayments()
    {
        return $this->hasMany(ReceiptPayment::class);
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function floor(){
        return $this->belongsTo(Floor::class);
    }

    public function receiptPayment() {
        return $this->belongsTo(ReceiptPayment::class,'id','sales_order_id');
    }
    public function client(){
            return $this->belongsTo(Client::class);
    }
    public function flat(){
        return $this->belongsTo(Flat::class,'flat_id');
    }

}
