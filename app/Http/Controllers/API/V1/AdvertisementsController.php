<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Repositories\AdvertisementsRepository;

class AdvertisementsController extends Controller
{
    protected $repository;

    /**
     * @var AdvertisementsRepository
     * @var ApiResponse
     */
    public function __construct(AdvertisementsRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/advertisements",
     *      operationId="Advertisement_index",
     *      tags={"advertisemens"},
     *      description="Get list of existing publishable advertisments",
     *      @OA\Response(
     *          response=200,
     *          description="Advertisements fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $advertismenets = $this->repository->fetchAll();

            return $this->api->success()
                ->message("Advertisements fetched successfuly")
                ->payload(AdvertisementResource::collection($advertismenets))
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
}
