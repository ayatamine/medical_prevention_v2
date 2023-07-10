<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\ChatMessage;
use App\Helpers\ApiResponse;

use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Models\BallanceHistory;
use App\Events\ChatMessageEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\SummaryRequest;
use App\Http\Resources\ConsultationResouce;
use App\Http\Resources\ConsultationResource;

use App\Repositories\ConsultationRepository;
use App\Http\Resources\MedicalRecordResource;
use App\Http\Resources\PatientMedicalResource;
use App\Http\Resources\DoctorConsultationRequestResource;

class ConsultationController extends Controller
{
    protected $repository;
    public $consultation;
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
    public function index()
    {
        try {

            return $this->api->success()
                ->payload(new DoctorConsultationRequestResource(request()->user()))
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
     * @OA\Post(
     *      path="/api/v1/consultations/payment/create",
     *      operationId="pay_consult",
     *      tags={"consultation"},
     *      security={ {"sanctum": {} }},
     *      description="pay for consultation with doctor",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property( property="doctor_id",type="integer"),
     *             )),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
     *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
     *     )
     */
    public function pay(Request $request)
    {
        try {
            $this->validate($request, [
                'doctor_id' => 'integer|required|exists:doctors,id'
            ]);
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

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => env('CONSULT_PRICE') * 100,
                'currency' => 'usd',
                'customer' => $customerId,
                'metadata' => [
                    'doctor_id' => $request->doctor_id,
                    'customer' => $customerId,
                ],
            ]);

            $result = [
                'client_secret_key' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $customerId,
            ];
            return $this->api->success()
                ->message("payment details created successfully with secret details")
                ->payload($result)
                ->send();
        } catch (\Stripe\Exception\CardException $ex) {
            return $this->api->failed()
                ->message($ex->getError()->message)
                ->send();
        } catch (\Stripe\Exception\InvalidRequestException $ex) {
            return $this->api->failed()
                ->message($ex->getError()->message)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Post(
     *      path="/api/v1/consultations/payment/verify",
     *      operationId="paymentVerify",
     *      tags={"consultation"},
     *      security={ {"sanctum": {} }},
     *      description="paymentVerify for consultation",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property( property="payment_intent_id",type="string"),
     *             )),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
     *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
     *     )
     */
    public function paymentVerify(Request $request)
    {
        try {
            $this->validate($request, [
                'payment_intent_id' => 'string|required',
            ]);
            $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            // get from user payment intent id to check payment if done 
            $response = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

            if ($response->status == 'succeeded') {
                DB::transaction(function () use ($response) {
                    //TODO: check if consult is already created
                    $this->consultation = Consultation::where('paymentintent_id', $response->id)
                        ->wherePatientId(request()->user()->id)
                        ->first();
                    if (!$this->consultation) {
                        $this->consultation = Consultation::create([
                            'doctor_id' => $response->metadata->doctor_id,
                            'patient_id' => request()->user()->id,
                            'status' => 'pending',
                        ]);
                        //TODO: cron job
                        BallanceHistory::create([
                            'user_id' => request()->user()->id,
                            'user_type' => Patient::class,
                            'amount'   => ($response->amount) / 100,
                            'operation_type' => BallanceHistory::$PFC,
                            'consult_id' => $this->consultation->id
                        ]);
                        BallanceHistory::create([
                            'user_id' => $response->metadata->doctor_id,
                            'user_type' => Doctor::class,
                            'amount'   => ($response->amount) / 100,
                            'operation_type' => BallanceHistory::$RFC,
                            'consult_id' => $this->consultation->id
                        ]);
                        //update doctor ballance
                        Doctor::findOrFail($response->metadata->doctor_id)->increment('ballance', $response->amount / 100);
                        //update patient ballance
                        request()->user()->decrement('ballance', $response->amount / 100);
                        //TODO: cron job to send emails
                    }
                });
            }
            return $this->api->success()
                ->message("payment checked successfully")
                ->payload([
                    "payment_status" => "succeded",
                    "consultation_id" => $this->consultation?->id
                ])
                ->send();
        } catch (\Stripe\Exception\CardException $ex) {
            return $this->api->failed()
                ->message($ex->getError()->message)
                ->send();
        } catch (\Stripe\Exception\InvalidRequestException $ex) {
            return $this->api->failed()
                ->message($ex->getError()->message)
                ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/consultations/{id}/chat-messages",
     *      operationId="get a consultation chat messages",
     *      tags={"consultation"},
     *      security={ {"sanctum": {} }},
     *      description="get consultation chat message",
     *      @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *      @OA\Response(
     *          response=200,
     *          description="consultations chat messages fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="The Consultation either not found or not yet accepted",
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
    public function getConsultationChat($id)
    {
        try {
            $consultation = Consultation::with('chatMessages')
                ->when(request()->user()->tokenCan('role:patient'), function ($query) {
                    $query->wherePatientId(request()->user()->id);
                })
                ->when(request()->user()->tokenCan('role:doctor'), function ($query) {
                    $query->whereDoctorId(request()->user()->id);
                })
                ->with('doctor:id,full_name,thumbnail')
                ->with('patient:id,full_name,thumbnail')
                ->where('status', "in_progress")
                ->findOrFail($id);

            return $this->api->success()
                ->message("The chat messages fetched successfully")
                ->payload(new ConsultationResouce($consultation))
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "The Consultation either not found or not yet accepted");
        }
    }
    /**
     * @OA\Post(
     *      path="/api/v1/consultations/{id}/send-message",
     *      operationId="sendConsultationMessage",
     *      tags={"consultation"},
     *      security={ {"sanctum": {} }},
     *      description="sendConsultationMessage",
     *      @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property( property="sender_type",type="string",enum={"patient", "doctor"}),
     *                 @OA\Property( property="content",type="string"),
     *                 @OA\Property( property="attachement",type="file"),
     *             )),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="message sended successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
     *      @OA\Response( response=404,description="The Consultation either not found or not yet accepted", @OA\JsonContent()),
     *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
     *     )
     */
    public function sendMessage(Request $request, $id)
    {

        try {
            $this->validate($request, [
                'sender_type' => 'required|in:"patient","doctor"',
                'content' => 'sometimes|nullable|string|required_without:attachement',
                'attachement' => 'sometimes|nullable|file|max:3000|required_without:content',
            ]);
            $sender_type = $request['sender_type'];
            $filename = '';
            if ($request['attachement']) {
                $filename = $request['attachement']->storePublicly(
                    "consultations/files",
                    ['disk' => 'public']
                );
            }

            $consult = Consultation::where('status', "in_progress")->findOrFail($id);

            $chatMessage = new ChatMessage();
            $chatMessage->consultation_id = $id;
            $chatMessage->sender_type = ($sender_type == 'patient') ? Patient::class : Doctor::class;
            $chatMessage->sender_id = request()->user()->id;
            $chatMessage->receiver_id = ($sender_type == 'patient') ? $consult->doctor_id : $consult->patient_id;
            $chatMessage->receiver_type = ($sender_type == 'patient') ? Doctor::class : Patient::class;
            $chatMessage->content = $request['content'];
            $chatMessage->attachement = $filename ?? null;
            $chatMessage->save();

            event(new ChatMessageEvent($chatMessage));

            return $this->api->success()
                ->message("The message sended successfully")
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "The Consultation either not found or not yet accepted");
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/consultations/{id}/medical-record",
     *      operationId="consultMedicalRecord",
     *      tags={"consultation"},
     *      security={ {"sanctum": {} }},
     *      description="get patient medical record of a given consultation",
     *      @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *      @OA\Response(
     *          response=200,
     *          description="patient medical record fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="No consultation found with the given id",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="internal server error",
     *          @OA\JsonContent()
     *       ),
     *     )
     */
    public function patientMedicalRecord($consultation_id)
    {
        try {
            $result = $this->repository->patientMedicalRecord($consultation_id); 

            return $this->api->success()
                ->payload(new MedicalRecordResource($result))
                ->message("patient medical record fetched successfuly")
                ->send();
        } catch (Exception $ex) {
            return handleTwoCommunErrors($ex, "No consultation found with the given id");
        }
    }
}
