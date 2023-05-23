<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Helpers\ApiResponse;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Neomed Api",
 *   @OA\License(name="MIT"),
 *   @OA\Attachable()
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected ApiResponse $api ;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->api = $apiResponse;
    }
}
