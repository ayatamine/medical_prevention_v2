<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientScale extends Model
{
    
    use HasFactory;
    public static $res1="Not at all";
    public static $res2="Several days";
    public static $res3="More then half of the days";
    public static $res4="nearly every day";

    public static $res11="على الإطلاق";
    public static $res22="العديد من الأيام";
    public static $res33="أكثر من نصف الأيام";
    public static $res44="تقريباكل يوم";
    
    protected $fillable = [
        'answer',
        'patient_id',
        'scale_question_id',
    
    ];
    
    
    
    protected $dates = [
            'created_at',
        'updated_at',
    ];
    /* ************************ RELATIONS ************************ */
    /**
    * Many to One Relationship to \App\Models\Patient::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function patient() {
        return $this->belongsTo(\App\Models\Patient::class,"patient_id","id");
    }
    /**
    * Many to One Relationship to \App\Models\Scale::class
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function scaleQuestion() {
        return $this->belongsTo(\App\Models\ScaleQuestion::class,"scale_question_id","id");
    }
    /** get answer as text */
    public function answerAsText($answer) {
       switch ($answer) {
        case 2:
            return self::$res2;
            break;
        case 3:
            return self::$res3;
            break;
        case 4:
            return self::$res4;
            break;
        
        default:
            return self::$res1;
            break;
       }
    }
    public function answerAsArabicText($answer) {
       switch ($answer) {
        case 2:
            return self::$res22;
            break;
        case 3:
            return self::$res33;
            break;
        case 4:
            return self::$res44;
            break;
        
        default:
            return self::$res11;
            break;
       }
    }
}
