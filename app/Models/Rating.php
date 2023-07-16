<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        // 'patient_id',
        'consultation_id',
        'rating',
        'comment',
    ];
    public function consultation():BelongsTo
    {
        return $this->belonsTo(Consultation::class);
    }
}
