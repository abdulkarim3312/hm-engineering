<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlatSalesOrder extends Model
{

    protected $table = 'flat_sales_order';

    public function Flats()
    {
        return $this->belongsTo(Flat::class);
    }
}
