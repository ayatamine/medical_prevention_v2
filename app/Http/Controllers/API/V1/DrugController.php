<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\Drug;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DrugRequest;
use App\Http\Controllers\Controller;
use App\Repositories\DrugRepository;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\DoctorDrugResource;
use App\Http\Resources\DoctorDrugMedicineResource;

class DrugController extends Controller
{
        protected $repository;

        /**
         * @var DrugRepository
         * @var ApiResponse
         */
        public function __construct(DrugRepository $repository,ApiResponse $apiResponse)
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
        * path="/api/v1/doctors/drugs/store",
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
        *                 @OA\Property( property="prn",type="boolean",enum={0, 1}),
        *                 @OA\Property( property="instructions",type="text"),
        *             )),
        *    ),
        *      @OA\Response(response=200,description="the drug created succefully",@OA\JsonContent()),
        *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
        *      @OA\Response( response=301,description="unauthorized", @OA\JsonContent()),
        *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
        *     )
        */
        public function store(DrugRequest $request){
            try{
                $medicine  = $this->repository->store($request->validated());

                return $this->api->success()
                                 ->message("the drug created succefully")
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
        * path="/api/v1/doctors/drugs/list",
        * operationId="drugList",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="get doctor medecines list ",
        * description="get doctor me list  ",
        *       @OA\Parameter(name="search",in="query",description="medicine name"),
        *      @OA\Response( response=200, description="drugs fetched succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no medicine found ", @OA\JsonContent() ),
        *    )
        */
        public function searchDrugs(){
            try{
                $drugs  = $this->repository->myMedicineList();
                // $formated_drugs=[];
                // $i=0;
                // foreach($drugs as $key=>$medicine){
                //     $formated_drugs[$i] =[
                //         "drug_name"=>$key,
                //         "drugs"=>new DoctorDrugResource($medicine)
                //     ];
                //     $i++;
                // }
                $collection = DoctorDrugResource::collection($drugs);
                return $this->api->success()
                                 ->message("drugs fetched succefully")
                                 ->payload([
                                    'data' => $collection->items(),
                                    'total' => $drugs->total(),
                                    'last_page' => $drugs->lastPage(),
                                    'first_page_url' => $drugs->url(1),
                                    'from' => $drugs->firstItem(),
                                    'last_page_url' => $drugs->url($drugs->lastPage()),
                                    'next_page_url' => $drugs->nextPageUrl(),
                                    'prev_page_url' => $drugs->previousPageUrl(),
                                    'current_page' => $drugs->currentPage(),
                                    'per_page' => $drugs->perPage(),
                                    'to' => $drugs->lastItem(),
                                    'path' => $drugs->path(),
                                ])
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex,"no drugs found");
            }
        }
         /**
       * @OA\Get(
        * path="/api/v1/medicines/list",
        * operationId="searchMedicinesList",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="get medecines list ",
        * description="get medicines list  ",
        *       @OA\Parameter(name="search",in="query",description="scientific_name or commercial_name",example="ziag"),
        *       @OA\Parameter(name="page",in="query",description="page number"),
        *       @OA\Parameter(name="limit",in="query",description="number of items in each page"),
        *      @OA\Response( response=200, description="medicines fetched succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no medicine found ", @OA\JsonContent() ),
        *    )
        */
        public function searchMedicinesList(){
            try{
                $medicines  = $this->repository->searchMedicineList();

                return $this->api->success()
                                 ->message("medicines fetched succefully")
                                 ->payload(MedicineResource::collection($medicines))
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex,"no medicines found");
            }
        }
           /**
     * @OA\Get(
     *      path="/api/v1/lab-tests",
     *      operationId="lab testsindex",
     *      tags={"patientApp"},
     *      description="Get list of common lab tests",
     *      @OA\Parameter(  name="limit", in="query", description="limit records",required=false),
     *      @OA\Response(
     *          response=200,
     *          description="common lab tests fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function labTests()
    {
        try {
            $labtests = $this->repository->labTests();

            return $this->api->success()
                ->message("lab tests fetched successfuly")
                ->payload($labtests)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
     /**
       * @OA\Delete(
        * path="/api/v1/doctors/drugs/{id}",
        * operationId="drug_delete",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="delete a doctor saved drug ",
        * description="delete a doctor saved drug   ",
        *      @OA\Parameter(name="drug_id",in="path",description="drug id to delete"),
        *      @OA\Response( response=200, description="drug deleted succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no drug found ", @OA\JsonContent() ),
        *    )
        */
        public function destroy($id)
        {
            try {

                $this->repository->destroy($id);

                return $this->api->success()
                    ->message("drug deleted succefully")
                    ->send();
            } catch (Exception $ex) {
                return handleTwoCommunErrors($ex, 'no drug found with this id');
            }
        }
}
