<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
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
        'doctor_id',
        'prescription_title'
    ];
    protected $hidden=['created_at','updated_at'];
}
