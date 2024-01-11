<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankLoan extends Model
{
    protected $fillable = [
        'bank', 'description', 'amount','rate', 'duration', 'status'
    ];
}
