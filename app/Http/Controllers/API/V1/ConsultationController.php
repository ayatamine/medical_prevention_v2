<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SummaryRequest;

use App\Repositories\ConsultationRepository;
use function App\Helpers\handleTwoCommunErrors;
use App\Http\Resources\DoctorConsultationResource;

class ConsultationController extends Controller
{
    protected $repository;

    /**
     * @var ConsultationRepository
     * @var ApiResponse
     */
    public function __construct(ConsultationRepository $repository, ApiResponse $api)
    {
        $this->api = $api;
        $this->repository = $repository;
    }
    
    /**
     * @OA\Get(
     *      path="/api/v1/consultations",
     *      operationId="get_doctor_consultations",
     *      tags={"doctors"},
     *      security={ {"sanctum": {} }},
     *      description="fetch doctor consultation (pending,accepted,finished)",
     *      @OA\Response(
     *          response=200,
     *          description="consultations fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="internal server error",
     *          @OA\JsonContent()
     *       ),
     *     )
     */
    public function index(){
        try {

            return $this->api->success()
                ->payload(new DoctorConsultationResource(request()->user()))
                ->message("The consultations fetched successfully")
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "There is no doctor with the given details please verify login");
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/consultations/{id}/approve",
     * operationId="approve_consultation",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="approve a patient consultation request",
     * description="the doctor approve a patient consultation request",
     * @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *      @OA\Response(response=200,description="The consult request approved successfully",@OA\JsonContent()),
     *      @OA\Response(response=400,description="There is no consultation related to this doctor with the given id",@OA\JsonContent()),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent())
     *     )
     */
    public function approveConsult($id)
    {
        try {
            $this->repository->approveConuslt($id);

            return $this->api->success()
                ->message("The consult request approved successfully")
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "There is no consultation related to this doctor with the given id");
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/consultations/{id}/reject",
     * operationId="reject_consultation",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="reject a patient consultation request",
     * description="the doctor reject a patient consultation request",
     * @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *      @OA\Response(response=200,description="The consult request rejected successfully",@OA\JsonContent()),
     *      @OA\Response(response=400,description="There is no consultation related to this doctor with the given id",@OA\JsonContent()),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent())
     *     )
     */
    public function rejectConsult($id)
    {
        try {
            $this->repository->rejectConsult($id);

            return $this->api->success()
                ->message("The consult request rejectedd successfully")
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "There is no consultation related to this doctor with the given id");
            // handleTwoCommunErrors($ex,"There is no consultation related to this doctor with the given id");
        }
    }
    /**
     * @OA\Post(
     * path="/api/v1/consultations/{id}/add-summary",
     * operationId="add_consultation_summary",
     * tags={"doctors"},
     * security={ {"sanctum": {} }},
     * summary="add a summary to a finished consultation",
     * description="the doctor add a summary to a finished consultation",
     * @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property( property="description",type="text",example="description here"),
     *                 @OA\Property( property="prescriptions",type="array",@OA\Items(type="integer"), example={1,2}),
     *                 @OA\Property( property="lab_tests",type="array",@OA\Items(type="integer"), example={1,2}),
     *                 @OA\Property( property="other_lab_tests",type="string",example="new test"),
     *                 @OA\Property( property="sick_leave",type="boolean"),
     *                 @OA\Property( property="notes",type="text"),
     *             )),
     *    ),
     *      @OA\Response(response=200,description="The summary added successfully",@OA\JsonContent()),
     *      @OA\Response(response=400,description="There is no consultation related to this doctor with the given id",@OA\JsonContent()),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent())
     *     )
     */
    public function addSummary(SummaryRequest $request, $id)
    {
        try {
            $this->repository->addSummary($request->validated(), $id);

            return $this->api->success()
                ->message("The summary added successfully")
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "There is no consultation related to this doctor with the given id");
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/consultations/pay",
     *      operationId="pay_consult",
     *      tags={"patientApp"},
     *      security={ {"sanctum": {} }},
     *      description="pay for consultation",
     *      @OA\Response(
     *          response=200,
     *          description="fetched successfuly",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function pay()
    {
        try {
            $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $customers = \Stripe\Customer::all([
                "email" => request()->user()->email,
                "limit" => 1,
            ]);
            if (sizeof($customers->data) !== 0) {
                $customerId = $customers->data[0]->id;
            } else {
                //create customer in stripe if user dose not exist there by his email
                $customer = \Stripe\Customer::create([
                    'email' => request()->user()->email,

                ]);
                $customerId = $customer->data->id;
            }
            $ephemeralKey = \Stripe\EphemeralKey::create(
                ['customer' => $customerId],
                ['stripe_version' => '2022-08-01'],
            );
            return response()->json( env('CONSULT_PRICE'));

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => 30,
                'currency' => 'usd',
                'customer' => $customerId,
            ]);
            $result =[
                'client_secret_key' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $customerId,
            ];
            return $this->api->success()
                ->message("payment details created successfully with secret details")
                ->payload($result)
                ->send();
        } catch (Exception $ex) {
            $this->apiResponse->failed()->code(500)
                ->message($ex->getMessage())
                ->send();
        }
    }
}
