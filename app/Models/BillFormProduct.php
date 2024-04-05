<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillFormProduct extends Model
{
    use HasFactory;
    protected $table = 'bill_form_products';
    protected $guarded = [];
}
