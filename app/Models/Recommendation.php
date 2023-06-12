<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recommendation extends Model
{
    use HasFactory;
    protected $fillable = ['title','title_ar','content','content_ar','duration', 'publish_date', 'sex', 'min_age', 'max_age'];
    
    protected $dates = [
    ];
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
        return $query->where(function ($query) {
            $query->whereDate('publish_date', '<=', Carbon::today())
                  ->whereDate('publish_date', '>=', Carbon::today()->subDays($this->duration));
        });
    }
}
