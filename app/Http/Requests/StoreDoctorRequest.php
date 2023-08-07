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
            'full_name' => 'sometimes|nullable|string|max:150',
            'id_number' => 'sometimes|nullable|integer|unique:doctors',
            'birth_date' => 'sometimes|nullable|string|max:150',
            'phone_number' => 'sometimes|nullable|max:255|unique:doctors',
            'email' => 'sometimes|nullable|email|max:255|unique:doctors',
            'job_title' => 'sometimes|nullable|string|max:150',
            'classification_number' => 'sometimes|nullable|integer|unique:doctors',
            'insurance_number' => 'sometimes|nullable|integer|unique:doctors',
            // 'specialities'=>'sometimes|nullable',
            'sub_specialities'=>'sometimes|nullable|array',
            'sub_specialities.*'=>'integer',
            'medical_licence_file' => ['sometimes','nullable','file', 'max:3000'],   
            'cv_file' =>  ['sometimes','nullable','file', 'max:3000'],   
            'certification_file' =>  ['sometimes','nullable','file', 'max:3000'],   
            'thumbnail' => ['sometimes','nullable', 'mimes:jpg,jpeg,png', 'max:3000'],
            'bio' => ['sometimes', 'nullable'],
            'speciality_id' => ['sometimes', 'nullable','integer'],
        ];
    }
}
