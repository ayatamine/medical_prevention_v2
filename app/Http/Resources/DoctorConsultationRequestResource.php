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
            'pending_requests'=>$this->pendingConsultations()->get()->map(function($r){
                return [    
                    'id'=>$r->id,
                    'patient'=>[
                        'id'=>$r->patient->id,
                        'full_name'=>$r->patient->full_name,
                        'thumbnail'=>$r->patient->thumbnail,
                        'address'=>$r->patient->address,
                        'gender'=>$r->patient->gender,
                        'age'=>$r->patient->age
                    ]
                ];
            }),
            'incompleted_requests'=>$this->incompletedConsultations()->get()->map(function($r){
                return [    
                    'id'=>$r->id,
                    'patient'=>[
                        'id'=>$r->patient->id,
                        'full_name'=>$r->patient->full_name,
                        'thumbnail'=>$r->patient->thumbnail,
                        'address'=>$r->patient->address,
                        'gender'=>$r->patient->gender,
                        'age'=>$r->patient->age
                    ]
                ]; 
            }),
            'completed_requests'=>$this->completedConsultations()->get()->map(function($r){
                return [    
                    'id'=>$r->id,
                    'patient'=>[
                        'id'=>$r->patient->id,
                        'full_name'=>$r->patient->full_name,
                        'thumbnail'=>$r->patient->thumbnail,
                        'address'=>$r->patient->address,
                        'gender'=>$r->patient->gender,
                        'age'=>$r->patient->age
                    ],
                    'completed_at'=>$r->finished_at
                ];
            })
        ];
    }
}
