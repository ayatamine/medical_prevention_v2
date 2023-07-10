<?php

namespace App\Models;

use App\Events\ChatMessageEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'consultation_id',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'content',
        'attachement'
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

    public function broadcastMessage()
    {
        Broadcast::channel('consultation.' . $this->consultation_id, function ($user) {
            return (int) $user->id == $this->sender_id ||(int) $user->id == $this->receiver_id;
        });

        Broadcast::event('consultation.' . $this->consultation_id, new ChatMessageEvent($this))->toOthers();
    }
    public function getAttachementAttribute($value)
    {

          return ($value) ? url('storage/'.$value) :null;

    }
}
