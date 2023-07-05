<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ChronicDiseasesRepository;

class ChronicDiseasesController extends Controller
{
    protected $repository;

    /**
     * @var ChronicDiseasesRepository
     * @var ApiResponse
     */
    public function __construct(ChronicDiseasesRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/chronic-diseases",
     *      operationId="chronicDiseasesindex",
     *      tags={"patientApp"},
     *      description="Get list of chronic diseases",
     *      @OA\Parameter(  name="limit", in="query", description="limit records"),
     *      @OA\Response(
     *          response=200,
     *          description="chronic diseases fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $chronic_diseases = $this->repository->fetchAll();

            return $this->api->success()
                ->message("chronic diseases fetched successfuly")
                ->payload($chronic_diseases)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
}
