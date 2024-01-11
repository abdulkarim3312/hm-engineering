<?php

namespace App\Model;

use App\FlatSalesOrder;
use App\Floor;
use Illuminate\Database\Eloquent\Model;

class SaleInventory extends Model
{
    protected $gurded = [];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function flatSaleOrder()
    {
        return $this->belongsTo(FlatSalesOrder::class);
    }

}
