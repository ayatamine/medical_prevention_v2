<?php

namespace App\Http\Resources;

use App\Models\PatientScale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientScaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'scale_question'=> $this->scaleQuestion,
            'answer'=>$this->answer,
            'answer_as_text'=>$this->answerAsText($this->answer),
            'answer_as_text_ar'=>$this->answerAsArabicText($this->answer),
        ];
    }
}
