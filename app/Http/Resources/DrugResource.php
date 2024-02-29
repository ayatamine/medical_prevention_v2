<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrugResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "drug_name"=>$this->drug_name,
            "route"=>$this->route,
            "dose"=>$this->dose,
            "frequancy"=>$this->frequancy,
            "unit"=>$this->unit,
            "duration"=>$this->duration,
            "duration_unit"=>$this->duration_unit,
            "shape"=>$this->shape,
            "prn"=>$this->prn,
            "instructions"=>$this->instructions,
        ];
    }
}
