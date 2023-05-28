<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory,FormatsDates;
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'start_time',
        'end_time',
        'notes',
        'status',
        'finished_at'
    ];

    protected $casts=[
        'finished_at'=>'timestamp'
    ];

    //-----------------accessor -----------------
    
    public function finishedAt():Attribute
    {
        return Attribute::make(
            get: fn($value)=> $this->formatDate($value,'H:i, d-m-Y')
        );
    }
    //-------------------relationships----------
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

}
