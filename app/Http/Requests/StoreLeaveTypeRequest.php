<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreLeaveTypeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:leave_types,name'],
            'days_per_year' => ['required', 'numeric', 'min:0', 'max:365'],
            'is_paid' => ['required', 'boolean'],
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
            'name.required' => 'Leave type name is required.',
            'name.unique' => 'This leave type already exists.',
            'days_per_year.required' => 'Days per year is required.',
            'days_per_year.numeric' => 'Days per year must be a number.',
            'days_per_year.min' => 'Days per year cannot be negative.',
            'is_paid.required' => 'Please specify if this leave is paid.',
        ];
    }
}
