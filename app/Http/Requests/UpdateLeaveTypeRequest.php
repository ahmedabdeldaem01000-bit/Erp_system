<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveTypeRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255', 'unique:leave_types,name,' . $this->route('leave_type')],
            'days_per_year' => ['sometimes', 'numeric', 'min:0', 'max:365'],
            'is_paid' => ['sometimes', 'boolean'],
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
            'name.string' => 'Leave type name must be a string.',
            'name.max' => 'Leave type name cannot exceed 255 characters.',
            'name.unique' => 'This leave type name already exists.',
            'days_per_year.numeric' => 'Days per year must be a number.',
            'days_per_year.min' => 'Days per year cannot be negative.',
            'is_paid.boolean' => 'Is paid must be true or false.',
        ];
    }
}
