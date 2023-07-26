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
    //     /**
    //    * @OA\Get(
    //     * path="/api/v1/doctors/medicines/store",
    //     * operationId="prescriptionsList",
    //     * tags={"doctors"},
    //     * security={ {"sanctum": {} }},
    //     * summary="get doctor prescriptions list ",
    //     * description="get doctor prescriptions list  ",
    //     *      @OA\Response( response=200, description="prescriptions fetched succefully", @OA\JsonContent() ),
    //     *      @OA\Response( response=404, description="no prescriptions found ", @OA\JsonContent() ),
    //     *    )
    //     */
    //     public function index(){
    //         try{
    //             $prescriptions  = $this->repository->findAllBy('doctor_id',request()->user()->id,['id','drug_name']);
                
    //             return $this->api->success()
    //                              ->message("prescriptions fetched succefully")
    //                              ->payload($prescriptions)
    //                              ->send();
    //         }
    //         catch(Exception $ex){
    //             return handleTwoCommunErrors($ex,"no prescriptions found");
    //         }
    //     }
        
          /**
        * @OA\Post(
        * path="/api/v1/doctors/medicines/store",
        * operationId="storeNewmedicine",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="create a new medicine ",
        * description="create a new medicine by a doctor ",
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
        *                 @OA\Property( property="prn",type="boolean",enum={0, 1}),
        *                 @OA\Property( property="instructions",type="text"),
        *             )),
        *    ),
        *      @OA\Response(response=200,description="the medicine created succefully",@OA\JsonContent()),
        *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
        *      @OA\Response( response=301,description="unauthorized", @OA\JsonContent()),
        *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
        *     )
        */
        public function store(PrescriptionRequest $request){
            try{
                $medicine  = $this->repository->store($request->validated());
                
                return $this->api->success()
                                 ->message("the medicine created succefully")
                                 ->payload([
                                    'id'=>$medicine->id,
                                    'drug_name'=>$medicine->drug_name,
                                 ])
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex);
            }
        }
         /**
       * @OA\Get(
        * path="/api/v1/doctors/medicines/list",
        * operationId="medicinesList",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="get doctor medecines list ",
        * description="get doctor me list  ",
        *       @OA\Parameter(name="search",in="query",description="medicine name"),
        *      @OA\Response( response=200, description="medicines fetched succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no medicine found ", @OA\JsonContent() ),
        *    )
        */
        public function searchMedicines(){
            try{
                $medicines  = $this->repository->myMedicineList();
                
                return $this->api->success()
                                 ->message("medicines fetched succefully")
                                 ->payload($medicines)
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex,"no medicines found");
            }
        }
         /**
       * @OA\Get(
        * path="/api/v1/medicines/list",
        * operationId="searchMedicinesList",
        * tags={"consultation"},
        * security={ {"sanctum": {} }},
        * summary="get medecines list ",
        * description="get medicines list  ",
        *       @OA\Parameter(name="search",in="query",description="scientific_name or commercial_name"),
        *      @OA\Response( response=200, description="medicines fetched succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no medicine found ", @OA\JsonContent() ),
        *    )
        */
        public function searchMedicinesList(){
            try{
                $medicines  = $this->repository->searchMedicineList();
                
                return $this->api->success()
                                 ->message("medicines fetched succefully")
                                 ->payload($medicines)
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex,"no medicines found");
            }
        }
}
