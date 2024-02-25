<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
require base_path('routes/channels.php');

Route::group(['as' => 'api_v1.'], function () {
    Route::get('/chronic-diseases', [\App\Http\Controllers\API\V1\ChronicDiseasesController::class, 'index']);
    Route::get('/allergies', [\App\Http\Controllers\API\V1\AllergyController::class, 'index']);
    Route::get('/lab-tests', [\App\Http\Controllers\API\V1\PrescriptionController::class, 'labTests']);
    Route::get('/symptomes', [\App\Http\Controllers\API\V1\SymptomeController::class, 'index']);
    Route::get('/family-histories', [\App\Http\Controllers\API\V1\FamilyHistoryController::class, 'index']);
    Route::get('/advertisements', [\App\Http\Controllers\API\V1\AdvertisementsController::class, 'index']);
    Route::get('/medical-instructions', [\App\Http\Controllers\API\V1\MedicalInstructionController::class, 'index']);
    Route::get('/specialities', [\App\Http\Controllers\API\V1\SpecialityController::class, 'index']);
    Route::get('/specialities/{id}', [\App\Http\Controllers\API\V1\SpecialityController::class, 'show']);
    Route::get('/specialities/{id}/doctors', [\App\Http\Controllers\API\V1\SpecialityController::class, 'doctors'])->name('speciality_doctors');
    Route::get('/sub_specialities', [\App\Http\Controllers\API\V1\SpecialityController::class, 'subIndex']);
    Route::get('/sub_specialities/{id}', [\App\Http\Controllers\API\V1\SpecialityController::class, 'getSubSpecialityDetails']);
    Route::get('/sub_specialities/{id}/doctors', [\App\Http\Controllers\API\V1\SpecialityController::class, 'SubDoctors'])->name('sub_speciality_doctors');

    Route::get('support/subject-types', [\App\Http\Controllers\API\V1\SupportRequestController::class,'subjectTypes']);

    Route::apiResource('doctors', \App\Http\Controllers\API\V1\DoctorController::class);
    Route::post('/symptomes/doctors', [\App\Http\Controllers\API\V1\DoctorController::class, 'doctorsBySymptomes'])->name('symptomes_doctors');
    Route::group(['middleware' => ['auth:sanctum','auth.patient']], function () {
        Route::post('/doctors/{id}/add-to-favorites', [\App\Http\Controllers\API\V1\DoctorController::class, 'addToFavorites']);
        Route::delete('/doctors/{id}/remove-from-favorites', [\App\Http\Controllers\API\V1\DoctorController::class, 'removeFromFavorites']);
        Route::post('/consultations/payment/create', [\App\Http\Controllers\API\V1\ConsultationController::class, 'pay']);
        Route::post('/consultations/payment/verify', [\App\Http\Controllers\API\V1\ConsultationController::class, 'paymentVerify']);
        Route::post('/consultations/{id}/reviews', [\App\Http\Controllers\API\V1\ConsultationController::class, 'addReview']);
        Route::get('/consultations/{id}', [\App\Http\Controllers\API\V1\ConsultationController::class, 'show']);
    });
    Route::get('/pages/{title}', [\App\Http\Controllers\API\V1\PageController::class, 'getPage']);

    Route::controller(\App\Http\Controllers\API\V1\Auth\PatientController::class)->prefix('patients')->group(function () {
        Route::post('otp/send', 'sendOtp');
        Route::post('otp/verify', 'loginWithOtp');
        Route::group(['middleware' => ['auth:sanctum','auth.patient']], function () {
            Route::post('otp/auth/send', 'sendToAuth');
            Route::get('/profile/my', 'show');
            Route::post('complete-medical-record', 'storePatientData');
            Route::put('/update-phone-number', 'updatePhone');
            Route::post('/update-thumbnail', 'updateThumbnail');
            Route::delete('/', 'deletePatientAccount');
            Route::post('/logout', 'logout');
            Route::put('/notifications-status/{status}', 'switchNotificationsStataus');
            Route::get('/notifications', 'myNotifications');
            Route::post('/notifications/mark-as-read', 'markNotificationsAsRead');
            Route::post('/notifications/{id}/mark-as-read', 'markSingleNotificationsAsRead');

            //patient scales
            Route::get('scales', 'getPatientScales');
            Route::get('scales/{title}', 'patientScaleDetails');
            Route::post('scales/{title}', 'updatePatientScale');
            // recommendation with age and sex filtered base on the patient
            Route::get('/recommendations', 'recommendations');
            Route::get('/recommendations/{id}', 'recommendationDetails');
            //medical record
            Route::get('/medical-record/', 'getMedicalRecord');
            Route::get('/favorite-doctors/', 'getFavoriteDoctors');
            Route::get('/summaries/', 'previousSummaries');
        });
    });
    // Route::controller(\App\Http\Controllers\API\V1\ScaleController::class)->prefix('scales')->group(function(){
    //     Route::get('', 'index');
    // });

    // doctor controller
    Route::controller(\App\Http\Controllers\API\V1\Auth\DoctorController::class)->prefix('doctors')->group(function () {
        //------------------------Auth-----------------------

        Route::get('{id}/check-status', 'checkStatus');
        Route::post('otp/send', 'sendOtp');
        Route::post('otp/verify', 'loginWithOtp');
        Route::post('register', 'store');

        //------------------------Auth-----------------------
        Route::group(['middleware' => ['auth:sanctum', 'auth.doctor']], function () {
            Route::post('otp/auth/send', 'sendToAuth');
            Route::post('otp/auth/verify', 'verifyOtpAuth');
            Route::get('/profile/my', 'show');
            Route::get('/profile/notifications', 'myNotifications');
            Route::post('/profile/notifications/mark-as-read', 'markNotificationsAsRead');
            Route::post('/profile/notifications/{id}/mark-as-read', 'markSingleNotificationsAsRead');
            Route::get('/profile/home', 'getHomeProfileData');
            // Route::get('/profile/main', 'profileDetails');
            Route::post('logout', 'logout');
            Route::post('profile/update', 'updateProfile');
            Route::put('profile/update-phone-number', 'updatePhone');
            Route::delete('/','deleteAccount');
            Route::post('/update-thumbnail', 'updateThumbnail');
            Route::post('/withdraw-ballance', 'withdrawBallance');



        });
    });
});
//'auth:sanctum', 'type.customer'
Route::group(['middleware' => ['auth:sanctum', 'auth.doctor']], function () {
    Route::get('/doctors/my-wallet/history', [\App\Http\Controllers\API\V1\BallanceController::class, 'ballance_history']);
    Route::controller(\App\Http\Controllers\API\V1\ConsultationController::class)->prefix('consultations')->group(function () {
        Route::get('/', 'index');
        Route::post('/{id}/approve', 'approveConsult');
        Route::post('/{id}/reject', 'rejectConsult');
        Route::post('/{id}/finish', 'finishConsult');
        Route::post('/{id}/add-summary', 'addSummary');
    });
    //profile controller
    Route::controller(\App\Http\Controllers\API\V1\DoctorProfileController::class)->prefix('doctors')->group(function () {
        Route::put('online-status/{status}', 'switchOnlineStatus');
        Route::put('notification-status/{status}', 'switchNotificationStatus');
        Route::put('profile/update-last-online-status', 'updateLastOnlineStatus');

    });
    //Prescripiton controller
    Route::controller(\App\Http\Controllers\API\V1\PrescriptionController::class)->group(function () {
        //prescripitons
        // Route::get('/my-medicines', 'index');
        Route::post('/doctors/medicines/store', 'store');
        Route::get('/doctors/medicines/list', 'searchMedicines');
        Route::get('/medicines/list', 'searchMedicinesList');

    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('consultations/{id}/chat-messages/', [\App\Http\Controllers\API\V1\ConsultationController::class,'getConsultationChat']);
    Route::get('consultations/{id}/medical-record/', [\App\Http\Controllers\API\V1\ConsultationController::class,'patientMedicalRecord']);
    Route::post('consultations/{id}/send-message', [\App\Http\Controllers\API\V1\ConsultationController::class,'sendMessage']);
    Route::get('consultations/{id}/view-summary', [\App\Http\Controllers\API\V1\ConsultationController::class,'viewSummary']);
    Route::get('consultations/{id}/print-summary', [\App\Http\Controllers\API\V1\ConsultationController::class,'printSummary']);

    Route::post('support/contact', [\App\Http\Controllers\API\V1\SupportRequestController::class,'saveRequest']);
});
