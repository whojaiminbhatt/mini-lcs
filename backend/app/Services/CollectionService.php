<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Collection;
use App\Models\Loan;
use App\Models\User;
use App\Events\CollectionAdded;
use Illuminate\Support\Facades\Log;
use Exception;

class CollectionService
{
    public function createCollection(array $data, User $user): Collection
    {
        try {
            Log::info('Processing collection entry', ['loan_no' => $data['loan_no'], 'amount' => $data['amount_paid']]);

            $loan = Loan::where('loan_no', $data['loan_no'])->firstOrFail();

            $collection = Collection::create([
                'loan_id' => $loan->id,
                'amount_paid' => $data['amount_paid'],
                'payment_mode' => $data['payment_mode'],
                'location' => $data['location'] ?? null,
                'collected_at' => $data['collected_at'],
                'collected_by' => $user->id,
            ]);

            event(new CollectionAdded($collection, $user));

            Log::info('Collection added successfully', ['collection_id' => $collection->id]);

            return $collection;
        } catch (\Exception $e) {
            Log::error('Exception in CollectionService@createCollection', [
                'loan_no' => $data['loan_no'] ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
