<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChronicDiseaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "category_name"=>$this->name,
            "category_nam_ar"=>$this->name_ar,
            "chronic_diseases"=>$this->chronicDiseases->map(function($cd){
                return [
                    "name"=>$cd->name,
                    "name_ar"=>$cd->name_ar,
                    "icon"=>$cd->icon,
                ];
            })
        ];
    }
}
