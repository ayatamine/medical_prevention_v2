<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected $hidden=['updated_at'];
    public function consultation():BelongsTo
    {
        return $this->belonsTo(Consultation::class);
    }
    public function createdAt():Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? date('d-m-Y',strtotime($value)) :null
        );
    }
}
