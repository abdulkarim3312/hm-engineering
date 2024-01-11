<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCostProduct extends Model
{
    use HasFactory;
    protected $table = 'extra_cost_products';
    protected $guarded = [];
}
