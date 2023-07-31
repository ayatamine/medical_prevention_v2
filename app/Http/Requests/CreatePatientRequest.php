<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'nullable|string|max:300',
                'birth_date' => 'nullable|date',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'age' => 'nullable|integer|between:1,110',
                'gender' => 'nullable|string|in:male,female',
                'allergies'=>'sometimes|nullable',
                'chronic_diseases'=>'sometimes|nullable',
                'family_histories'=>'sometimes|nullable',
                'has_physical_activity' => 'boolean|in:0,1',
                'has_cancer_screening' => 'boolean|in:0,1',
                'has_depression_screening' => 'boolean|in:0,1',
                'little_interest_doing_things' => 'boolean|in:0,1',
                'feeling_down_or_depressed' => 'boolean|in:0,1',
                // 'other_problems' => 'nullable|json', 
        ];
    }
}
