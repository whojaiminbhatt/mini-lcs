<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

use App\Models\Loan;

class ValidCollectionAmount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $loanNo = request()->input('loan_no');
        if (!$loanNo) {
            return; // the existing 'required' rule will catch this
        }

        $loan = Loan::where('loan_no', $loanNo)->first();
        if (!$loan) {
            return; // the existing 'exists' rule will catch this
        }

        $collectedAmount = $loan->collections()->sum('amount_paid');
        $pendingAmount = max(0, $loan->total_amount - $collectedAmount);

        if ($value > $pendingAmount) {
            $fail("Amount paid cannot exceed pending amount ({$pendingAmount}).");
        }
    }
}
