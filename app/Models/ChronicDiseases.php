<?php

namespace App\Models;

use App\Models\ChronicDiseaseCategory;
use Illuminate\Database\Eloquent\Model;
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
}
