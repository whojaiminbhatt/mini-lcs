<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Loan;
use App\Models\User;
use App\Events\LoanCreated;
use Illuminate\Support\Facades\Log;

use Illuminate\Pagination\LengthAwarePaginator;

class LoanService
{
    public function getLoans(array $filters): LengthAwarePaginator
    {
        try {
            Log::info('Fetching loans with filters', $filters);
            
            $query = Loan::query();

            if (!empty($filters['loan_no'])) {
                $query->where('loan_no', 'like', '%' . $filters['loan_no'] . '%');
            }

            if (!empty($filters['customer_name'])) {
                $query->where('customer_name', 'like', '%' . $filters['customer_name'] . '%');
            }

            return $query->paginate(10);
        } catch (\Exception $e) {
            Log::error('Exception in LoanService@getLoans', [
                'filters' => $filters,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function createLoan(array $data, User $user): Loan
    {
        try {
            Log::info('Processing loan entry', ['loan_no' => $data['loan_no']]);

            // Using updateOrCreate makes this function reusable for both creating and updating
            $loan = Loan::updateOrCreate(
                ['loan_no' => $data['loan_no']],
                [
                    'customer_name' => $data['customer_name'],
                    'mobile' => $data['mobile'],
                    'address' => $data['address'],
                    'total_amount' => $data['total_amount'],
                    'emi_amount' => $data['emi_amount'],
                ]
            );

            // Only trigger LoanCreated event if the loan was actually recently created
            if ($loan->wasRecentlyCreated) {
                event(new LoanCreated($loan, $user));
                Log::info('Loan created successfully', ['loan_id' => $loan->id]);
            } else {
                Log::info('Loan updated successfully', ['loan_id' => $loan->id]);
            }

            return $loan;
        } catch (\Exception $e) {
            Log::error('Exception in LoanService@createLoan', [
                'loan_no' => $data['loan_no'] ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getLoanById(int $id)
    {
        try {
            Log::info('Fetching loan by ID', ['id' => $id]);
            return Loan::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Exception in LoanService@getLoanById', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
