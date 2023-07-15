<?php

namespace App\Models;

use App\Models\Rating;
use App\Models\Prescription;
use App\Models\SubSpeciality;
use App\Models\BallanceHistory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model  
{
    use HasFactory;
    use HasFactory;
    use HasApiTokens;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'birth_date',
        'thumbnail',
        'id_number',
        'phone_number',
        'otp_verification_code',    
        'otp_expire_at',
        'is_phone_verified',
        'gender',
        'notification_status',
        'online_status',
        'account_status',
        'email',
        'job_title',
        'classification_number',
        'insurance_number',
        'medical_licence_file','cv_file','certification_file',
        'ballance',
        'location',
    ];
    // accesors
    public function thumbnail():Attribute
    {
        return Attribute::make(
            get: fn (string $value) =>$value ? url('storage/'.$value) : url('storage/doctors/thumbnails/doctor.png'),
        );
    }
    public function cvFile():Attribute
    {
        return Attribute::make(
            get: fn ($value) =>$value ? url('storage/doctors/cv_files/'.$value) : null,
        );
    }
    public function medicalLicenceFile():Attribute
    {
        return Attribute::make(
            get: fn ($value) =>$value ? url('storage/doctors/medical_licences/'.$value) : null,
        );
    }
    public function certificationFile():Attribute
    {
        return Attribute::make(
            get: fn ( $value) =>$value ? url('storage/doctors/certifications/'.$value) : null,
        );
    }
    //////////////////////////////////- relationships-///////////////////////
    /**
     * @return subspecialities collection
     * 
     */
    public function sub_specialities():BelongsToMany
    {
        return $this->belongsToMany(
            SubSpeciality::class,
            'doctor_sub_speciality',
            'doctor_id',
            'sub_speciality_id'
        );
    }
    /**
     * @return ballancehistory collection
     */
    public function balanceHistories(): MorphMany
    {
        return $this->morphMany(BallanceHistory::class, 'user');
    }
    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class,'doctor_id','id');
    }
    public function sentMessages(): MorphMany
    {
        return $this->morphMany(Message::class, 'sender');
    }
    public function receivedMessages(): MorphMany
    {
        return $this->morphMany(Message::class, 'receiver');
    }
    public function pendingConsultations(): HasMany
    {
        return $this->consultations()->where('status', 'pending')->with('patient');
    }
    
    public function inProgressConsultations(): HasMany
    {
        return $this->consultations()->where('status', 'in_progress')->with('patient');
    }
    
    public function completedConsultations(): HasMany
    {
        return $this->consultations()->where('status', 'completed')->with('patient');
    }
    public function incompletedConsultations(): HasMany
    {
        return $this->consultations()->where('status', 'incompleted')->with('patient');
    }
    
    public function canceledConsultations(): HasMany
    {
        return $this->consultations()->where('status', 'canceled')->with('patient');
    }
    //prescription
    public function prescriptions():HasMany
    {
        return $this->hasMany(Prescription::class);
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Rating::class);
    }
    public function scopeOnline(){
        return $this->where('online_status',true);
    }
    public function scopeActive(){
        return $this->where('account_status',"accepted");
    }
    public function patientsFavoritedMe()
    {
        return $this->belongsToMany(Patient::class, 'patient_favorites', 'patient_id', 'doctor_id');
    }
}
