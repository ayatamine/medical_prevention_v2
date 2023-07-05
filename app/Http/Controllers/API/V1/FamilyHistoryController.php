<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FamilyHistoryRepository;

class FamilyHistoryController extends Controller
{
    protected $repository;

    /**
     * @var FamilyHistoryRepository
     * @var ApiResponse
     */
    public function __construct(FamilyHistoryRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/family-histories",
     *      operationId="family_history_index",
     *      tags={"patientApp"},
     *      description="Get list of existing family histories",
     *      @OA\Response(
     *          response=200,
     *          description="data fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $family_history = $this->repository->fetchAll();

            return $this->api->success()
                ->message("family history fetched successfuly")
                ->payload($family_history)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
}
