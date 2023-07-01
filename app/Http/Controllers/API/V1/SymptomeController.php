<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SymptomeRepository;

class SymptomeController extends Controller
{
    protected $repository;

    /**
     * @var SymptomeRepository
     * @var ApiResponse
     */
    public function __construct(SymptomeRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/symptomes",
     *      operationId="symptomesindex",
     *      tags={"patientApp"},
     *      description="Get list of common symptomes",
     *      @OA\Parameter(  name="limit", in="query", description="limit records",required=false),
     *      @OA\Response(
     *          response=200,
     *          description="common symptomes fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $symptomes = $this->repository->fetchAll();

            return $this->api->success()
                ->message("symptomes fetched successfuly")
                ->payload($symptomes)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
}
