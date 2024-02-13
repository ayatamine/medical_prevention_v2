<?php

namespace App\Http\Controllers\API\V1\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\Doctor;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Repositories\DoctorRepository;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\DoctorProfileDataResource;
use App\Http\Resources\DoctorHomeProfileDataResource;
use App\Http\Resources\SimpleNotificationResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DoctorController extends Controller
{
    protected $repository;

    /**
     * @var DoctorRepository
     * @var ApiResponse
     */
    public function __construct(DoctorRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/register",
     * operationId="register_doctor",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="applying for register a new doctor using the form",
     * description="register a new doctor",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property( property="full_name",type="string",example="doctor full Name"),
     *                 @OA\Property( property="id_number",type="integer",example="46126"),
     *                 @OA\Property( property="birth_date",type="string",example="doctor birth_date"),
     *                 @OA\Property( property="email",type="string",example="email@email.com"),
     *                 @OA\Property( property="job_title",type="string",example="chronic disease"),
     *                 @OA\Property( property="speciality_id",type="integer"),
     *                 @OA\Property( property="sub_specialities",type="array",@OA\Items(type="integer"), example={1,2}),
     *                 @OA\Property( property="classification_number",type="string",example="784899554"),
     *                 @OA\Property( property="insurance_number",type="string",example="78489966554"),
     *                 @OA\Property( property="medical_licence_file",type="file"),
     *                 @OA\Property( property="cv_file",type="file"),
     *                 @OA\Property( property="certification_file",type="file"),
     *                 @OA\Property( property="bio",description="doctor bio",type="text"),
     *                 @OA\Property( property="thumbnail",type="file"),
     *             )),
     *    ),
     *    @OA\Response( response=200, description="Record Completed successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="no result found with the given token or phone number, please login again", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *     )
     */

    public function store(StoreDoctorRequest $request)
    {


        try {
            $doctor = $this->repository->store($request->validated());

            return $this->api->success()
                ->message('Doctor Record Completed successfully')
                ->payload([
                    'doctor_id' => $doctor->id
                ])
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()->code(500)
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/doctors/{id}/check-status",
     * operationId="check_doctor_apply_request_status",
     * tags={"doctors"},
     * summary="check doctor apply request status if accepted ",
     * description="check doctor apply request status if accepted  ",
     *      @OA\Parameter(  name="id", in="path", description="Doctor id ", required=true),
     *      @OA\Response( response=200, description="request status ['pending','accepted','blocked']", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no doctor found with this id", @OA\JsonContent() ),
     *    )
     */
    public function checkStatus($id)
    {
        $payload = [];
        try {
            $doctor = $this->repository->findById($id);

            $payload['request_status'] = $doctor['account_status'];

            //generate token to save
            //  if($doctor['account_status'] == 'accepted')
            //  {
            //      $payload['token'] =  $doctor->createToken('mobile', ['role:doctor','doctor:update'])->plainTextToken;
            //  }
            return $this->api->success()
                ->message('Request Status Checked')
                ->payload($payload)
                ->send();
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return $this->api->failed()->code(404)
                    ->message("no doctor found with the given id")
                    ->send();
            }
            return $this->api->failed()->code(500)
                ->message($ex->getMessage())
                ->send();
        }
    }

    /**
     * @OA\Post(
     * path="/api/v1/doctors/otp/send",
     * operationId="sendDoctorOtp",
     * tags={"doctors"},
     * summary="send doctor otp to phone number",
     * description="send doctor otp via to phone number via sms example +213684759496",
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
                'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/',
            ]);
            if($request->phone_number ==env('DEFAULT_PHONE_NUMBER'))
            {
                return response()->json([
                    "success"=>true,
                    'message'=>"The OTP has been sent successfully",
                    'new_registered' => true,
                    'id'=> Doctor::wherePhoneNumber($request->phone_number)->first()->id
                ]);
            }
            $doctor = Doctor::whereDeletedAt(null)
                ->where('phone_number', $request->phone_number)
                ->where('account_status', "blocked")
                ->first();
            if ($doctor) {
                return $this->api->failed()->code(401)
                    ->message("Your account was suspended ,please contact the support team")
                    ->send();
            }

            $otp = generate_otp($request->phone_number, 'Doctor');
            return sendSMS($request->phone_number, 'Your OTP Verification code is ', $otp, 'Doctor');
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return $this->api->failed()->code(404)
                    ->message("no doctor found with the given phone number or Account is not active")
                    ->send();
            }
            return $this->api->failed()->code(500)
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/otp/auth/send",
     * operationId="sendOtpToAuth",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="send otp to authenticated doctor via phone number",
     * description="send otp to authenticated doctor via phone number example +213684759496",
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
    public function sendToAuth(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/',
            ]);
            if($request->phone_number ==env('DEFAULT_PHONE_NUMBER'))
            {
                return response()->json([
                    "success"=>true,
                    'message'=>"The OTP has been sent successfully",
                    'new_registered' => true,
                    'id'=> Doctor::wherePhoneNumber($request->phone_number)->first()->id
                ]);
            }
            $doctor = request()->user();

            if ($doctor && $doctor->phone_number == $request->phone_number) {
                return $this->api->failed()->code(400)
                    ->message("Same phone number provided")
                    ->send();
            }

            $otp = generate_otp($request->phone_number, 'Doctor',$doctor);

            return sendSMS($request->phone_number, 'Your OTP Verification code is ', $otp, 'Doctor');
        } catch (Exception $ex) {
            if ($ex instanceof ModelNotFoundException) {
                return $this->api->failed()->code(404)
                    ->message("no doctor found with the given phone number or Account is not active")
                    ->send();
            }
            return $this->api->failed()->code(500)
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/otp/verify",
     * operationId="loginDoctorWithOtp",
     * tags={"doctors"},
     * summary="verify doctor otp code if match to login",
     * description="verify doctor otp code if match to login using the phone_number and the otp",
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
                // $default_doctor = Doctor::wherePhoneNumber($request->phone_number)->first()->id;
                $default_doctor = Doctor::wherePhoneNumber($request->phone_number)->first();

                return $this->api->success()
                ->message('The verification passed successfully')
                ->payload([
                    'token' => $default_doctor->createToken('mobile', ['role:doctor', 'doctor:update'])->plainTextToken,
                    'doctor_id' => $default_doctor->id,
                    'new_registered'=>true
                ])
                ->send();
            }
            $doctor  = $this->repository
                ->findByOtpAndPhone($request->phone_number, $request->otp);

            $now = now();
            // if (!$doctor) {
            //     return $this->api->failed()->code(422)
            //         ->message('Your OTP Or Phone Number is not correct')
            //         ->send();
            // }
            if ($doctor && $now->isAfter($doctor->otp_expire_at)) {
                return $this->api->failed()->code(419)
                    ->message('Your OTP has been expired')
                    ->send();
            }




            //validate the otp
            $doctor->otp_verification_code =  null;
            $doctor->otp_expire_at =  now();
            $doctor->is_phone_verified =  true;
            //authenticated  login
            if(request()->user()) $doctor->phone_number =$request->phone_number;
            $doctor->save();

            if (request()->user()) {
                return $this->api->success()
                    ->message('The verification passed successfully')
                    ->send();
            }
            $is_new = false;
            if (!$doctor->id_number || !$doctor->job_title || !$doctor->insurance_number) {
                $is_new = true;
            }
            return $this->api->success()
                ->message('The verification passed successfully')
                ->payload([
                    'token' => $doctor->createToken('mobile', ['role:doctor', 'doctor:update'])->plainTextToken,
                    'id' => $doctor->id,
                    'new_regitered' => $is_new
                ])
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No doctor Found with the given phone number");
        }
    }
        /**
     * @OA\Post(
     * path="/api/v1/doctors/otp/auth/verify",
     * operationId="verifyOtpOfAuth",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="verify doctor otp code if match to login",
     * description="verify doctor otp code if match to login using the phone_number and the otp",
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
    public function verifyOtpAuth(Request $request)
    {
        /* Validation */
        $request->validate([
            'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/',
            'otp' => 'required'
        ]);

        try {
            if($request->phone_number ==env('DEFAULT_PHONE_NUMBER')){
                $default_doctor = Doctor::wherePhoneNumber($request->phone_number)->first()->id;
                return $this->api->success()
                ->message('The verification passed successfully')
                ->payload([
                    'token' => $default_doctor->createToken('mobile', ['role:doctor', 'doctor:update'])->plainTextToken,
                    'doctor_id' => $default_doctor->id,
                    'new_registered'=>true
                ])
                ->send();
            }
            $doctor =request()->user();
            if($doctor && $doctor->otp_verification_code != $request->otp){
                abort(422,"Your OTP is not correct, Please Verify");
            }

            $now = now();
            // if (!$doctor) {
            //     return $this->api->failed()->code(422)
            //         ->message('Your OTP Or Phone Number is not correct')
            //         ->send();
            // }
            if ($doctor && $now->isAfter($doctor->otp_expire_at)) {
                return $this->api->failed()->code(419)
                    ->message('Your OTP has been expired')
                    ->send();
            }




            //validate the otp
            $doctor->otp_verification_code =  null;
            $doctor->otp_expire_at =  now();
            $doctor->is_phone_verified =  true;
            //authenticated  login
            if(request()->user()) $doctor->phone_number =$request->phone_number;
            $doctor->save();

            if (request()->user()) {
                return $this->api->success()
                    ->message('The verification passed successfully')
                    ->send();
            }

        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No doctor Found with the given phone number");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/doctors/profile/home",
     * operationId="getHomeProfileData",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="get profile home data of a doctor ",
     * description="return profile home data of a doctor ",
     *      @OA\Response( response=200, description="home profile data fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated ", @OA\JsonContent() ),
     *    )
     */

    public function getHomeProfileData()
    {

        try {

            return $this->api->success()
                ->message('Home Profile Data  fetched successfully')
                ->payload(new DoctorHomeProfileDataResource(request()->user()))
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "no doctor found with the given id");
        }
    }
    // /**
    //  * @OA\Get(
    //  * path="/api/v1/doctors/profile/main",
    //  * operationId="profileDetails",
    //  * tags={"doctors"},
    //  * security={ {"sanctum": {} }},
    //  * summary="get main profile data of a doctor ",
    //  * description="get main profile data of a doctor",
    //  *      @OA\Response( response=200, description="profile fetched successfully", @OA\JsonContent() ),
    //  *      @OA\Response( response=401, description="unauthenticated ", @OA\JsonContent() ),
    //  *    )
    //  */

    // public function profileDetails()
    // {

    //     try {

    //         return $this->api->success()
    //             ->message('Profile fetched successfully')
    //             ->payload(new DoctorProfileDataResource(request()->user()))
    //             ->send();
    //     } catch (Exception $ex) {
    //         return handleTwoCommunErrors($ex, "no doctor found with the given id");
    //     }
    // }
    /**
     * @OA\Get(
     * path="/api/v1/doctors/profile/my",
     * operationId="show_doctor_details",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="get main doctor details",
     * description="get main doctor details",
     *      @OA\Response( response=200, description="profile fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated ", @OA\JsonContent() ),
     *    )
     */

    public function show()
    {

        try {

            return $this->api->success()
                ->message('Profile fetched successfully')
                ->payload(new DoctorResource(request()->user()))
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "no doctor found with the given id");
        }
    }

    /**
     * @OA\Post(
     * path="/api/v1/doctors/update-thumbnail",
     * operationId="updateDoctorThumbnail",
     * security={ {"sanctum": {} }},
     * tags={"doctors"},
     * summary="update doctor thumbnail or profile photo",
     * description="update doctor thumbnail(profile photo)",
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
     *    @OA\Response( response=404,description="No Doctor Found with this request", @OA\JsonContent()),
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
            $docotr = $this->repository->updateThumbnail($request->only('thumbnail'));

            return $this->api->success()
                ->message('Thumbnail updated successfully')
                ->payload(['thumbnail' => $docotr->thumbnail])
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Doctor Found with this request please verify your login");
        }
    }
    /**
     * @OA\post(
     * path="/api/v1/doctors/logout",
     * operationId="logoutDoctor",
     * security={ {"sanctum": {} }},
     * tags={"doctors"},
     * description="Logout a Doctor",
     *    @OA\Response( response=200, description="You logged out successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Doctor Found please verify your login", @OA\JsonContent()),
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
            return handleTwoCommunErrors($ex, "No Doctor Found please verify your login");
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/profile/update",
     * operationId="update doctor profile",
     * security={ {"sanctum": {} }},
     * tags={"doctors"},
     * summary="update docotr profile ",
     * description="update docotr profile  ",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property( property="full_name",type="string",example="doctor full Name"),
     *                 @OA\Property( property="id_number",type="integer",example="46126"),
     *                 @OA\Property( property="birth_date",type="string",example="doctor birth_date"),
     *                 @OA\Property( property="email",type="string",example=""),
     *                 @OA\Property( property="job_title",type="string",example="chronic disease"),
     *                 @OA\Property( property="speciality_id",type="integer"),
     *                 @OA\Property( property="sub_specialities",type="array",@OA\Items(type="integer"), example={1,2}),
     *                 @OA\Property( property="classification_number",type="string",example="784899554"),
     *                 @OA\Property( property="insurance_number",type="string",example="78489966554"),
     *                 @OA\Property( property="medical_licence_file",type="file"),
     *                 @OA\Property( property="cv_file",type="file"),
     *                 @OA\Property( property="certification_file",type="file"),
     *                 @OA\Property( property="location",description="json location when picked from map",type="json"),
     *                 @OA\Property( property="bio",description="doctor bio",type="text"),
     *             ),
     *       )
     *    ),
     *    @OA\Response( response=200, description="profile updated Successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Doctor Found please verify your login", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function updateProfile(UpdateDoctorRequest $request)
    {
        try {
            $doctor = $this->repository->updateProfile($request->validated());

            return $this->api->success()
                ->message('Profile updated successfully')
                ->payload($doctor)
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Doctor Found please verify your login");
        }
    }
    /**
     * @OA\Put(
     * path="/api/v1/doctors/profile/update-phone-number",
     * operationId="updateDoctorPhone",
     * security={ {"sanctum": {} }},
     * tags={"doctors"},
     * summary="update doctor phone number",
     * description="update doctor phone_number ",
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
     *    @OA\Response( response=404,description="No Doctor Found please verify your login", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/|unique:doctors,phone_number,' . request()->user()->id
        ]);

        try {
            $doctor = $this->repository
                ->updatePhone($request);

            return $this->api->success()
                ->message('Phone number updated successfully')
                ->payload([
                    'id' => $doctor->id,
                    'phone_number' => $doctor->phone_number
                ])
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Doctor Found please verify your login");
        }
    }
    /**
     * @OA\Delete(
     * path="/api/v1/doctors",
     * operationId="deleteDoctorAccount",
     * security={ {"sanctum": {} }},
     * tags={"doctors"},
     * summary="delete doctor account",
     * description="delete doctor account ",
     *    @OA\Response( response=200, description="Account deleted Successfully", @OA\JsonContent() ),
     *    @OA\Response( response=404,description="No Doctor Found please verify your login", @OA\JsonContent()),
     *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
     *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
     *    )
     */
    public function deleteAccount()
    {
        try {
            $this->logout();
            $this->repository->delete(request()->user());

            return $this->api->success()
                ->message('Account deleted successfully')
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No Doctor Found please verify your login");
        }
    }
    /**
     * @OA\Get(
     * path="/api/v1/doctors/profile/notifications",
     * operationId="get doctor notifications",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="get list of notifications related to a given doctor ",
     * description="get list of notifications related to a given doctor  ",
     *      @OA\Response( response=200, description="notification fetched successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no doctor found with this id", @OA\JsonContent() ),
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
            handleTwoCommunErrors($ex, 'no doctor found ,please verify your login');
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/profile/notifications/mark-as-read",
     * operationId="mark_as_read_doctor_noti",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="mark as read for doctor notifications ",
     * description="mark as read for doctor notifications  ",
     *      @OA\Response( response=200, description="notification marked as read successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no doctor found with this id", @OA\JsonContent() ),
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
            handleTwoCommunErrors($ex, 'no doctor found ,please verify your login');
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/profile/notifications/{id}/mark-as-read",
     * operationId="mark_as_read_doctor_single_noti",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="mark as read for doctor given id notifications ",
     * description="mark as read for doctor  given id  notifications  ",
     * @OA\Parameter(  name="id", in="path", description="Notification id ", required=true),
     *      @OA\Response( response=200, description="notification marked as read successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no doctor found with this id", @OA\JsonContent() ),
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
            handleTwoCommunErrors($ex, 'no doctor found ,please verify your login');
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/doctors/withdraw-ballance",
     * operationId="withdraw-doctor-ballance",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="withdraw-doctor-ballance",
     * description="withdraw-doctor-ballance",
     *      @OA\Response( response=200, description="ballance Withdrew successfully", @OA\JsonContent() ),
     *      @OA\Response( response=404, description="no doctor found with this id", @OA\JsonContent() ),
     *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
     *    )
     */
    public function withdrawBallance()
    {
    }
}
