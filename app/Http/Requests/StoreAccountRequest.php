<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:accounts,code'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'type' => [
                'required',
                'in:asset,liability,equity,revenue,expense'
            ],

            'parent_id' => [
                'nullable',
                'exists:accounts,id'
            ],

            'is_postable' => [
                'boolean'
            ]
        ];
    }

}
