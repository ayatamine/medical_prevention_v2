<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
