<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlasterConfigure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function plasterConfigureProducts() {
        return $this->hasMany(PlasterConfigureProduct::class,'plaster_configure_id','id');
    }

    public function plasterConfigureProduct() {
        return $this->belongsTo(PlasterConfigureProduct::class,'plaster_configure_id','id');
    }
}
