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

    //get only publishable using duration and publish date
    public function scopePublishable($query){
        return $query->whereDate('publish_date', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('duration')
                            ->orWhereDate('publish_date', '>=', Carbon::now()->subDays($this->duration));
                    });
     }  
     public function status(): Attribute
     {
        return Attribute::make(
            get:function(){
                if($this->publishable()) return trans('dashboard.running');
                if( Carbon::now()->subDays($this->duration) < carbon::now()) return trans('dashboard.not_published_yet');
                return trans('dashboard.finished');
            }
        );
     } 

}
