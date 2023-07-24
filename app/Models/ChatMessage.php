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
    protected $appends=['attachement_type'];
    
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
    public function getAttachementTypeAttribute($value)
    {
        $extension = pathinfo($this->attachement, PATHINFO_EXTENSION);
        return $this->getFileType($extension);

    }
    private function getFileType($extension)
    {
        // Map common file extensions to file types
        $fileTypes = [
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
            'bmp' => 'image',
            'webp' => 'image',
            'tiff' => 'image',
            'tif' => 'image',
            'svg' => 'image',
            'ai' => 'image', // Adobe Illustrator
            'psd' => 'image', // Adobe Photoshop
            'eps' => 'image', // Encapsulated PostScript
            'icns' => 'image', // macOS Icon
    
            'mp3' => 'audio',
            'wav' => 'audio',
            'ogg' => 'audio',
            'flac' => 'audio',
            'aac' => 'audio',
            'wma' => 'audio',
            'm4a' => 'audio',
    
            'mp4' => 'video',
            'avi' => 'video',
            'mkv' => 'video',
            'mov' => 'video',
            'wmv' => 'video',
            'flv' => 'video',
            'webm' => 'video',
            'mpeg' => 'video',
            'mpg' => 'video',
    
            'txt' => 'text',
            'csv' => 'text', // Comma-Separated Values
            'xml' => 'text', // eXtensible Markup Language
            'json' => 'text', // JavaScript Object Notation
            'pdf' => 'text', // Portable Document Format
            'doc' => 'text', // Microsoft Word
            'docx' => 'text', // Microsoft Word
            'xls' => 'text', // Microsoft Excel
            'xlsx' => 'text', // Microsoft Excel
            'ppt' => 'text', // Microsoft PowerPoint
            'pptx' => 'text', // Microsoft PowerPoint
    
            'zip' => 'archive',
            'rar' => 'archive',
            '7z' => 'archive',
            'tar' => 'archive',
            'gz' => 'archive', // Gzip
            'bz2' => 'archive', // Bzip2
    
            'ttf' => 'font', // TrueType Font
            'otf' => 'font', // OpenType Font
            'woff' => 'font', // Web Open Font Format
            'woff2' => 'font', // Web Open Font Format 2
    
            'exe' => 'executable', // Windows
            'dmg' => 'executable', // macOS
            'deb' => 'executable', // Debian Linux
            'apk' => 'executable', // Android Package
        ];

        return isset($fileTypes[$extension]) ? $fileTypes[$extension] : 'unknown';
    }
}
