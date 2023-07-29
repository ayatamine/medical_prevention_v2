<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    use HasFactory;
    protected $fillable=['title','title_ar','short_description','short_description_ar','show_in_app'];
    /* ************************ RELATIONS ************************ */
    public function scaleQuestions(){
        return $this->hasMany(\App\Models\ScaleQuestion::class,'scale_id','id');
    }
    public function responses()
    {
        return $this->hasMany(PatientScale::class);
    }
}
