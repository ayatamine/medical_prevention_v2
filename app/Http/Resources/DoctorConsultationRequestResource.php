<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorConsultationRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

                return [
                    'id'=>$this->id,
                    'patient'=>[
                        'id'=>$this->patient->id,
                        'full_name'=>$this->patient->full_name,
                        'thumbnail'=>$this->patient->thumbnail,
                        'address'=>$this->patient->address,
                        'gender'=>$this->patient->gender,
                        'age'=>$this->patient->age
                    ],
                    'created_at'=>$this->created_at
                ];
    }
}
