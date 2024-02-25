<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\SupportSubjectType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportRequest extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function subject(): BelongsTo
    {
        return $this->belongsTo(SupportSubjectType::class);
    }
    public function user()
    {
        if($this->user_type == 'doctor') return $this->belongsTo(Doctor::class,'user_id','id');
        return $this->belongsTo(Patient::class,'user_id','id');
    }
}
