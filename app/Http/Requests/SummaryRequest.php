<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SummaryRequest extends FormRequest
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
            'description'=>'string|required',
            'prescriptions'=>'sometimes|nullable|array',
            'prescriptions.*'=>'integer',
            'lab_tests'=>'sometimes|nullable|array',
            'lab_tests.*'=>'integer',
            'other_lab_tests'=>'nullable|string',
            'sick_leave'=>'sometimes|nullable|boolean',
            'notes'=>'sometimes|nullable|string'
        ];
    }
}
