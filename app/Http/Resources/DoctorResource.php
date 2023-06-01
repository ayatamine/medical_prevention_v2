<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "id_number" => $this->id_number,
            "birth_date" => $this->birth_date,
            "phone_number" => $this->phone_number,
            "email" => $this->email,
            "job_title" => $this->job_title,
            "thumbnail" => $this->thumbnail ,
            "classification_number" => $this->classification_number,
            "insurance_number" => $this->insurance_number,
            "medical_licence_file" => $this->medical_licence_file,
            "certification_file" => $this->certification_file,
            "cv_file" => $this->cv_file,
            "notification_status" => $this->notification_status,
            "online_status" => $this->online_status,
            "gender" => $this->gender
        ];
    }
}
