<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleMedicalRecordResource extends JsonResource
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
            'full_name'=>$this->full_name,
            'birth_date'=> $this->birth_date,
            'thumbnail'=> $this->thumbnail,
            'age'=> $this->age,
            'gender'=> $this->gender,
            'height'=> $this->height,
            'weight'=> $this->weight,
            'allergies'=>$this->allergies->map(function($item){
                return [
                   'id'=>$item->id,
                   'name'=>$item->name,
                   'name_ar'=>$item->name_ar,
                   'icon'=>$item->icon
                ];
           }),
            'chronic_diseases'=>$this->chronic_diseases->map(function($item){
                 return [
                    'id'=>$item->id,
                    'name'=>$item->name,
                    'name_ar'=>$item->name_ar,
                    'icon'=>$item->icon
                 ];
            }),
            'family_histories'=>$this->family_histories->map(function($item){
                return [
                   'id'=>$item->id,
                   'name'=>$item->name,
                   'name_ar'=>$item->name_ar,
                ];
           }),

            'activities'=>[
                "physical_activity"=>(bool)$this->has_physical_activity,
                "cancer_screening"=>(bool)$this->has_cancer_screening,
                "depression_screening"=>(bool)$this->has_depression_screening,

            ]
        ];
    }
}
