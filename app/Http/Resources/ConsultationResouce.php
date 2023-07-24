<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResouce extends JsonResource
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
            'doctor' => $this->doctor,
            'patient' => $this->patient,
            'chat_messages'=>$this->chatMessages->map(function($item){
                $attr=[
                    "id"=>$item->id,
                    "consultation_id"=>$item->consultation_id,
                    "sender_type"=>$item->sender_type,
                    "sender_id"=>$item->sender_id,
                    "receiver_type"=>$item->receiver_type,
                    "receiver_id"=>$item->receiver_id,
                    "content"=>$item->content,
                    "attachement"=>$item->attachement,
                    "created_at"=>$item->created_at
                ];
                if($item->attachement) $attr['attachement_type'] = $item->attachement_type;
                return $attr;
            })
        ];
    }
}
