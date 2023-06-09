<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SpecialityResource;
use App\Repositories\SpecialityRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SpecialityController extends Controller
{
        protected $repository;

        /**
         * @var DoctorRepository
         */
        public function __construct(SpecialityRepository $repository,ApiResponse $apiResponse)
        {
            parent::__construct($apiResponse);
            $this->repository = $repository;
        }
    
       /**
       * @OA\Get(
        * path="/api/v1/specialities",
        * operationId="specialitis_index",
        * tags={"specialities"},
        * summary="get list of doctors specialities ",
        * description="get list of doctors specialities  ",
        *      @OA\Response( response=200, description="specialities fetched successfully", @OA\JsonContent() ),
        *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
        *    )
        */
        public function index()
        {
            try {
                 $specialities = $this->repository->getWithSubs();
                 return $this->api->success()
                        ->message('specialities fetched successfully')
                        ->payload((SpecialityResource::collection($specialities))->resolve())
                        ->send();
            }
             catch(Exception $ex){

                return $this->api->failed()->code(500)
                                    ->message($ex->getMessage())
                                    ->send();
            }
            
        }
       /**
       * @OA\Get(
        * path="/api/v1/specialities/{id}",
        * operationId="specialy_show",
        * tags={"specialities"},
        * summary="get details of a speciality by id  ",
        * description="get details of a speciality by id  ",
        *      @OA\Parameter(  name="id", in="path", description="speciality id ", required=true),
        *      @OA\Response( response=200, description="speciality details fetched successfully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no speciality found with the given id", @OA\JsonContent() ),
        *      @OA\Response( response=500, description="internal server error", @OA\JsonContent() ),
        *    )
        */
        public function show($id)
        {
            try {
                 $speciality = $this->repository->getDetails($id);
                 return $this->api->success()
                        ->message('speciality details fetched successfully')
                        ->payload((new SpecialityResource($speciality))->resolve())
                        ->send();
            }
             catch(Exception $ex){
                if ($ex instanceof ModelNotFoundException) {
                    return $this->api->failed()->code(404)
                                ->message("no speciality found with the given id")
                                ->send();
        
                }
                return $this->api->failed()->code(500)
                                    ->message($ex->getMessage())
                                    ->send();
            }
            
        }
}
