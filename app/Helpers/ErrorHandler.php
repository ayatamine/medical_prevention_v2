<?php
namespace App\Helpers;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;


if (!function_exists('handleTwoCommunErrors')) {
    function handleTwoCommunErrors($ex,$not_found_message=null)
    {
        if ($ex instanceof ModelNotFoundException) {
            return (new ApiResponse())->failed()->code(404)
                        ->message($not_found_message)
                        ->send();

        }
        return (new ApiResponse())->failed()->code(500)
                            ->message($ex->getMessage())
                            ->send();
    }
}