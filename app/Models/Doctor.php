<?php

namespace App\Models;

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
        'ballance'
    ];
    // mutators
    public function thumbnail():Attribute
    {
        return Attribute::make(
            get: fn (string $value) =>$value ? url('storage/doctors/thumbnails/'.$value) : url('storage/doctors/thumbnails/doctor.png'),
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
            Subspeciality::class,
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
}
