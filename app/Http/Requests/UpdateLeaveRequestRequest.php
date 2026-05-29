<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Implement your authorization logic here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['sometimes', 'numeric', 'exists:employees,id'],
            'leave_type_id' => ['sometimes', 'numeric', 'exists:leave_types,id'],
            'start_date' => ['sometimes', 'date', 'after_or_equal:today'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'reason' => ['sometimes', 'string', 'min:10', 'max:1000'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_id.exists' => 'The selected employee does not exist.',
            'leave_type_id.exists' => 'The selected leave type does not exist.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'Start date must be today or in the future.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'reason.min' => 'Reason must be at least 10 characters.',
            'reason.max' => 'Reason cannot exceed 1000 characters.',
        ];
    }
}
