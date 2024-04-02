<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillAdjustmentProduct extends Model
{
    use HasFactory;
    protected $table = 'bill_adjustment_products';
    protected $guarded = [];
}
