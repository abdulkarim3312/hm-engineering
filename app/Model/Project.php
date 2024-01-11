<?php

namespace App\Model;

use App\Floor;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name','address','status'
    ];


    public function expense()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function flat()
    {
        return $this->hasone(Flat::class);
    }

    public function floor()
    {
        return $this->hasone(Floor::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }
}
