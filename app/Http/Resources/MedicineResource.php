<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'registeration_number'=>$this->registeration_number,
            'id'=>$this->id,
            'scientific_name'=>$this->scientific_name,
            'dose'=>$this->dose,
            'dose_unit'=>$this->dose_unit,
            'pharmaceutical_form'=>$this->pharmaceutical_form,
            'route'=>$this->route,
            'prescription_method'=>$this->prescription_method,
            // 'registeration_year'=>$this->registeration_number,
           
            'branch'=>$this->registeration_number,
            'target'=>$this->target,
            'type'=>$this->type,
            'commercial_name'=>$this->commercial_name,
           
            'code1'=>$this->registeration_number,
            // 'code2'=>$this->registeration_number,
            'size'=>$this->size,
            'size_unit'=>$this->size_unit,
            // 'package_type'=>$this->registeration_number,
            // 'package_size'=>$this->registeration_number,
            // 'control'=>$this->registeration_number,
            // 'marketing_company_name'=>$this->registeration_number,
            // 'representative'=>$this->registeration_number,
        ];
    }
}
