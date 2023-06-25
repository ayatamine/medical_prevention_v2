<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allergy extends Model
{
    use HasFactory;
    protected $fillable=['name','name_ar'];

    public function getPublishDateAttribute($date)
    {
         return Carbon::createFromFormat('Y-m-d H:i', $date);
    }
}
