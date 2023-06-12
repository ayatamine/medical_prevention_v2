<?php

namespace App\Http\Controllers\API\V1\Auth;

use Exception;
use App\Models\Patient;
use App\Helpers\ApiResponse;
use App\Models\PatientScale;
use Illuminate\Http\Request;
use App\Models\Recommendation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScaleResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Api\PatientRequest;
use App\Http\Resources\PatientScaleResource;
use App\Http\Resources\RecommendationResource;

class PatientController extends Controller
{

    public function __construct(ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
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

            $request->validate([
                'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/'
            ]);
            $otp = generate_otp($request->phone_number,'Patient');
            return sendSMS($request->phone_number,'Your OTP code is ',$otp);
        
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
    
            /* Validation Logic */
            $patient   = Patient::where('phone_number', $request->phone_number)->where('otp_verification_code', $request->otp)->first();
    
            $now = now();
            if (!$patient) {
                return $this->api->failed()->code(422)
                        ->message('Your OTP Or Phone Number is not correct')
                        ->send();
            }else if($patient && $now->isAfter($patient->otp_expire_at)){
                return $this->api->failed()->code(419)
                            ->message('Your OTP has been expired')
                            ->send();
            }
            
            
            //validate the otp
            $patient->otp_verification_code =  null;
            $patient->otp_expire_at =  now();
            $patient->save();

            return $this->api->success()
                        ->message('Phone number updated successfully')
                        ->payload([
                            'token' => $patient->createToken('mobile', ['role:patient','patient:update'])->plainTextToken,
                            'patient_id'=>$patient->id
                        ])
                        ->send();

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
        *                 @OA\Property( property="gender",type="integer",example="male"),
        *                 @OA\Property( property="address",type="integer",example="adrar alg"),
        *                 @OA\Property( property="height",type="integer",example=180),
        *                 @OA\Property( property="weight",type="double",example="55.5"),
        *                 @OA\Property( property="allergy_id",type="integer",example=1),
        *                 @OA\Property( property="chronic_diseases_id",type="integer",example=1),
        *                 @OA\Property( property="family_history_id",type="integer",example=1),
        *                 @OA\Property( property="has_cancer_screening",type="boolean",enum={0, 1}),
        *                 @OA\Property( property="has_depression_screening",type="boolean",enum={0, 1}),
        *                 @OA\Property( property="other_problems",type="object"),
        *             )
        *        ),
        *    ),
        *    @OA\Response( response=200, description="Patient Record Completed Successfuly", @OA\JsonContent() ),
        *    @OA\Response( response=404,description="Patient not found with the given token or phone number, please login again", @OA\JsonContent()),
        *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
          *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
        *    )
        */
        public function storePatientData(PatientRequest $request)
        {

          $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::where('phone_number',$request->query('phone_number'))->firstOrFail();
          $patient->update($request->except('thumbnail','phone_number'));

          return $this->api->success()
                        ->message('Patient Record Completed Successfuly')
                        ->payload(['patient'=>$patient->makeHidden(["api_route", "can","created_at","updated_at","otp_verification_code","otp_expire_at"])])
                        ->send();
        }
        /**
        * @OA\Put(
        * path="/api/v1/patients/{id}/update-phone-number",
        * operationId="updatePhone",
        * security={ {"sanctum": {} }},
        * tags={"patients"},
        * summary="update patient phone number",
        * description="update patient phone_number ",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Patient id",
        *         required=false,
        *      ),
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
        *    @OA\Response( response=404,description="No Patient Found with the given phone number", @OA\JsonContent()),
        *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
        *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
        *    )
        */
        public function updatePhone(Request $request,$id)
        {
            $request->validate([
                'phone_number' => 'required|regex:/^(\+\d{1,2}\d{10})$/'
            ]);
            $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::findOrFail($id);
            $patient->update($request->only('phone_number'));

            return $this->api->success()
                        ->message('Phone number updated successfully')
                        ->payload(['phone_number'=>$patient->phone_number])
                        ->send();
        }
        /**
        * @OA\Post(
        * path="/api/v1/patients/{id}/update-thumbnail",
        * operationId="updateThumbnail",
        * security={ {"sanctum": {} }},
        * tags={"patients"},
        * summary="update patient thumbnail or profile photo",
        * description="update patient thumbnail(profile photo)",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Patient id",
        *         required=false,
        *      ),
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
        *    @OA\Response( response=404,description="No Patient Found with the given phone number", @OA\JsonContent()),
        *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
        *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),

        *    )
        */
        public function updateThumbnail(Request $request,$id)
        {
            $request->validate([
                'thumbnail' => ['required','mimes:jpg,jpeg,png', 'max:1024'],
            ]);
            $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::findOrFail($id);
            
            tap($patient->thumbnail, function ($previous) use ($request,$patient) {
                $patient->update([
                    'thumbnail' => $request->thumbnail->storePublicly(
                        'patients/thumbnails', ['disk' => 'public']
                    ),
                ]);
    
                if ($previous) {
                    Storage::disk('public')->delete($previous);
                }
            });

            return $this->api->success()
                        ->message('Thumbnail updated successfully')
                        ->payload(['thumbnail'=>$patient->thumbnail])
                        ->send();
        }
          /**
        * @OA\Delete(
        * path="/api/v1/patients/{id}",
        * operationId="deletePatientAccount",
        * security={ {"sanctum": {} }},
        * tags={"patients"},
        * summary="delete patient account",
        * description="delete patient account",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Patient id to delete",
        *         required=false,
        *      ),
        *    @OA\Response( response=200, description="You account was deleted successfully", @OA\JsonContent() ),
        *    @OA\Response( response=404,description="No Patient Found with the given phone number", @OA\JsonContent()),
        *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
        *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
        *    )
        */
        public function deletePatientAccount(Request $request,$id){
        
            $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::findOrFail($id);
            $patient->tokens()->delete();
            $patient->delete();

            return $this->api->success()
                        ->message('Your account was deleted successfully')
                        ->send();

        }
          /**
        * @OA\post(
        * path="/api/v1/patients/{id}/logout",
        * operationId="logout",
        * security={ {"sanctum": {} }},
        * tags={"patients"},
        * description="Logout a patient",
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Patient id to logout",
        *         required=false,
        *      ),
        *    @OA\Response( response=200, description="You logged out successfully", @OA\JsonContent() ),
        *    @OA\Response( response=404,description="No Patient Found with the given phone number", @OA\JsonContent()),
        *    @OA\Response(response=500,description="internal server error", @OA\JsonContent() ),
          *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
        *    )
        */
        public function logout(Request $request,$id){
        
            $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::findOrFail($id);
            $patient->tokens()->delete();

            return $this->api->success()
                        ->message('You logged out successfully')
                        ->send();


        }
          /**
        * @OA\Put(
        * path="/api/v1/patients/{id}/notifications-status/{status}",
        * operationId="switchNotificationsStataus",
        * security={ {"sanctum": {} }},
        * tags={"patients"},
        * description="change the notification status  on/off",
        
        *      @OA\Parameter(  name="id", in="path", description="Patient id to change notification state", required=false),
        *      @OA\Parameter(  name="status", in="path", description="notification status on/off", required=true,
        *        @OA\Schema(type="integer"),
        *         @OA\Examples(example="status", value="0",summary="switch notification on/off"),
        *        ),
        *      @OA\Response( response=200, description="notifications state switched successfully", @OA\JsonContent() ),
        *      @OA\Response( response=422,description="Please Provide a correct status format", @OA\JsonContent()),
        *    )
        */
        public function switchNotificationsStataus(Request $request,$id,$state){
            if(!in_array($state,[0,1])){
                return $this->api->failed()->code(422)
                            ->message('Please Provide a correct status format')
                            ->send();
            }
            $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::findOrFail($id);
            $patient->update(['notification_status'=>(boolean)$state]);

            return $this->api->success()
                        ->message((int)$state == 0 ? 'notifications turned off successfully' : 'notifications turned On successfully')
                        ->send();

        }
          /**
        * @OA\Get(
        * path="/api/v1/patients/{id}/scales",
        * operationId="getPatientScales",
        * security={ {"sanctum": {} }},
        * tags={"patients"},
        * description="get patient filled scales ",
        
        *      @OA\Parameter(  name="id", in="path", description="Patient id ", required=true),
        *      @OA\Response( response=200, description="scales fetched successfully", @OA\JsonContent() ),
        *      @OA\Response( response=401, description="unauthenticated", @OA\JsonContent() ),
        *    )
        */
        public function getPatientScales($id){
            
            $patient = auth('sanctum') ? auth('sanctum')->user() : Patient::findOrFail($id);
            
            $scales = PatientScale::wherePatientId($patient->id)
                                    ->with(['scale'=>function($q) {
                                        $q->select(['id','title','title_ar','short_description','short_description_ar']);
                                    }])
                                    ->get()
                                    ->makeHidden(["api_route", "can","created_at","updated_at"])
                                    ->map(function($q){
                                        return new PatientScaleResource($q);
                                    });

            return $this->api->success()
                        ->message('patient scales fetched successfully')
                        ->payload($scales)
                        ->send();

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
        public function recommendations(Request $request){
            try {
                $patient = auth('sanctum') ? auth('sanctum')->user() : $request->user();
                $recommendations = Recommendation::publishable()->byAgeAndSex($patient->gender, $patient->age ?? 18);
                
                return $this->api->success()
                ->message('recommendations fetched successfully')
                ->payload(RecommendationResource::collection($recommendations->get()))
                ->send();
            }
            catch (Exception $ex) {
                return $this->api->failed()
                    ->code($ex->getCode())
                    ->message($ex->getMessage())
                    ->send();
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
        *      @OA\Response( response=404, description="404 not found", @OA\JsonContent() ),
        *    )
        */
        public function recommendationDetails($id){
            $recommendation = Recommendation::findOrFail($id);
            return $this->api->success()
            ->message('recommendation fetched successfully')
            ->payload(new RecommendationResource($recommendation))
            ->send();
        }
}
