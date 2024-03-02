<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Doctor;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorProfileResource;
use App\Repositories\DoctorRepository;
use App\Http\Resources\SimpleDoctorResource;

class DoctorController extends Controller
{
    protected $repository;

      /**
     * @var DoctorRepository
     * @var ApiResponse
     */
    public function __construct(DoctorRepository $repository, ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
        /**
     * @OA\Get(
     *      path="/api/v1/doctors",
     *      operationId="doctor_list",
     *      tags={"patientApp"},
     *      description="Get list of existing doctors",
     *      @OA\Parameter(  name="search", in="query", description="search doctors"),
     *     @OA\Parameter(     @OA\Schema( default=false, type="string",
     *      enum={"best_rated","latest","oldest","is_online"} ),
     *       description="sort doctors",
     *       example="0", in="query", name="sort",
     *      ),
     *      @OA\Parameter(  name="limit", in="query", description="limit records", ),
     *      @OA\Parameter(  @OA\Schema( type="array",@OA\Items(type="integer"), example={1,2}), name="symptomes", in="query", description="selected symptomes"),
     *      @OA\Parameter(  @OA\Schema( type="array",@OA\Items(type="integer"), example={1,2}), name="chronic_diseases", in="query", description="selected chronic diseases"),
     *      @OA\Response(
     *          response=200,
     *          description="Doctors fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try {
            $doctors = $this->repository->index();
            if(array_key_exists('limit', request()->query())) return SimpleDoctorResource::collection($doctors->paginate(request()->query()['limit']));
            return SimpleDoctorResource::collection($doctors->get());
            // return $this->api->success()
            //     ->message("doctors fetched successfuly")
            //     ->payload(SimpleDoctorResource::collection($doctors))
            //     ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }

    }
        /**
     * @OA\Post(
     *      path="/api/v1/doctors/{id}/add-to-favorites",
     *      operationId="addd_doctor_to_favorites",
     *      tags={"patients"},
     *      security={ {"sanctum": {} }},
     *      description="add a doctor to a patient favorites",
     *      @OA\Parameter(name="id",in="path",description="doctor id",required=true),
     *      @OA\Response(
     *          response=200,
     *          description="Doctor added to favorites successfully",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function addToFavorites($id)
    {
        try {
            $do=$this->repository->addToFavorites($id);

            return $this->api->success()
                ->message("Doctor added to favorites successfully")
                ->payload($do)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }

    }
        /**
     * @OA\Delete(
     *      path="/api/v1/doctors/{id}/remove-from-favorites",
     *      operationId="remove_doctor_from_favorites",
     *      tags={"patients"},
     *      security={ {"sanctum": {} }},
     *      description="remove a doctor from a patient favorites",
     *      @OA\Parameter(name="id",in="path",description="doctor id",required=true),
     *      @OA\Response(
     *          response=200,
     *          description="Doctor removed from favorites successfully",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function removeFromFavorites($id)
    {
        try {
            $do=$this->repository->removeFromFavorites($id);

            return $this->api->success()
                ->message("Doctor removed from favorites successfully")
                ->payload($do)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *      path="/api/v1/doctors/{id}",
     *      operationId="doctor_details",
     *      tags={"patientApp"},
     *      description="Get doctor details",
     *      @OA\Parameter(  name="id", in="path", description="doctor id"),
     *      @OA\Response(
     *          response=200,
     *          description="Doctor details fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="No doctor found with the given id",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="internal server error",
     *          @OA\JsonContent()
     *       ),
     *     )
     */
    public function show($id)
    {
        try {
            $doctor = $this->repository->show($id);

            return $this->api->success()
                ->message("Doctor details fetched successfuly")
                ->payload(new DoctorProfileResource($doctor))
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
     /**
     * Display a listing of the resource.
     */
        /**
     * @OA\Post(
  *      path="/api/v1/symptomes/doctors",
     *      operationId="doctor_list_using_symptomes",
     *      tags={"patientApp"},
     *      description="Get list of existing doctors of selected symptomes",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                @OA\Property( property="symptomes",type="array",@OA\Items(type="integer"), example={1,2}),
     *                @OA\Property( property="search",type="string"),
     *                @OA\Property( property="sort",type="string",enum={"best_rated","latest","oldest","is_online"}),
     *      )
     * )
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Doctors fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function doctorsBySymptomes(Request $request)
    {
        try {
            $doctors = $this->repository->searchWithSymptomes($request);
            // if(array_key_exists('limit', request()->query())) return SimpleDoctorResource::collection($doctors->paginate(request()->query()['limit']));
            // return SimpleDoctorResource::collection($doctors->get());
            return $this->api->success()
                ->message("doctors fetched successfuly")
                ->payload($doctors)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }

    }
}
