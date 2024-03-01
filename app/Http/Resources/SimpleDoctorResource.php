<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleDoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sum = $this->reviews_sum_rating;
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "online_status" => true,
            "reviews_count" => $this->reviews_count,
            "rating_value" => $this->reviews_count >0  ? floatval($sum / $this->reviews_count) : floatval($sum) ,
            "location" => $this->location ,
            "thumbnail" => $this->thumbnail ,
        ];
    }

}
