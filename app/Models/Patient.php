<?php

namespace App\Models;

use App\Models\Allergy;
use Illuminate\Support\Str;
use App\Models\BallanceHistory;
use App\Models\ChronicDiseases;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patient extends Model
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use SoftDeletes;
    
    protected $fillable = [
        'full_name',
        'birth_date',
        'thumbnail',
        'phone_number',
        'otp_verification_code',
        'gender',
        'address',
        'other_problems',
        'height',
        'weight',
        'otp_expire_at',
        'notification_status',
        'has_physical_activity',
        'has_cancer_screening',
        'has_depression_screening',
        'account_status',
        'age',
        // 'allergy_id',
        // 'chronic_diseases_id',
        // 'family_history_id',
        'ballance',
        'little_interest_doing_things',
        'feeling_down_or_depressed'
    
    ];
    public function notificationStatus():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
    public function hasPhysicalActivity():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
    public function hasCancerScreening():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
    public function hasDepressionScreening():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
    public function littleInterestDoingThings():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
    public function feelingDownOrDepressed():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
    public function AccountStatus():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => (bool)$value,
        );
    }
     /* ************************ RELATIONS ************************ */
    /**
    * Many to One Relationship to \App\Models\ChronicDisease::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function chronicDisease() {
        return $this->belongsTo(ChronicDiseases::class,"chronic_diseases_id","id");
    }
    /**
    * Many to One Relationship to \App\Models\Allergy::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function allergy() {
        return $this->belongsTo(\App\Models\Allergy::class,"allergy_id","id");
    }
    /**
    * Many to One Relationship to \App\Models\FamilyHistory::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function familyHistory() {
        return $this->belongsTo(\App\Models\FamilyHistory::class,"family_history_id","id");
    }
        /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function otherProblems(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode(json_decode($value,true)),
        );
    } 
    public function thumbnail():Attribute
    {
        return Attribute::make(
            // get: function ($value){
            //     if(Str::startsWith($value,'patient')) return url('storage/'.$value);
            //     return $value;
            // }
            get: function ($value) {
                if($value)
                {
                    if(app()->isProduction()) return  url('storage/public/'.$value);
                    return url('storage/'.$value);
                }else 
                {
                    if(app()->isProduction()) return  url('storage/public/patients/thumbnails/doctor.png');
                    return  url('storage/patients/thumbnails/doctor.png');
                }
            }
        );
    }
    public function balanceHistories(): MorphMany
    {
        return $this->morphMany(BallanceHistory::class, 'user');
    }
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }
    public function sentMessages(): MorphMany
    {
        return $this->morphMany(Message::class, 'sender');
    }
    public function receivedMessages(): MorphMany
    {
        return $this->morphMany(Message::class, 'receiver');
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Rating::class);
    }
    public function favorites()
    {
        return $this->belongsToMany(Doctor::class, 'patient_favorites', 'patient_id', 'doctor_id');    
    }
    public function previousSummaries(){
        $consultations = $this->consultations->pluck('id')->toArray();
       return Summary::with('consultation','consultation.doctor:id,full_name')->whereIn('consultation_id',$consultations)->get();
    }
        /**
     * @return allergies collection
     * 
     */
    public function allergies():BelongsToMany
    {
        return $this->belongsToMany(
            Allergy::class,
            'patient_allergy',
            'patient_id',
            'allergy_id'
        );
    }
        /**
     * @return family_histories collection
     * 
     */
    public function family_histories():BelongsToMany
    {
        return $this->belongsToMany(
            FamilyHistory::class,
            'patient_family_history',
            'patient_id',
            'family_history_id'
        );
    }
        /**
     * @return chronic_diseases collection
     * 
     */
    public function chronic_diseases():BelongsToMany
    {
        return $this->belongsToMany(
            ChronicDiseases::class,
            'patient_chronic_diseases',
            'patient_id',
            'chronic_diseases_id'
        );
    }
    public function responses()
    {
        return $this->hasMany(PatientScale::class);
    }
}
