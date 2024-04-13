<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashAdjustmentProduct extends Model
{
    use HasFactory;
    protected $table = 'petty_cash_adjustment_products';
    protected $guarded = [];
}
