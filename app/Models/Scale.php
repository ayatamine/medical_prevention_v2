<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    use HasFactory;
    /* ************************ RELATIONS ************************ */
    public function scaleQuestions(){
        return $this->hasMany(\App\Models\ScaleQuestion::class,'scale_id','id');
    }
}
