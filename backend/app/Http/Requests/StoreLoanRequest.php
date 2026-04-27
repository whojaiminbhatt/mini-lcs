<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
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
            'loan_no' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'address' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'emi_amount' => 'required|numeric|min:0',
        ];
    }
}
