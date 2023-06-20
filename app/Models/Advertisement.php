<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory,FormatsDates;
    protected $fillable=[
        'title','title_ar','image','text','text_ar','publish_date','duration'
    ];
    protected $casts=[
        'publish_date'=>'date'
    ];
    protected $appends=['status'];
    //get only publishable using duration and publish date
 
    public function getPublishDateAttribute($date)
    {
         return $this->formatDate($date,'d-m-Y H:i');
    }
    public function scopePublishable($query)
    {
        return $query->whereDate('publish_date', '<=', Carbon::today())
        ->whereRaw('DATE_ADD(publish_date, INTERVAL duration DAY) > NOW()');
    }
    public function scopeFinished($query)
    {
        return $query->whereDate('publish_date', '<=', now())
            ->whereRaw('DATE_ADD(publish_date, INTERVAL duration DAY) <= NOW()');
    }
    public function scopeUnpublished($query)
    {
        return $query->whereDate('publish_date', '>', now());
    }
    public function status(): Attribute
    {
       return Attribute::make(
           get:function(){
               if($this->publishable()->count()) return trans('dashboard.running');
               if($this->unpublished()->count()) return trans('dashboard.not_published_yet');
               if($this->finished()->count()) return trans('dashboard.finished');
           }
       );
    }
}
