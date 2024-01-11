<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilizationWorkDetails extends Model
{
    use HasFactory;
    protected $fillable = ['mobilization_work_id','mobilization_product_id'];

    public function product() {
        return $this->belongsTo(MobilizationWorkProduct::class,'mobilization_product_id', 'id');
    }
}
