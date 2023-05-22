<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalInstruction extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','title_ar','image','content','content_ar','publish_date','duration'
    ];

}
