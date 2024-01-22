<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TilesConfigureProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tiles_configure_products';
    public function unitSection(){
        return $this->belongsTo(UnitSection::class,'unit_section_id');
    }
}
