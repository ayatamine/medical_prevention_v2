<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function appLogo():Attribute
    {
        return Attribute::make(
            get: function ($value){
                if($value && Request::segment(1) != 'admin') {
                    return base_path().'/storage/app/public/'.$value;
                }
                return $value;
            }
        );
    }
    public function signatureImage():Attribute
    {
        return Attribute::make(
            get: function ($value){
                if($value && Request::segment(1) != 'admin') {
                    return base_path().'/storage/app/public/'.$value;
                }
                return $value;
            }
        );
    }
    
}
