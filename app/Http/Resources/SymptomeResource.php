<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SymptomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "category_name"=>$this->name,
            "category_name_ar"=>$this->name_ar,
            "symptomes"=>$this->symptomes->map(function($s){
                return [
                    "id"=>$s->id,
                    "name"=>$s->name,
                    "name_ar"=>$s->name_ar,
                    "icon"=>$s->icon,
                ];
            })
        ];
    }
}
