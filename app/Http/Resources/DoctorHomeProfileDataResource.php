<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorHomeProfileDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'full_name'=>$this->full_name,
            'online_status'=>$this->online_status,
            'thumbnail'=>$this->thumbnail,
            'notification_status'=>$this->notification_status,
            'ballance'=>$this->ballance,
            'notifications_count'=>3,
            'pending_requests'=>[
                (object)[
                    'id'=>254,
                    'patient'=>(object)[
                        'id'=>1,
                        'full_name'=>'Mohamed Aminh',
                        'thumbnail'=>public_path('storage/doctors/doctor.png'),
                        'address'=>'Saudi Arabia',
                        'gender'=>'male',
                        'age'=>33
                    ]
                ]
            ],
            'incomplete_requests'=>[
                (object)[
                    'id'=>63,
                    'patient'=>(object)[
                        'id'=>1,
                        'full_name'=>'Saad Farah',
                        'thumbnail'=>public_path('storage/doctors/doctor.png'),
                        'address'=>'Saudi Arabia',
                        'gender'=>'male',
                        'age'=>74
                    ]
                ]
            ],
            'completed_requests'=>[
                (object)[
                    'id'=>23,
                    'patient'=>(object)[
                        'id'=>1,
                        'full_name'=>'akram sol',
                        'thumbnail'=>public_path('storage/doctors/doctor.png'),
                        'address'=>'Saudi Arabia',
                        'gender'=>'male',
                        'age'=>55
                    ]
                ]
            ]
        ];
    }
}
