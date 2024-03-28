<?php


namespace App\Helpers;

use App\Models\Doctor;
use App\Models\Consultation;
use App\Notifications\DoctorReviewAdded;

class ClickPayHandler
{
    function updateCartByIPN( $requestData){
        $cartId= $requestData->getCartId();
        $status= $requestData->getStatus();
        // return response()->json($status);
        Consultation::create([
            'doctor_id' => 2,
            'patient_id' => 1,
            'status' => 'pending',
            'paymentintent_id'=> 'dfdfdf'
        ]);
        $data=array(
            'message'=>'A Review has been added to your consultation #',
            'consultation_id'=>1,
            'doctor'=>"sdf"
        );
        Doctor::find(1)->notify((new DoctorReviewAdded($data)));
        //your logic .. updating cart in DB, notifying the customer ...etc
    }
}