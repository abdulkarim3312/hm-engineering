<?php

namespace App\Models;

use App\Model\EstimateProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanitaryConfigureProduct extends Model
{
    use HasFactory;
    protected $table = 'sanitary_configure_products';
    protected $guarded = [];
    public function products() {
        return $this->belongsTo(SanitaryProduct::class,'product_id','id');
    }
}
