<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricProduct extends Model
{
    use HasFactory;
    protected $table = 'electric_products';
    protected $guarded = [];
}
