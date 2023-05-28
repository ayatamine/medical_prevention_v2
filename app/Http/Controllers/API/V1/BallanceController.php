<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BallanceHistoryRepository;
use App\Http\Resources\BallanceHistoryResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BallanceController extends Controller
{
        protected $repository;

        /**
         * @var BallanceHistoryRepository
         * @var ApiResponse
         */
        public function __construct(BallanceHistoryRepository $repository,ApiResponse $apiResponse)
        {
            parent::__construct($apiResponse);
            $this->repository = $repository;
        }
     /**
       * @OA\Get(
        * path="/api/v1/doctors/my-wallet",
        * operationId="my_ballance_history",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="return the ballance history for the doctor ",
        * description="return the ballance history for the doctor  ",
        *      @OA\Parameter(  name="period", in="query", description="filter by period", required=false, *     @OA\Schema(
        *       default="all",
        *       type="string",
        *       enum={ "all","last_month","last_three_months","last_year" },
        *       ),
        *      ),
        *      @OA\Response( response=200, description="The ballance history fetched successfully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no doctor found please verfiy your login status", @OA\JsonContent() ),
        *    )
        */
        public function ballance_history(){
            try{
                $ballance_history =  $this->repository->ballance_history();

                return $this->api->success()
                                 ->message("The ballance history fetched successfully")
                                 ->payload([
                                    'ballance'=>request()->user()?->ballance,
                                    'ballance_history'=>(BallanceHistoryResource::collection($ballance_history))->resolve()
                                 ])
                                 ->send();
            }
            catch(Exception $ex){
                if ($ex instanceof ModelNotFoundException) {
                    return $this->api->failed()->code(404)
                                ->message("no doctor found please verfiy your login status")
                                ->send();
        
                }
                return $this->api->failed()->code(500)
                                    ->message($ex->getMessage())
                                    ->send();
            }
        }
}
