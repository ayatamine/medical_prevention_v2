<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorAvailabilityRequest extends FormRequest
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
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|integer|between:1,7', // 1 (Sunday) - 7 (Saturday)
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
            'availabilities.*.is_pm' => 'sometimes|nullable|boolean',
        ];
    }
}
