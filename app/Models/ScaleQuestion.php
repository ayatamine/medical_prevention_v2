<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScaleQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'question_ar',
        'scale_id',
    
    ];    
    protected $dates = [
            'created_at',
            'updated_at',
    ];
     /* ************************ RELATIONS ************************ */
    /**
    * Many to One Relationship to \App\Models\Scale::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function scale() {
        return $this->belongsTo(\App\Models\Scale::class,"scale_id","id");
    }
}
