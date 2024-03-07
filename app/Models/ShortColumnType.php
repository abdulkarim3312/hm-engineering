<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortColumnType extends Model
{
    use HasFactory;
    protected $table = 'short_column_types';
    protected $guarded = [];
}
