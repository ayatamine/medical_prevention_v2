<?php

namespace App\Models;

use App\Models\Symptome;
use App\Models\SubSpeciality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speciality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_ar',
        'slug',
        'icon',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
    // public function sub_specialities():HasMany
    // {
    //     return $this->hasMany(SubSpeciality::class,'speciality_id','id');
    // }
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
    public function symptomes()
    {
        return $this->belongsToMany(
            Symptome::class,
            'speciality_symptome',
            'speciality_id',
            'symptome_id'
        );
    }
    public function chronic_disease()
    {
        return $this->belongsToMany(
            Symptome::class,
            'speciality_chronic_disease',
            'speciality_id',
            'chronic_disease_id'
        );
    }
}
