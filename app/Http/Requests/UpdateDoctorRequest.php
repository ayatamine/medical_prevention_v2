<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->tokenCan('doctor:update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'sometimes|nullablestring|max:150',
            'id_number' => 'sometimes|nullable|string|max:150|unique:doctors,classification_number,'.request()->user()->id,
            'birth_date' => 'sometimes|nullable|string|max:150',
            'email' => 'sometimes|nullable|email|max:255|unique:doctors,email,'.request()->user()->id,
            'job_title' => 'sometimes|nullable|string|max:150',
            'classification_number' => 'sometimes|nullable|string|max:150|unique:doctors,classification_number,'.request()->user()->id,
            'insurance_number' => 'sometimes|nullable|string|max:150|unique:doctors,insurance_number,'.request()->user()->id,
            'sub_specialities'=>'sometimes|nullable|nullable|string',
            'medical_licence_file' => ['file', 'max:3000'],   
            'cv_file' =>  ['sometimes','nullable','file', 'max:3000'],   
            'certification_file' =>  ['sometimes','nullable','file', 'max:3000'], 
        ];
    }
}
