<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AllergyRepository;

class AllergyController extends Controller
{
    protected $repository;

    /**
     * @var AllergyRepository
     * @var ApiResponse
     */
    public function __construct(AllergyRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/allergies",
     *      operationId="allergiesindex",
     *      tags={"patientApp"},
     *      description="Get list of common allergies",
     *      @OA\Parameter(  name="limit", in="query", description="limit records",required=false),
     *      @OA\Response(
     *          response=200,
     *          description="common allergies fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $allergies = $this->repository->fetchAll();

            return $this->api->success()
                ->message("allergies fetched successfuly")
                ->payload($allergies)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
}