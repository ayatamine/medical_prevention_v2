<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorProfileDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //TODO: notification count
        return [
            'id' =>$this->id,
            'full_name'=>$this->full_name,
            'online_status'=>$this->online_status,
            'thumbnail'=>$this->thumbnail,
            'notification_status'=>$this->notification_status,
            'ballance'=>$this->ballance,
            'notifications_count'=>3,
        ];
    }
}
