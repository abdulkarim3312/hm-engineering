<?php

namespace App\Models;

use App\Model\Client;
use App\Model\PurchaseProduct;
use App\Model\SalesOrder;
use App\Model\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product() {
        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id');
    }
    public function customer()
    {
        return $this->belongsTo(Client::class,'customer_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function saleOrder()
    {
        return $this->belongsTo(SalesOrder::class,'sales_order_id','id');
    }

    public function user() {
        return $this->belongsTo(User::class,'employee_id');
    }
}
