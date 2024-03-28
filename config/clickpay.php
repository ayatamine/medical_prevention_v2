<?php

use App\Helpers\ClickPayHandler;

return [

    /*
     |--------------------------------------------------------------------------
     | Merchant profile id
     |--------------------------------------------------------------------------
     |
     | Your merchant profile id , you can find the profile id on your ClickPay Merchant’s Dashboard- profile.
     |
     */

    'profile_id' => env('CLICKPAY_PROFILE_ID', null),

    /*
   |--------------------------------------------------------------------------
   | Server Key
   |--------------------------------------------------------------------------
   |
   | You can find the Server key on your ClickPay Merchant’s Dashboard - Developers - Key management.
   |
   */

    'server_key' => env('CLICKPAY_SERVER_KEY', null),

    /*
   |--------------------------------------------------------------------------
   | Currency
   |--------------------------------------------------------------------------
   |
   | The currency you registered in with ClickPay account
     you must pass value from this array ['SAR','AED','EGP','OMR','JOD','US']
   |
   */

    'currency' => env('CLICKPAY_CURRENCY', null),
    'callback' => env('clickpay_ipn_callback', ClickPayHandler::class ),

];
