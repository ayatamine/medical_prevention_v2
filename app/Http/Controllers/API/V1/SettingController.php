<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
         /**
     * @OA\Get(
     *      path="/api/v1/app-settings",
     *      operationId="get_app_settings",
     *      tags={"patientApp"},
     *      security={ {"sanctum": {} }},
     *      description="get app settings",
     *      @OA\Response(
     *          response=200,
     *          description="the settings fetched successfully , wait for response",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function index()
    {
        try{
                return $this->api->success()
                    ->payload(Setting::select('app_name','app_logo','app_slogon','app_description','email','whatsapp_number','customer_service_number')->first())
                    ->message('the settings fetched successfully')
                    ->send();
        }
        catch (\Exception $ex) {
            return $this->api->failed()
                ->message($ex->getMessage())
                ->send();
        }
    }
}
