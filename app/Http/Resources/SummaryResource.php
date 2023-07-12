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
        return [
            "id"=>$this['summary']->id,
            "description"=>$this['summary']->description,
            "sick_leave"=>(bool)$this['summary']->sick_leave,
            "lab_tests"=>$this['summary']->labTests,
            "other_lab_tests"=>$this['summary']->other_lab_tests,
            "notes"=>$this['summary']->notes,
            "prescriptions"=>$this['summary']->prescriptions,
            "created_at"=>$this['summary']->created_at,
            "feedback"=>$this['review']

        ];
    }
}
