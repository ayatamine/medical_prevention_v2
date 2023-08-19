<?php

namespace App\Models;

use App\Models\SubSpeciality;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Symptome extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','name_ar',
        'icon','symptome_category_id'
    
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function category(){
        return $this->belongsTo(SymptomeCategory::class);
    }
    public function subSpecialities()
    {
        return $this->belongsToMany(
            SubSpeciality::class,
            'sub_speciality_symptome',
            'symptome_id',
            'sub_speciality_id',
        );
    }
}
