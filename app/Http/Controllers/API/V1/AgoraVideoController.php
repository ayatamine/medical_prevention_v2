<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Events\MakeAgoraCall;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AgoraVideoController extends Controller
{
    public function index(Request $request)
    {
        // fetch all users apart from the authenticated user
        $users = User::where('id', '<>', Auth::id())->get();
        return view('agora-chat', ['users' => $users]);
    }
 /**
     * @OA\Post(
     *      path="/api/v1/consultations/{id}/token",
     *      operationId="create_agora_token",
     *      tags={"agoraCall"},
     *      security={ {"sanctum": {} }},
     *      description="create agora token for consultation call",
     *      @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *      @OA\Response(
     *          response=200,
     *          description="token generated successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
     *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
     *     )
     */
    public function token($id)
    {

        try{
            $consultation = request()->user()->consultations()->findOrFail($id);
            $appID = env('AGORA_APP_ID');
            $appCertificate = env('AGORA_APP_CERTIFICATE');
            // $channelName = $request->channelName;
            $channelName = "consultation_".$id;
            $user = request()->user()->id;
            $role = RtcTokenBuilder::RolePublisher;
            $expireTimeInSeconds = 3600;
            $currentTimestamp = now()->getTimestamp();
            $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

            $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

            return $this->api->success()
                ->message('The call token generated successfully')
                ->payload([
                    "token"=>$token
                ])
                ->send();

        } catch (Exception $ex) {
            if($ex instanceof ModelNotFoundException)
                {
                    return $this->api->failed()
                            ->message('There is no consultation related to this user with the given id')
                            ->send();
                }
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }
    /**
     * @OA\Post(
     *      path="/api/v1/consultations/{id}/call-user",
     *      operationId="call_user_agora",
     *      tags={"agoraCall"},
     *      security={ {"sanctum": {} }},
     *      description="create agora token for consultation call",
     *      @OA\Parameter(  name="id", in="path", description="consultation id ", required=true),
     *      @OA\Response(
     *          response=200,
     *          description="token generated successfuly",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response( response=500,description="internal server error", @OA\JsonContent()),
     *      @OA\Response( response=401,description="unauthenticated", @OA\JsonContent())
     *     )
     */
    public function callUser($id)
    {
        try
        {
            $user =request()->user()->tokenCan('role:doctor') ? Consultation::findOrFail($id)?->patient->id : Consultation::findOrFail($id)?->doctor?->id;
            $data['userToCall'] = $user;
            $data['channelName'] ='consultation_'.$id;
            $data['from'] = request()->user()->id;

            broadcast(new MakeAgoraCall($data))->toOthers();
            return $this->api->success()
            ->message('The audio/video call is now streaming')
            ->send();
        } catch (Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }
}
