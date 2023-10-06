<?php

namespace App\Http\Controllers\API\V1\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\Patient;
use App\Helpers\ApiResponse;
use App\Models\PatientScale;
use Illuminate\Http\Request;
use App\Models\Recommendation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScaleResource;
use App\Repositories\PatientRepository;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\PatientRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Resources\PatientScaleResource;
use App\Http\Resources\PatientProfileResource;
use App\Http\Resources\RecommendationResource;
use App\Http\Resources\SimpleNotificationResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PatientController extends Controller
{
    protected $repository;

    /**
     * @var PatientRepository
     * @var ApiResponse
     */

    public function __construct(PatientRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/otp/send",
     * operationId="sendOtp",
     * tags={"patients"},
     * summary="send patient otp to phone number",
     * description="send patient otp via to phone number via sms example +213684759496",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="phone_number",type="string",example="+213664419425"),
     *             )),
     *    ),
     *      @OA\Response(response=200,description="The otp sended successfully",@OA\JsonContent()),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent())
     *     )
     */
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/'
            ]);
            if($request->phone_number ==env('DEFAULT_PHONE_NUMBER')) 
            {
                return response()->json([
                    "success"=>true,
                    'message'=>"The OTP has been sent successfully",
                    'new_registered' => false,
                    'id'=> Patient::wherePhoneNumber($request->phone_number)->first()->id
                ]);
            }
            $otp = generate_otp($request->phone_number, 'Patient');
            return sendSMS($request->phone_number, 'Your OTP Verification code is ', $otp, 'Patient');
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return $this->api->failed()->code(404)
                    ->message("no patient found with the given phone number")
                    ->send();
            }
            return $this->api->failed()->code(500)
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/otp/verify",
     * operationId="loginWithOtp",
     * tags={"patients"},
     * summary="verify patient otp code if match to login",
     * description="verify patient otp code if match to login using the phone_number and the otp",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="phone_number",type="string",example="+213664419425"),
     *                 @OA\Property(property="otp",type="string",example="55555")
     *             ) ),
     *    ),
     *      @OA\Response( response=200,description="The verification passed successfully",@OA\JsonContent()),
     *      @OA\Response( response=422,description="Your OTP Or Phone Number is not correct",@OA\JsonContent()),
     *      @OA\Response( response=419,description="Your OTP has been expired",@OA\JsonContent()),
     *      @OA\Response(response=500,description="internal server error",@OA\JsonContent())
     *     )
     */
    public function loginWithOtp(Request $request)
    {
        /* Validation */
        $request->validate([
            'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/',
            'otp' => 'required'
        ]);
        try {
            if($request->phone_number ==env('DEFAULT_PHONE_NUMBER')){
                $default_patient = Patient::wherePhoneNumber($request->phone_number)->first()->id;
                return $this->api->success()
                ->message('The verification passed successfully')
                ->payload([
                    'token' => $default_patient->createToken('mobile', ['role:patient', 'patient:update'])->plainTextToken,
                    'patient_id' => $default_patient->id,
                    'new_registered'=>false
                ])
                ->send();
            }
            $patient  = $this->repository
                ->findByOtpAndPhone($request->phone_number, $request->otp);

            $now = now();
            // if (!$patient) {
            //     return $this->api->failed()->code(422)
            //         ->message('Your OTP Or Phone Number is not correct')
            //         ->send();
            // } 
            if ($patient && $now->isAfter($patient->otp_expire_at)) {
                return $this->api->failed()->code(419)
                    ->message('Your OTP has been expired')
                    ->send();
            }



            //validate the otp
            $patient->otp_verification_code =  null;
            $patient->otp_expire_at =  now();
            $patient->save();
            $is_new = false;
            if (!$patient->birth_date || !$patient->height || !$patient->weight) {
                $is_new = true;
            }
            return $this->api->success()
                ->message('The verification passed successfully')
                ->payload([
                    'token' => $patient->createToken('mobile', ['role:patient', 'patient:update'])->plainTextToken,
                    'patient_id' => $patient->id,
                    'new_registered'=>$is_new
                ])
                ->send();
        } catch (Exception $ex) {

            return handleTwoCommunErrors($ex, "No patient Found with the given phone number");
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/complete-medical-record",
     * operationId="storePatientData",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * summary="update or complete patient medical record",
     * description="update or complete patient medical record ",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property( property="full_name",type="string",example="ahmed amine"),
     *                 @OA\Property( property="birth_date",type="string",example="25-05-1995"),
     *                 @OA\Property( property="age",type="integer",example=28),
     *                 @OA\Property( property="gender",type="string",example="male"),
     *                 @OA\Property( property="height",type="integer",example=180),
     *                 @OA\Property( property="weight",type="double",example="55.5"),
     *                 @OA\Property( property="allergies",type="array",@OA\Items(type="integer"), example={1}),
     *                 @OA\Property( property="chronic_diseases",type="array",@OA\Items(type="integer"), example={1}),
     *                 @OA\Property( property="family_histories",type="array",@OA\Items(type="integer"), example={1}),
     *                 @OA\Property( property="has_cancer_screening",type="boolean",enum={0, 1}),
     *                 @OA\Property( property="has_depression_screening",type="boolean",enum={0, 1}),
     *                 @OA\Property( property="has_physical_activity",type="boolean",enum={0, 1}),
     *                 @OA\Property( property="little_interest_doing_things",type="boolean",enum={0, 1}),
     *                 @OA\Property( property="feeling_down_or_depressed",type="boolean",enum={0, 1}),
     *             )
     *        ),
     *    ),
     *    @OA\Response( response=200, description="Patient Record Completed Successfuly", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="Patient not found with the given token or phone number, please login again", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function storePatientData(CreatePatientRequest $request)
    {
        try {
            $patient  = $this->repository
                ->store($request);


            return $this->api->success()
                ->message('Patient Record Completed Successfuly')
                ->payload(['patient' => $patient])
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Patient Found in this request");
        }
    }
    /**
     * @OA\Put(
     * path="/api/v1/patients/update-phone-number",
     * operationId="updatePhone",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * summary="update patient phone number",
     * description="update patient phone_number ",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property( property="phone_number",type="string",example="+213648952765"),
     *        )
     *       )
     *    ),
     *    @OA\Response( response=200, description="Phone Updated Successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Patient Found with this request", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/'
        ]);



        try {
            $patient = $this->repository
                ->updatePhone($request->only('phone_number'));
            return $this->api->success()
                ->message('Phone number updated successfully')
                ->payload(['phone_number' => $patient->phone_number])
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Patient Found with this request please verify your login");
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/update-thumbnail",
     * operationId="updateThumbnail",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * summary="update patient thumbnail or profile photo",
     * description="update patient thumbnail(profile photo)",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property( property="thumbnail",type="file"),
     *        )
     *       )
     *    ),
     *    @OA\Response( response=200, description="Thumbnail Updated Successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Patient Found with this request", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),

     *    )
     */
    public function updateThumbnail(Request $request)
    {
        $request->validate([
            'thumbnail' => ['required', 'mimes:jpg,jpeg,png', 'max:3000'],
        ]);
        try {
            $patient = $this->repository->updateThumbnail($request->only('thumbnail'));

            return $this->api->success()
                ->message('Thumbnail updated successfully')
                ->payload(['thumbnail' => $patient->thumbnail])
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Patient Found with this request please verify your login");
        }
    }
    /**
     * @OA\Delete(
     * path="/api/v1/patients",
     * operationId="deletePatientAccount",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * summary="delete patient account",
     * description="delete patient account",
     *    @OA\Response( response=200, description="You account was deleted successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Patient Found with this request", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function deletePatientAccount(Request $request)
    {

        try {
            $this->logout();
            $this->repository->delete(request()->user());

            return $this->api->success()
                ->message('Account deleted successfully')
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Patient Found please verify your login");
        }
    }
    /**
     * @OA\post(
     * path="/api/v1/patients/logout",
     * operationId="logout",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * description="Logout a patient",
     *    @OA\Response( response=200, description="You logged out successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Patient Found with this request", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function logout()
    {
        try {
            request()->user()->tokens()->delete();

            return $this->api->success()
                ->message('You logged out successfully')
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Patient Found please verify your login");
        }
    }
    /**
     * @OA\Put(
     * path="/api/v1/patients/notifications-status/{status}",
     * operationId="switchNotificationsStataus",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * description="change the notification status  on/off",
     *      @OA\Parameter( 
     *     @OA\Schema(
     *       default="1",
     *       type="integer",
     *       enum={
     *         0,
     *         1,
     *       },
     *     ),
     *     description="Notification Status.",
     *     example="0",
     *     in="path",
     *     name="status",
     *     required=true,
     * ),
     *      @OA\Response( response=200, description="notifications state switched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404,description="No Patient Found with this request", @OA\JsonContent()),
     *      @OA\Response( response=422,description="Please Provide a correct status format", @OA\JsonContent()),
     *    )
     */
    public function switchNotificationsStataus($status)
    {
        try {
            if (!in_array($status, [0, 1])) {
                return $this->api->failed()->code(422)
                    ->message('Please Provide a correct status format')
                    ->send();
            }

            $this->repository->switchNotification($status);

            return $this->api->success()
                ->message((int)$status == 0 ? 'notifications turned off successfully' : 'notifications turned On successfully')
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Patient Found please verify your login");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/patients/scales",
     * operationId="getPatientScales",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * description="get patient filled scales ",
     *      @OA\Response( response=200, description="scales fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function getPatientScales()
    {

        try {

            $scales = $this->repository->scalesList();



            return $this->api->success()
                ->message('patient scales fetched successfully')
                ->payload($scales)
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Record Found");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/patients/scales/{title}",
     * operationId="getPatientScaleDetails",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * description="get patient filled scale details ",
     *      @OA\Parameter( 
     *     @OA\Schema(
     *       default="anxiety",
     *       type="string",
     *       enum={
     *         "anxiety",
     *         "depression",
     *       },
     *     ),
     *     description="Scale title",
     *     example="anxiety",
     *     in="path",
     *     name="title",
     *     required=true,
     * ),
     *     @OA\Response( response=200, description="scale details fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function patientScaleDetails($title)
    {

        try {
            if ($title == 'anxiety') {
                //anxiety scale
                $scales = $this->repository->scaleDetails(1);
            } else {
                //depression scale
                $scales = $this->repository->scaleDetails(2);
            }



            return $this->api->success()
                ->message('patient scale details fetched successfully')
                ->payload($scales)
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Record Found");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/patients/recommendations",
     * operationId="recommendations",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * description="get patient recommendation based on his/her age and his/her gender",
     *      @OA\Response( response=200, description="recommendations fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function recommendations()
    {
        try {

            $recommendations = $this->repository->recommendationsList();

            return $this->api->success()
                ->message('recommendations fetched successfully')
                ->payload(RecommendationResource::collection($recommendations->get()))
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Record Found");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/patients/recommendations/{id}",
     * operationId="recommendationDetails",
     * security={ {"sanctum": {} }},
     * tags={"patients"},
     * description="get recommendation details by id",
     *      @OA\Parameter(  name="id", in="path", description="recommendation id ", required=true),
     *      @OA\Response( response=200, description="recommendation fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="No Record Found", @OA\JsonContent() ),
     *    )
     */
    public function recommendationDetails($id)
    {
        try {

            $recommendation = $this->repository->recommendationDetails($id);

            return $this->api->success()
                ->message('recommendation details fetched successfully')
                ->payload(new RecommendationResource($recommendation))
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Record Found");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/patients/notifications",
     * operationId="get patient notifications",
     * tags={"patients"},
     * security={ {"sanctum": {} }},
     * summary="get list of notifications related to a given patient ",
     * description="get list of notifications related to a given patient  ",
     *      @OA\Response( response=200, description="notification fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no patient found with this id", @OA\JsonContent() ),
     *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
     *    )
     */
    public function myNotifications()
    {
        try {
            $notifications = request()->user()->notifications;

            return $this->api->success()
                ->message('notification fetched successfully')
                ->payload(SimpleNotificationResource::collection($notifications))
                ->send();
        } catch (Exception $ex) {
            handleTwoCommunErrors($ex, 'no patient found ,please verify your login');
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/notifications/mark-as-read",
     * operationId="mark_as_read_patient_noti",
     * tags={"patients"},
     * security={ {"sanctum": {} }},
     * summary="mark as read for patient notifications ",
     * description="mark as read for patient notifications  ",
     *      @OA\Response( response=200, description="notification marked as read successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no patient found with this id", @OA\JsonContent() ),
     *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
     *    )
     */
    public function markNotificationsAsRead()
    {
        try {
            $notifications = request()->user()->unreadNotifications->markAsRead();
            return $this->api->success()
                ->message('notifications marked as read successfully')
                ->send();
        } catch (Exception $ex) {
            handleTwoCommunErrors($ex, 'no patient found ,please verify your login');
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/notifications/{id}/mark-as-read",
     * operationId="mark_as_read_patient_single_noti",
     * tags={"patients"},
     * security={ {"sanctum": {} }},
     * summary="mark as read for patient given id notifications ",
     * description="mark as read for patient  given id  notifications  ",
     * @OA\Parameter(  name="id", in="path", description="Notification id ", required=true),
     *      @OA\Response( response=200, description="notification marked as read successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no patient found with this id", @OA\JsonContent() ),
     *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
     *    )
     */
    public function markSingleNotificationsAsRead($id)
    {
        try {
            DB::table('notifications')->where('id', $id)->update(['read_at' => Carbon::now()]);

            return $this->api->success()
                ->message('notification marked as read successfully')
                ->send();
        } catch (Exception $ex) {
            handleTwoCommunErrors($ex, 'no patient found ,please verify your login');
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/patients/scales/{title}",
     * operationId="update patient scale",
     * tags={"patients"},
     * security={ {"sanctum": {} }},
     * summary="update patient scale (anexiety or depression) ",
     * description="update patient scale (anexiety or depression)  ",
     *      @OA\Parameter( 
     *     @OA\Schema(
     *       default="anxiety",
     *       type="string",
     *       enum={
     *         "anxiety",
     *         "depression",
     *       },
     *     ),
     *     description="Scale title",
     *     example="anxiety",
     *     in="path",
     *     name="title",
     *     required=true,
     * ),
     * @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *               @OA\Property( property="answers",type="array",@OA\Items(type="object")),
     *              )
     *          )
     * ),
     *      @OA\Response( response=200, description="scale updated successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no patient found with this id", @OA\JsonContent() ),
     *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
     *    )
     */
    public function updatePatientScale(Request $request, $title)
    {
        $this->validate($request, [
            'answers' => 'required|array',
            // 'answers.*' => 'exists:scale_questions,id'
        ]);
        $this->repository->updateScale($request, $title);
        try {
            return $this->api->success()
                ->message('scale updated successfully')
                ->send();
        } catch (Exception $ex) {
            handleTwoCommunErrors($ex, 'no patient found ,please verify your login');
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/patients/profile/my",
     * operationId="show_patient_details",
     * tags={"patients"},
     * security={ {"sanctum": {} }},
     * summary="get main patient details",
     * description="get main patient details",
     *      @OA\Response( response=200, description="profile fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated ", @OA\JsonContent() ),
     *    )
     */

     public function show()
     {
 
         try {
 
             return $this->api->success()
                 ->message('Profile fetched successfully')
                 ->payload(new PatientProfileResource(request()->user()))
                 ->send();
         } catch (Exception $ex) {
             return handleTwoCommunErrors($ex, "no doctor found with the given id");
         }
     }
}
