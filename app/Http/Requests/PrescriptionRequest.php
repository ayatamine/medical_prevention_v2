<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'prescription_title'=>'string|required|max:250',
            'drug_name'=>'string|required|max:250',
            'route'=>'string|required|max:100',
            'dose'=>'integer|required',
            'unit'=>'string|required|max:150',
            'frequancy'=>'string|required|max:150',
            'duration'=>'integer|required',
            'duration_unit'=>'string|required|max:100',
            'shape'=>'sometimes|nullable|string|required|max:150',
            'prn'=>'sometimes|nullable|boolean',
            'instructions'=>'sometimes|nullable|string|required',
            // 'doctor_id'=>'integer|required|exists:doctors,id'
        ];
    }
}
