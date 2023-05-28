<?php

namespace App\Http\Controllers\API\V1;

use Exception;
use App\Models\Page;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PageController extends Controller
{

    public function __construct(ApiResponse $apiResponse)
    {
        parent::__construct($apiResponse);
    }
      /**
       * @OA\Get(
        * path="/api/v1/pages/{title}",
        * operationId="getPageByTitle",
        * tags={"pages"},
        * summary="get page details by title ",
        * description="get page details by title  ",
        *      @OA\Parameter( name="title",in="path",description="the page title",required=true),
        *      @OA\Response( response=200, description="page fetched succefully", @OA\JsonContent() ),
        *      @OA\Response( response=404, description="no page found with the given title", @OA\JsonContent() ),
        *    )
        */
        public function getPage($title){
             try {
                $page = Page::with('language:id,name')
                            ->where('title','like',"%$title%")
                            ->orWhere('slug','like',"%$title%")
                            ->whereLanguageId(1)
                            ->firstOrfail();
                
                 return $this->api->success()
                        ->message('Page fetched successfully')
                        ->payload((new PageResource($page))->resolve())
                        ->send();
                }
              catch(Exception $ex){
                if ($ex instanceof ModelNotFoundException) {
                    return $this->api->failed()->code(404)
                                ->message("no page found with the given title")
                                ->send();
        
                }
                return $this->api->failed()->code(500)
                                    ->message($ex->getMessage())
                                    ->send();
            }
                        
        }
      
}
