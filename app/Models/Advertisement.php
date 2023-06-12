<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','title_ar','image','text','text_ar','publish_date','duration'
    ];
    protected $casts=[
        'publish_date'=>'date'
    ];
    //get only publishable using duration and publish date
    public function scopePublishable($query){
        return $query->whereDate('publish_date', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('duration')
                            ->orWhereDate('publish_date', '>=', Carbon::now()->subDays($this->duration));
                    });
     }   
}
