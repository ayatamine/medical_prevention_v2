<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleNotificationResource extends JsonResource
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
            "type"=>$this->type,
            "notifiable_type"=>$this->notifiable_type,
            "notifiable_id"=>$this->notifiable_id,
            "message" =>$this->data['message'],
            "consultation_id"=>$this->data['consultation_id'],
            "read"=> $this->read_at ? true: false ,
            "created_at" =>date('d-m-Y',strtotime($this->created_at)),
        ];
    }
}
