<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacingBricksWorkProduct extends Model
{
    use HasFactory;
    protected $table = 'facing_bricks_work_products';
    protected $guarded = [];
    public function unitSection(){
        return $this->belongsTo(UnitSection::class,'unit_section_id');
    }
}
