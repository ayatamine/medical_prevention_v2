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
        * path="/api/v1/doctors/my-wallet/history",
        * operationId="my_balance_history",
        * tags={"doctors"},
        * security={ {"sanctum": {} }},
        * summary="return the balance history for the doctor ",
        * description="return the balance history for the doctor  ",
        *      @OA\Parameter(  name="period", in="query", description="filter by period", required=false, *     @OA\Schema(
        *       default="all",
        *       type="string",
        *       enum={ "all","last_month","last_three_months","last_year" },
        *       ),
        *      ),
        *      @OA\Response( response=200, description="The balance history fetched successfully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no doctor found please verfiy your login status", @OA\JsonContent() ),
        *    )
        */
        public function ballance_history(){
            try{
                $ballance_history =  $this->repository->ballance_history();

                return $this->api->success()
                                 ->message("The balance history fetched successfully")
                                 ->payload([
                                    'data'=>[
                                        'balance'=>request()->user()?->ballance,
                                        'balance_history'=>(BallanceHistoryResource::collection($ballance_history))->resolve()
                                    ],
                                    'total' => $ballance_history->total(),
                                    'last_page' => $ballance_history->lastPage(),
                                    'first_page_url' => $ballance_history->url(1),
                                    'from' => $ballance_history->firstItem(),
                                    'last_page_url' => $ballance_history->url($ballance_history->lastPage()),
                                    'next_page_url' => $ballance_history->nextPageUrl(),
                                    'prev_page_url' => $ballance_history->previousPageUrl(),
                                    'current_page' => $ballance_history->currentPage(),
                                    'per_page' => $ballance_history->perPage(),
                                    'to' => $ballance_history->lastItem(),
                                    'path' => $ballance_history->path(),
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
