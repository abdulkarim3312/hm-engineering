<?php

namespace App\Model;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequisitionLog extends Model
{
    // use HasFactory;
    protected $fillable = ['project_id','product_segment_id','product_id','requisition_id',
            'date','quantity','unit_price','total','note'
        ];
}
