<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCollectionAmount;

class StoreCollectionRequest extends FormRequest
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
            'loan_no' => 'required|string|exists:loans,loan_no',
            'amount_paid' => ['required', 'numeric', 'min:1', new ValidCollectionAmount()],
            'payment_mode' => 'required|in:cash,upi,card',
            'location' => 'nullable|string',
            'collected_at' => 'required|date',
        ];
    }
}
