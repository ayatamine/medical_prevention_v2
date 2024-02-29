<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;
    protected $fillable=[
        'drug_name',
        'route',
        'dose',
        'unit',
        'frequancy',
        'duration',
        'duration_unit',
        'shape',
        'prn',
        'instructions',
        'doctor_id', //if it is not null means that doctor save  this drug to his drug list
        'consultation_id',
    ];
    protected $hidden=['created_at','updated_at'];

}
