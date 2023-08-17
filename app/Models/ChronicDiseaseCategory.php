<?php

namespace App\Models;

use App\Models\ChronicDiseases;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChronicDiseaseCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','name_ar',    
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function chronicDiseases():HasMany
    {
        return $this->hasMany(ChronicDiseases::class);
    }
}
