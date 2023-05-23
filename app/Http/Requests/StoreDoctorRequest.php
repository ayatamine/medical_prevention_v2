<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
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
            'full_name' => 'string|max:150',
            'id_number' => 'string|max:150|unique:doctors',
            'birth_date' => 'string|max:150',
            'phone_number' => 'required|max:255|unique:doctors',
            'email' => 'required|email|max:255|unique:doctors',
            'job_title' => 'string|max:150',
            'classification_number' => 'string|max:150|unique:doctors',
            'insurance_number' => 'string|max:150|unique:doctors',
            'sub_specialities'=>'sometimes|nullable|string',
            'medical_licence_file' => ['file', 'max:3000'],   
            'cv_file' =>  ['file', 'max:3000'],   
            'certification_file' =>  ['file', 'max:3000'],   
        ];
    }
}
