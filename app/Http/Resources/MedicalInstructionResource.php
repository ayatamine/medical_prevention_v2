<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalInstructionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         //TODO: real path of image
        return [
            'id' =>$this->id,
            'title' =>$this->title,
            'title_ar' =>$this->title_ar,
            'image' =>'https://ui-avatars.com/api/?background=0D8ABC&color=fff',
            'content' =>$this->content,
            'content_ar' =>$this->content_ar,
        ];
    }
}
