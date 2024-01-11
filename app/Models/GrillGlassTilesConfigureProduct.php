<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrillGlassTilesConfigureProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function unitSection(){
        return $this->belongsTo(UnitSection::class,'unit_section_id');
    }
}
