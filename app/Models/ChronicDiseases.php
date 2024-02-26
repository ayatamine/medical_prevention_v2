<?php

namespace App\Models;

use App\Models\ChronicDiseaseCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChronicDiseases extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','name_ar',
        'icon','chronic_disease_category_id'

    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function category(){
        return $this->belongsTo(ChronicDiseaseCategory::class);
    }
    public function icon():Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if($value)
                {
                    if(app()->isProduction()) return  url('storage/public/'.$value);
                    return url('storage/'.$value);
                }else return  null;
            }
        );
    }
    public function specialities()
    {
        return $this->belongsToMany(
             Speciality::class,
            'speciality_chronic_disease',
            'chronic_disease_id',
            'speciality_id',
        );
    }
}
