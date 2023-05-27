<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BallanceHistory extends Model
{
    use HasFactory,FormatsDates;
    protected $fillable=[
        'user_id',
        'user_type',
        'amount',
        'operation_type',
        'consult_id'
    ];
    protected $appends=['operation_name'];

    public static $RFC = 'revenu_from_consult';
    public static $PFC = 'pay_for_consult';
    public static $WD = 'withdraw';
    public static $DP = 'deposit';

    //-------------mutators------------------
    //get_operation_type_attribute
    public function Ammount():Attribute
    {
    
        if($this->operation_type == self::$RFC || $this->operation_type == self::$DP){

            return Attribute::make(
                get: fn ($value) => '+'.$value
            ); 
        }
        return Attribute::make(
            get: fn ($value) => '-'.$value
        ); 

    }
    public function operationName():Attribute
    {
        $operation_name='';
        switch ($this->operation_type) {
            case   self ::$RFC:
                $operation_name = 'consult';
                break;
            case self::$PFC:
                $operation_name = 'consult #887452';
                break;
            case self::$WD:
                $operation_name = 'Withdraw Request';
                break;
            default:
                $operation_name = 'Deposit Request';
                break;
        }   
        return Attribute::make(
            get: fn () => $operation_name
        );   

    }
    public function createdAt():Attribute
    {
        return Attribute::make(
            get: fn($value)=> $this->formatDate($value,'M d Y')
        );
    }
    //--------------------relationships----------------
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
