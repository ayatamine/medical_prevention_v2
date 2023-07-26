<?php

namespace App\Models;

use App\Models\LabTest;
use App\Models\Consultation;
use App\Models\Prescription as Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Summary extends Model
{
    use HasFactory;
    
    protected $fillable=[
        'description',
        'sick_leave',
        'other_lab_tests',
        'notes',
        'prescriptions',
        'consultation_id'
    ];
   
    //----------------relationships---------------------------
    public function medicines():BelongsToMany
    {
        return $this->belongsToMany(
            Medicine::class,
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
    public function otherLabTests():Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? json_decode($value) :null
        );
    }
    // public function prescriptions():Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value) => $value ? json_decode($value) :null
    //     );
    // }
    public function consultation():BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
