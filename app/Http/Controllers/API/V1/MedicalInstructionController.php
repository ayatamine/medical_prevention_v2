<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalInstructionResource;
use App\Repositories\MedicalInstructionsRepository;

class MedicalInstructionController extends Controller
{
    protected $repository;

    /**
     * @var MedicalInstructionsRepository
     * @var ApiResponse
     */
    public function __construct(MedicalInstructionsRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * @OA\Get(
     *      path="/api/v1/medical-instructions",
     *      operationId="medical_instructions",
     *      tags={"patientApp"},
     *      description="Get list of existing medical instructions",
     *      @OA\Response(
     *          response=200,
     *          description="Medical instructions fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $instructions = $this->repository->fetchAll();

            return $this->api->success()
                ->message("Advertisements fetched successfuly")
                ->payload(MedicalInstructionResource::collection($instructions))
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->code($ex->getCode())
                ->message($ex->getMessage())
                ->send();
        }
    }
}
