<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SymptomeCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','name_ar',
    
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function symptomes():HasMany
    {
        return $this->hasMany(Symptome::class);
    }
}
