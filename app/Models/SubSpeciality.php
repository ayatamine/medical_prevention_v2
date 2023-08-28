<?php

namespace App\Models;

use App\Models\Symptome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubSpeciality extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'speciality_id',
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
    // public function speciality():BelongsTo
    // {
    //     return $this->belongsTo(Speciality::class,'speciality_id');
    // }
    public function symptomes()
    {
        return $this->belongsToMany(
            Symptome::class,
            'sub_speciality_symptome',
            'sub_speciality_id',
            'symptome_id'
        );
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
}
