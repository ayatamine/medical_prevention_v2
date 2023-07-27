<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $table='medicines';
    protected $fillable = [
        'registeration_number',
        'registeration_year',
        'target',
        'type',
        'branch',
        'scientific_name',
        'commercial_name',
        'dose',
        'dose_unit',
        'pharmaceutical_form',
        'route',
        'code1',
        'code2',
        'size',
        'size_unit',
        'package_type',
        'package_size',
        'prescription_method',
        'control',
        'marketing_company_name',
        'representative',
    ];
}
