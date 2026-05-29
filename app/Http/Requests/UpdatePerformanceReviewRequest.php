<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerformanceReviewRequest extends FormRequest
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
            'reviewer_id' => ['sometimes', 'numeric', 'exists:employees,id', 'different:employee_id'],
            'review_period' => ['sometimes', 'string', 'max:255'],
            'quality_of_work' => ['sometimes', 'numeric', 'min:1', 'max:10'],
            'productivity' => ['sometimes', 'numeric', 'min:1', 'max:10'],
            'communication' => ['sometimes', 'numeric', 'min:1', 'max:10'],
            'teamwork' => ['sometimes', 'numeric', 'min:1', 'max:10'],
            'leadership' => ['sometimes', 'numeric', 'min:1', 'max:10'],
            'strengths' => ['nullable', 'string', 'max:1000'],
            'areas_for_improvement' => ['nullable', 'string', 'max:1000'],
            'goals' => ['nullable', 'string', 'max:1000'],
            'comments' => ['nullable', 'string', 'max:2000'],
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
            'reviewer_id.exists' => 'The selected reviewer does not exist.',
            'reviewer_id.different' => 'Reviewer must be different from the employee.',
            'quality_of_work.min' => 'Quality of work rating must be at least 1.',
            'quality_of_work.max' => 'Quality of work rating cannot exceed 10.',
            'productivity.min' => 'Productivity rating must be at least 1.',
            'productivity.max' => 'Productivity rating cannot exceed 10.',
            'communication.min' => 'Communication rating must be at least 1.',
            'communication.max' => 'Communication rating cannot exceed 10.',
            'teamwork.min' => 'Teamwork rating must be at least 1.',
            'teamwork.max' => 'Teamwork rating cannot exceed 10.',
            'leadership.min' => 'Leadership rating must be at least 1.',
            'leadership.max' => 'Leadership rating cannot exceed 10.',
        ];
    }
}
