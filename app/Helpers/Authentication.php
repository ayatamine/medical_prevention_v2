<?php

namespace App\Helpers;

use App\Models\Doctor;
use App\Models\Patient;

if (!function_exists('generate_otp')) {
    function generate_otp($phone_number,$model)
    {
       
        if($model == 'Doctor'){
            $model_record = Doctor::where('phone_number', $phone_number)->firstOrfail();
        }else{
            $model_record = Patient::where('phone_number', $phone_number)->firstOrCreate(['phone_number'=>$phone_number],array(['phone_number'=>$phone_number]));
        }
  
        $now = now();
        if($model_record && isset($model_record->otp_verification_code) && $model_record->otp_expire_at && $now->isBefore($model_record->otp_expire_at)){
            return $model_record->otp_verification_code;
        }
        
        $model_record->otp_verification_code =  rand(12345, 99999);
        $model_record->otp_expire_at =  $now->addMinutes(10);
        $model_record->save();
        
        return $model_record->otp_verification_code;

    }
}
if(!function_exists('send_sms')){

    function sendSMS($receiverNumber,$message,$content)
    {
        $message = $message.' '.$content;
    
        try {
  
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
  
            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create($receiverNumber, [
            //     'from' => $twilio_number, 
            //     'body' => $message]);
   
            return response()->json([
                "success"=>true,
                'message'=>'The OTP send Successfully',
            ],200);
    
        } catch (Exception $e) {
            return response()->json([
                "success"=>false,
                'message'=>$e->getMessage(),
            ],500);
        }
    }
}