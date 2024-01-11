<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ScrapSalesOrder extends Model
{
    protected $guarded = [];

    public function products(){
        return $this->hasMany(ScrapSaleOrderProduct::class);
    }
    public function product(){
        return $this->belongsTo(ScrapSaleOrderProduct::class);
    }
    public function client(){

        return $this->belongsTo(Client::class);
    }

}
