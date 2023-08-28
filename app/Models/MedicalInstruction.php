<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalInstruction extends Model
{
    use HasFactory;
    protected $fillable=[
        'title','title_ar','image','content','content_ar','publish_date','duration'
    ];
    protected $casts=[
        'publish_date'=>'date'
    ];
    protected $appends=['status'];
    public function getPublishDateAttribute($date)
    {
         return date('d-m-Y H:i',strtotime($date));
    }
    //get only publishable using duration and publish date
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
    public function image():Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if($value)
                {
                    if(app()->isProduction()) return  url('storage/public/'.$value);
                    return url('storage/'.$value);
                }else return  null;
            }
        );
    }

}
