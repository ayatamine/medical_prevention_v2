<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
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
            'text' =>$this->text,
            'text_ar' =>$this->text_ar,
        ];
    }
}
