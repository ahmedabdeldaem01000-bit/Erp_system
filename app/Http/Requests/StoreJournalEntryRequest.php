<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreJournalEntryRequest extends FormRequest
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
            'entry_date' => ['required', 'date'],

            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'lines' => [
                'required',
                'array',
                'min:2'
            ],

            'lines.*.account_id' => [
                'required',
                'exists:accounts,id'
            ],

            'lines.*.debit' => [
                'required',
                'numeric',
                'min:0'
            ],

            'lines.*.credit' => [
                'required',
                'numeric',
                'min:0'
            ],

            'lines.*.description' => [
                'nullable',
                'string'
            ],
        ];
    }
}
