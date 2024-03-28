<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashProduct extends Model
{
    use HasFactory;
    protected $table = 'petty_cash_products';
    protected $guarded = [];
}
