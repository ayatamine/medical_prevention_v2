<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'registeration_number',
            'registeration_year',
            'target',
            'type',
            'branch',
            'scientific_name',
            'commercial_name',
            'dose',
            'dose_unit',
            'pharmaceutical_form',
            'route',
            'code1',
            'code2',
            'size',
            'size_unit',
            'package_type',
            'package_size',
            'prescription_method',
            'control',
            'marketing_company_name',
            'representative',
        ]
    }
}
