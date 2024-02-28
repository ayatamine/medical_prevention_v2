<?php

namespace App\Models;

use App\Models\Summary;
use App\Models\ChatMessage;
use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'finished_at',
        'paymentintent_id',
        'prescription_valid_until' //the only attribute in the prescription so we remove the prescription entity and include it here

    ];

    protected $casts=[
        'finished_at'=>'timestamp'
    ];

    //-----------------accessor -----------------

    // public function createdAt():Attribute
    // {
    //     return Attribute::make(
    //         get: fn($value)=> $this->formatDate($value,'d-m-Y, H:i')
    //     );
    // }
    public function finishedAt():Attribute
    {
        return Attribute::make(
            get: fn($value)=> $this->formatDate($value,'d-m-Y, H:i')
        );
    }
    public function updatedAt():Attribute
    {
        return Attribute::make(
            get: fn($value)=> $this->formatDate($value,'d-m-Y, H:i')
        );
    }
    //-------------------relationships----------
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class)->withTrashed();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class)->withTrashed();
    }
    public function scopeInCompleted(){
        return $this->where('status','incompleted');
    }
    public function scopeCompleted(){
        return $this->whereNotNull('finished_at');
    }
    public function scopeRejected(){
        return $this->where('status','rejected');
    }
    public function chatMessages():HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
    public function summary():HasOne
    {
        return $this->hasOne(Summary::class);
    }
    public function review():HasOne
    {
        return $this->hasOne(Rating::class);
    }

    public function drugs():HasMany
    {
        return $this->hasMany(Drug::class);
    }


}
