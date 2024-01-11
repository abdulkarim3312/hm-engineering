<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSegment extends Model
{
    protected $fillable = [
        'name','description', 'status'
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
    
    public function products() {
        return $this->hasMany(PurchaseProduct::class);
    }
    
    public function segmentDetails(){
        return $this->hasMany(ProjectSegmentDetail::class,'product_segment_id');
    }
}
