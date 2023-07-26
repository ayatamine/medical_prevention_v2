<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialityResource extends JsonResource
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
            'name'=>$this->name,
            'name_ar'=>$this->name_ar,
            // 'sub_specialities'=> $this->sub_specialities->map(function($sub){
            //     return [
            //         'id'=>$sub->id,
            //         'name'=>$sub->name,
            //         'name_ar'=>$sub->name_ar
            //     ];
            // }),   
        ];
    }
}
