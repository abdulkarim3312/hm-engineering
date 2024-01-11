<?php

namespace App;

use App\Model\Flat;
use App\Model\Project;
use App\Model\SalesOrder;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    protected $fillable = [
        'name', 'project_id', 'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function flat()
    {
        return $this->hasMany(Flat::class)->orderBy('name');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
