<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SupportRequest;
use App\Http\Controllers\Controller;
use App\Models\SupportSubjectType;
use App\Notifications\SupportRequestNotification;
use Illuminate\Support\Facades\Notification;

class SupportRequestController extends Controller
{

         /**
     * @OA\Post(
     *      path="/api/v1/support/contact",
     *      operationId="contact support with messages",
     *      tags={"patientApp"},
     *      security={ {"sanctum": {} }},
     *      description="contact support with messages",
     *      @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                @OA\Property( property="subject_id",type="integer",example={1,2}),
     *                @OA\Property( property="user_type",type="string",example={"patient","doctor"}),
     *                @OA\Property( property="description",type="string",),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="the request to the support sent successfully , wait for response",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function saveRequest(Request $request)
    {

        try{

            $request->validate([
                'user_type' => 'required|string|in:doctor,patient',
                'subject_id' => 'required|integer|exists:support_subject_types,id',
                'description' => 'required|string',
            ]);

            // Create a new support request
            $supportRequest = new SupportRequest();
            $supportRequest->user_id = request()->user()->id;
            $supportRequest->user_type = $request->user_type;
            $supportRequest->support_subject_type_id = $request->subject_id;
            $supportRequest->description = $request->description;
            $supportRequest->save();

            $delay = now()->addMinutes(3);
            $admins = User::where('is_admin',true)->get();
             Notification::send($admins, (new SupportRequestNotification($supportRequest))->delay($delay));

             return $this->api->success()
                    ->message('the request to the support sent successfully , wait for response')
                    ->send();
        }
        catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }
        /**
     * @OA\Get(
     *      path="/api/v1/support/subject-types",
     *      operationId="get support subject types",
     *      tags={"patientApp"},
     *      description="get support subject types",
     *      @OA\Response(
     *          response=200,
     *          description="support subject types fetched successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="internal server error",
     *          @OA\JsonContent()
     *       ),
     *     )
     */
    public function subjectTypes()
    {
        $subjects = SupportSubjectType::select('id','name','name_ar')->get();
        return $this->api->success()
                    ->payload($subjects)
                    ->message('support subject types fetched successfuly')
                    ->send();
    }
}
