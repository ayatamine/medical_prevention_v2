<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this['consult']->patient?->id,
            'full_name'=>$this['consult']->patient?->full_name,
            'birth_date'=> $this['consult']->patient?->birth_date,
            'thumbnail'=> $this['consult']->patient?->thumbnail,
            'age'=> $this['consult']->patient?->age,
            'gender'=> $this['consult']->patient?->gender,
            'height'=> $this['consult']->patient?->height,
            'wheight'=> $this['consult']->patient?->weight,
            'allergies'=>$this['consult']->patient?->allergies,
            'chronic_diseases'=>$this['consult']->patient?->chronic_diseases,
            'family_histories'=>$this['consult']->patient?->family_histories,

            'activities'=>[
                "physical_activity"=>(bool)$this['consult']->patient?->has_physical_activity,
                "cancer_screening"=>(bool)$this['consult']->patient?->has_cancer_screening,
                "depression_screening"=>(bool)$this['consult']->patient?->has_depression_screening,

            ],
            'anexiety_scale'=>$this['anexiety_scale'],
            'depression_scale'=>$this['depression_scale'],
            //TODO: get previous summary
            'previous_summaries'=>$this['consult']->patient?->previousSummaries()->map(function($item){
                return [
                    'consultation_id'=>$item->consultation->id,
                    'doctor_name'=>$item->consultation->doctor->full_name,
                    'completed_at'=>$item->consultation->finished_at,
                    'thumbnail'=>$item->consultation->doctor->thumbnail,
                ];
            })
        ];
    }
}
