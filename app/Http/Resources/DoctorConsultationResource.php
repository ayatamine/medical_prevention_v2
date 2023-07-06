<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorConsultationResource extends JsonResource
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
                        'age'=>$r->patient->gender
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
                        'age'=>$r->patient->gender
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
                        'age'=>$r->patient->gender
                    ],
                    'completed_at'=>$r->finished_at
                ];
            })
        ];
    }
}