<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;
    protected $fillable=['name','name_ar'];

    public function getPublishDateAttribute($date)
    {
         return Carbon::createFromFormat('Y-m-d H:i', $date);
    }
}
