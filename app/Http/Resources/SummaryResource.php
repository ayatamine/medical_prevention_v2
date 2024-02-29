<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lab_tests = $this['summary']?->labTests->map(function($item){
            return[
                'name'=>$item->name,
                'name_ar'=>$item->name_ar,
            ];
        });
        if($this['summary']->other_lab_tests)
        {
            $lab_tests[] =
            [
                'name'=>$this['summary']->other_lab_tests,
                'name_ar'=>$this['summary']->other_lab_tests
            ];
        }
        return [
            "id"=>$this['summary']->id,
            // "symptomes"=>$this['summary']->symptomes?->makeHidden('pivot'),
            "description"=>$this['summary']->description,
            "sick_leave"=>(bool)$this['summary']->sick_leave,
            "lab_tests"=>$lab_tests,
            "prescription"=>[
                'date'=>date('d-m-Y',strtotime($this->created_at)),
                'doctor_name'=>$this['doctor']?->full_name,
                // 'doctor_thumbnail'=>$this['doctor']->thumbnail,
                'patient_name'=>$this['patient']->full_name,
                // 'patient_thumbnail'=>$this['patient']->thumbnail,
                'drugs'=>$this->drugs->map(function ($drug) {
                    return new DrugResource($drug);
                })
            ],
            // "other_lab_tests"=>$this['summary']->other_lab_tests,
            "notes"=>$this['summary']->notes,
            // "created_at"=>$this['summary']->created_at,
            "feedback"=>
            [
                'consultation_id'=>$this['review']?->consultation_id,
                'rating'=>$this['review']?->rating,
                'comment'=>$this['review']?->comment,
            ]

        ];
    }
}

