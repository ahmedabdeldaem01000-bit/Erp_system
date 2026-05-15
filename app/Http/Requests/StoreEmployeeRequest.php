<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
           return [
            'name' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',

            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'phone' => 'required|string|max:20|unique:employees,phone',

            'gender' => 'required|in:male,female',

            'hire_date' => 'required|date',

            'email' => 'required|email|unique:employees,email',

            'password' => 'required|min:8',

            'department_id' => 'required|exists:departments,id',
        ];
    }
}
