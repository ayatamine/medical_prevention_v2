<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ConsultationRepository;

use function App\Helpers\handleTwoCommunErrors;

class ConsultationController extends Controller
{
    protected $repository;

        /**
         * @var ConsultationRepository
         * @var ApiResponse
         */
        public function __construct(ConsultationRepository $repository,ApiResponse $apiResponse)
        {
            parent::__construct($apiResponse);
            $this->repository = $repository;
        }

          /**
        * @OA\Post(
        * path="/api/v1/consultations/{id}/approve",
        * operationId="approve_consultation",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="approve a patient consultation request",
        * description="the doctor approve a patient consultation request",
        * @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
        *      @OA\Response(response=200,description="The consult request approved successfully",@OA\JsonContent()),
        *      @OA\Response(response=400,description="There is no consultation related to this doctor with the given id",@OA\JsonContent()),
        *      @OA\Response( response=500,description="internal server error", @OA\JsonContent())
        *     )
        */
        public function approveConsult($id)
        {
            try{
                $this->repository->approveConuslt($id);

                return $this->api->success()
                                 ->message("The consult request approved successfully")
                                 ->send();
            }
            catch(Exception $ex){
                return handleTwoCommunErrors($ex,"There is no consultation related to this doctor with the given id");
            }
        }
          /**
        * @OA\Post(
        * path="/api/v1/consultations/{id}/reject",
        * operationId="reject_consultation",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="reject a patient consultation request",
        * description="the doctor reject a patient consultation request",
        * @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
        *      @OA\Response(response=200,description="The consult request rejected successfully",@OA\JsonContent()),
        *      @OA\Response(response=400,description="There is no consultation related to this doctor with the given id",@OA\JsonContent()),
        *      @OA\Response( response=500,description="internal server error", @OA\JsonContent())
        *     )
        */
        public function rejectConsult($id)
        {
            try{
                $this->repository->rejectConsult($id);

                return $this->api->success()
                                 ->message("The consult request rejectedd successfully")
                                 ->send();
            }
            catch(Exception $ex){
               return handleTwoCommunErrors($ex,"There is no consultation related to this doctor with the given id");
                // handleTwoCommunErrors($ex,"There is no consultation related to this doctor with the given id");
            }
        }
}
