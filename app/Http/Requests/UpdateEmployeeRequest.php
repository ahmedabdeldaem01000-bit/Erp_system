<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
             $employeeId = $this->route('id');

        return [

            'name' => 'required|string|max:255',

            'salary' => 'required|numeric|min:0',

            'address' => 'required|string|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'phone' => 'required|string|max:20|unique:employees,phone,' . $employeeId,

            'gender' => 'required|in:male,female',

            'hire_date' => 'required|date',

            'email' => 'required|email|unique:employees,email,' . $employeeId,

            'department_id' => 'required|exists:departments,id',
        ];
    }
}
