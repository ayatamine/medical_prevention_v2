<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recommendation extends Model
{
    use HasFactory;
    protected $fillable = ['title','title_ar','content','content_ar','duration', 'publish_date', 'sex', 'min_age', 'max_age'];
    
    protected $dates = [
    ];
    protected $appends=['status'];
    public $timestamps = false;
        /* ************************ RELATIONS ************************ */
    public function scopeByAgeAndSex($query, $sex, $age)
    {
        return $query->where('sex', $sex)
                     ->where('min_age', '<=', $age)
                     ->where('max_age', '>=', $age);
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
