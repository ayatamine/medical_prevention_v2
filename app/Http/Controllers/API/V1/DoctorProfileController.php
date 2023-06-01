<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DoctorRepository;

class DoctorProfileController extends Controller
{
    protected $repository;

    /**
     * @var DoctorRepository
     * @var ApiResponse
     */
    public function __construct(DoctorRepository $repository,ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
   * @OA\Put(
    * path="/api/v1/doctors/notification-status/{status}",
    * operationId="switch notification status",
    * tags={"doctors"},
    * security={ {"sanctum": {} }},
    * summary="switch notification status on / off for the doctor ",
    * description="switch notification status on / off for the doctor  ",
    *      @OA\Parameter(  name="status", in="path", description="notification status : 0,1", required=true, *     @OA\Schema(
    *       type="string",
    *       enum={ 0,1},
    *       ),
    *      ),
    *      @OA\Response( response=200, description="The notification status updated successfully", @OA\JsonContent() ),
    *      @OA\Response( response=404, description="no doctor found please verfiy your login status", @OA\JsonContent() ),
    *    )
    */
    public function switchNotificationStatus($status){
        try{
            $this->repository->switchNotification($status);

            return $this->api->success()
                             ->message("The notification status updated successfully")
                             ->send();
        }
        catch(Exception $ex){
            return handleTwoCommunErrors($ex,"no doctor found please verfiy your login status");
        }
    }
    /**
   * @OA\Put(
    * path="/api/v1/doctors/online-status/{status}",
    * operationId="switch online status",
    * tags={"doctors"},
    * security={ {"sanctum": {} }},
    * summary="switch online status on / off for the doctor ",
    * description="switch online status on / off for the doctor  ",
    *      @OA\Parameter(  name="status", in="path", description="online status : 0,1", required=true, *     @OA\Schema(
    *       type="string",
    *       enum={ 0,1},
    *       ),
    *      ),
    *      @OA\Response( response=200, description="The online status updated successfully", @OA\JsonContent() ),
    *      @OA\Response( response=404, description="no doctor found please verfiy your login status", @OA\JsonContent() ),
    *    )
    */
    public function switchOnlineStatus($status){
        try{
            $this->repository->switchOnlineStatus($status);

            return $this->api->success()
                             ->message("The online status updated successfully")
                             ->send();
        }
        catch(Exception $ex){
            return handleTwoCommunErrors($ex,"no doctor found please verfiy your login status");
        }
    }
}
