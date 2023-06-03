<?php

namespace App\Models;

use App\Models\LabTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Summary extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'description',
        'sick_leave',
        'other_lab_tests',
        'notes'
    ];
    //----------------relationships---------------------------
    public function prescriptions():BelongsToMany
    {
        return $this->belongsToMany(
            Prescription::class,
            'summary_prescription',
            'summary_id',
            'prescription_id'
        );
    }
    public function labTests():BelongsToMany
    {
        return $this->belongsToMany(
            LabTest::class,
            'summary_lab_test',
            'summary_id',
            'lab_test_id'
        );
    }
}
