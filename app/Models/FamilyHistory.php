<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','name_ar'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
