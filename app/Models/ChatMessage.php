<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'consultation_id',
        'sender_id',
        'receiver_id',
        'content',
        'attachment'
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function sender(): MorphTo
    {
        return $this->morphTo('sender');
    }

    public function receiver(): MorphTo
    {
        return $this->morphTo('receiver');
    }
    
}
