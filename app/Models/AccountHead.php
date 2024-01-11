<?php

namespace App\Models;

use App\Model\AccountHeadType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(AccountHeadType::class,'account_head_type_id','id');
    }
}
