<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationWithoutChatResource extends JsonResource
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
            'doctor' =>[
                "id"=> $this->doctor?->id,
                "full_name"=>$this->doctor?->full_name,
                "phone_number" => $this->doctor?->phone_number,
                "email" =>$this->doctor?->email,
                "thumbnail"=> $this->doctor?->thumbnail,
                "online_status"=> $this->doctor?->online_status,
                "gender" => $this->doctor?->gender,
                "bio"=>$this->doctor?->last_online_at,
                "last_online_at" =>$this->doctor?->last_online_at,
                "speciality_id"=>$this->doctor?->speciality->name
            ],
            'patient' =>[
                "id"=> $this->id,
                "full_name"=>$this->full_name,
                "phone_number" => $this->phone_number,
                "thumbnail"=> $this->doctor?->thumbnail,
            ],
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,
            'notes'=>$this->notes,
            'status'=>$this->status,
            'finished_at'=>$this->finished_at || null,
        ];
    }
}
