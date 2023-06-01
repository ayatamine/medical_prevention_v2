<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['as' => 'api_v1.'], function () {
    // Route::get('/chronic-diseases', [\App\Http\Controllers\API\V1\ChronicDiseasesController::class,'index']);
    // Route::get('/family-histories', [\App\Http\Controllers\API\V1\FamilyHistoryController::class,'index']);
    // Route::post('/patients/store', [\App\Http\Controllers\API\V1\ChronicDiseasesController::class,'store']);
    // Route::get('/ads', [\App\Http\Controllers\API\V1\AdsController::class,'index']);
    // Route::get('/medical-instructions', [\App\Http\Controllers\API\V1\MedicalInstructionController::class,'index']);
    Route::get('/specialities', [\App\Http\Controllers\API\V1\SpecialityController::class,'index']);
    Route::get('/specialities/{id}', [\App\Http\Controllers\API\V1\SpecialityController::class,'show']);
   
    Route::get('/pages/{title}', [\App\Http\Controllers\API\V1\PageController::class,'getPage']);

    Route::controller(\App\Http\Controllers\API\V1\Auth\PatientController::class)->prefix('patients')->group(function(){
        Route::post('otp/send', 'sendOtp');
        Route::post('otp/verify', 'loginWithOtp');
        Route::group(['middleware'=>'auth:sanctum'],function(){
            Route::post('complete-medical-record', 'storePatientData');
            Route::put('/{id}/update-phone-number', 'updatePhone');
            Route::post('/{id}/update-thumbnail', 'updateThumbnail');
            Route::delete('/{id}', 'deletePatientAccount');
            Route::post('/{id}/logout', 'logout');
            Route::put('/{id}/notifications-status/{status}', 'switchNotificationsStataus');

            //patient scales
            Route::get('/{id}/scales', 'getPatientScales');
            // recommendation with age and sex filtered base on the patient
            Route::get('/recommendations','recommendations');
            Route::get('/recommendations/{id}','recommendationDetails');
        });
    });
    // Route::controller(\App\Http\Controllers\API\V1\ScaleController::class)->prefix('scales')->group(function(){
    //     Route::get('', 'index');
    // });

    // doctor controller
    Route::controller(\App\Http\Controllers\API\V1\Auth\DoctorController::class)->prefix('doctors')->group(function(){
        //------------------------Auth-----------------------
        Route::post('register','store');
        Route::get('{id}/check-status','checkStatus');
        Route::post('otp/send', 'sendOtp');
        Route::post('otp/verify', 'loginWithOtp');
        //------------------------Auth-----------------------
        Route::group(['middleware'=>'auth:sanctum'],function(){
            Route::get('home-profile-data', 'getHomeProfileData');
            Route::get('my-profile', 'profileDetails');
            Route::post('logout', 'logout');
            Route::put('profile/update', 'updateProfile');
            Route::put('profile/update-phone-number', 'updatePhone');
            Route::delete('delete-account', 'deleteAccount');

            // Route::put('/{id}/update-phone-number', 'updatePhone');
            // Route::post('/{id}/update-thumbnail', 'updateThumbnail');
            // Route::delete('/{id}', 'deletePatientAccount');
            // Route::post('/{id}/logout', 'logout');
            // Route::put('/{id}/notifications-status/{status}', 'switchNotificationsStataus');

            // //patient scales
            // Route::get('/{id}/scales', 'getPatientScales');
            // // recommendation with age and sex filtered base on the patient
            // Route::get('/recommendations','recommendations');
            // Route::get('/recommendations/{id}','recommendationDetails');
        });
    });
    
});
//'auth:sanctum', 'type.customer'
Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::get('my-wallet', [\App\Http\Controllers\API\V1\BallanceController::class,'ballance_history'])->prefix('doctors');
    Route::controller(\App\Http\Controllers\API\V1\ConsultationController::class)->prefix('consultations')->group(function(){
            Route::post('/{id}/approve', 'approveConsult');
            Route::post('/{id}/reject', 'rejectConsult');
    });
    //profile controller
    Route::controller(\App\Http\Controllers\API\V1\DoctorProfileController::class)->prefix('doctors')->group(function(){
        Route::put('online-status/{status}', 'switchOnlineStatus');
        Route::put('notification-status/{status}', 'switchNotificationStatus');
    });
});