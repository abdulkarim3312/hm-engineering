<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlasterConfigureProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bricksConfigureProduct(){
        return $this->belongsTo(BricksConfigureProduct::class,'bricks_configure_product_id');
    }
    public function plasterConfigure(){
        return $this->belongsTo(PlasterConfigure::class,'plaster_configure_id');
    }
}
