<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrescriptionRequest;
use App\Repositories\PrescriptionRepository;

class PrescriptionController extends Controller
{
        protected $repository;

        /**
         * @var PrescriptionRepository
         * @var ApiResponse
         */
        public function __construct(PrescriptionRepository $repository,ApiResponse $apiResponse)
        {
            parent::__construct($apiResponse);
            $this->repository = $repository;
        }
        /**
       * @OA\Get(
        * path="/api/v1/my-prescriptions",
        * operationId="prescriptionsList",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="get doctor prescriptions list ",
        * description="get doctor prescriptions list  ",
        *      @OA\Response( response=200, description="prescriptions fetched succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no prescriptions found ", @OA\JsonContent() ),
        *    )
        */
        public function index(){
            try{
                $prescriptions  = $this->repository->findAllBy('doctor_id',request()->user()->id,['id','drug_name']);
                
                return $this->api->success()
                                 ->message("prescriptions fetched succefully")
                                 ->payload($prescriptions)
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex,"no prescriptions found");
            }
        }
        
          /**
        * @OA\Post(
        * path="/api/v1/my-prescriptions/store",
        * operationId="storeNewPrescription",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="create a new prescription ",
        * description="create a new prescription by a doctor ",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="application/x-www-form-urlencoded",
        *             @OA\Schema(
        *                 @OA\Property( property="drug_name",type="string",example="Medicine name"),
        *                 @OA\Property( property="route",type="string",example=""),
        *                 @OA\Property( property="dose",type="integer",example="1"),
        *                 @OA\Property(property="unit",type="string",example="1 unit"),
        *                 @OA\Property( property="frequancy",type="string",example="1 times per day"),
        *                 @OA\Property( property="duration",type="integer",example="1"),
        *                 @OA\Property( property="duration_unit",type="string",example="day", description="duration unit in : {hour,day,week,month,year}"),
        *                 @OA\Property( property="shape",type="string"),
        *                 @OA\Property( property="prn",type="boolean"),
        *                 @OA\Property( property="instructions",type="text"),
        *             )),
        *    ),
        *      @OA\Response(response=200,description="the prescription created succefully",@OA\JsonContent()),
        *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
        *      @OA\Response( response=301,description="unauthorized", @OA\JsonContent()),
        *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
        *     )
        */
        public function store(PrescriptionRequest $request){
            try{
                $prescription  = $this->repository->store($request->validated());
                
                return $this->api->success()
                                 ->message("the prescription created succefully")
                                 ->payload([
                                    'id'=>$prescription->id,
                                    'drug_name'=>$prescription->drug_name,
                                 ])
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex);
            }
        }
}
