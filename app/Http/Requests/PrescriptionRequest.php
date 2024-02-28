<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return request()->user()->tokenCan('role:doctor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'valid_until'=>['required','date'],
            'drugs_ids' =>['sometimes','nullable','array','required_without:drugs_data'],
            'drugs_data'=>['sometimes','nullable','array','required_without:drugs_ids'],
        ];
    }
}
