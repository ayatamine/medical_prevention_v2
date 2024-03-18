<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_pm'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function startTime():Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Carbon::createFromFormat('H:i:s',$value)->format('h:i');
            }
        );
    }
    public function endTime():Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Carbon::createFromFormat('H:i:s',$value)->format('h:i');
            }
        );
    }
}
