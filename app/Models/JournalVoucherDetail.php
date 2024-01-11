<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVoucherDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function accountHead()
    {
        return $this->belongsTo(AccountHead::class);
    }
    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
