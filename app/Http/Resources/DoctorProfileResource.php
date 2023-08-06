<?php

namespace App\Http\Resources;

use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorProfileResource extends JsonResource
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
            $speciality =null;
        //  if($this->sub_specialities && count($this->sub_specialities)) {
        //     $speciality = Speciality::whereHas('sub_specialities',function($query){
        //          $query->whereIn('id',array($this->sub_specialities[0]->id));
        //     })->first();
        //  }
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "online_status" => true,
            "reviews_count" => $reviews_count,
            "rating_value" => $reviews_count >0  ? $sum / $reviews_count : intval($sum) ,
            "location" => $this->location ,
            "thumbnail" => $this->thumbnail ,
            "speciality"=>$this->speciality?->name,
            // "specialities" => $this->specialities->map(function($sub){
            //     return [
            //         'id'=>$sub->id,
            //         'name'=>$sub->name,
            //         'name_ar'=>$sub->name_ar
            //     ];
            // }),
            "sub_specialities"=>$this->sub_specialities->map(function($sp){
                return [
                    'id'=>$sp->id,
                    'name'=>$sp->name,
                    'name_ar'=>$sp->name_ar,
                    'slug'=>$sp->slug,
                ];
            }),
            "bio"=>$this->bio,
            "reviews"=>$this->reviews->map(function ($review){
                return [
                    'id'=>$review->id,
                    'patient'=>[
                        'full_name'=>$review->consultation->patient->full_name,
                        'thumbnail'=>$review->consultation->patient->thumbnail,
                    ],
                    'rating'=>$review->rating,
                    'comment'=>$review->comment,
                ]; 
            })

        ];
    }
}
