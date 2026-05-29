<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'required|numeric|min:0',
            'deductions' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'status' => 'required|in:paid,unpaid,pending',
            'paid_at' => 'required|date',
        ];
    }
}
