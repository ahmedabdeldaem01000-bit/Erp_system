<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
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
            'employee_id'   => 'required|exists:employees,id',
            'month'         => 'sometimes|integer|between:1,12',
            'year'          => 'sometimes|integer',
            'basic_salary'  => 'sometimes|numeric|min:0',
            'allowances'    => 'sometimes|numeric|min:0',
            'deductions'    => 'sometimes|numeric|min:0',
            'bonus'         => 'sometimes|numeric|min:0',
            'net_salary'    => 'sometimes|numeric|min:0',
            'status'        => 'sometimes|in:paid,unpaid,pending',
            'paid_at'       => 'sometimes|date',
        ];
    }
}
