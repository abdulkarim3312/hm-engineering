<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillStatementDescription extends Model
{
    use HasFactory;
    protected $table = 'bill_statement_descriptions';
    protected $guarded = [];
}
