<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteDoctorsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $sum = $this->reviews_sum_rating;
        $reviews_count = count($this->reviews);

        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "online_status" => true,
            "reviews_count" => $reviews_count,
            "rating_value" => $reviews_count >0  ? $sum / $reviews_count : intval($sum) ,
            "location" => $this->location ,
            "thumbnail" => $this->thumbnail ,
        ];
    }
}
