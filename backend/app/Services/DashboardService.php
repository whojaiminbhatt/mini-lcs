<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Loan;
use App\Models\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardService
{
    public function getMetrics(): array
    {
        try {
            Log::info('Fetching dashboard metrics');

            return Cache::rememberForever('dashboard_metrics', function () {
                $totalLoansCount = Loan::count();
                $totalCollectedToday = Collection::whereDate('collected_at', Carbon::today())->sum('amount_paid');
                
                $totalLoanAmount = Loan::sum('total_amount');
                $totalCollectedAmount = Collection::sum('amount_paid');
                $pendingAmount = max(0, $totalLoanAmount - $totalCollectedAmount);

                $collectionByMode = Collection::selectRaw('payment_mode, SUM(amount_paid) as total')
                    ->groupBy('payment_mode')
                    ->get();

                return [
                    'total_loans' => $totalLoansCount,
                    'total_collected_today' => $totalCollectedToday,
                    'pending_amount' => $pendingAmount,
                    'collection_by_mode' => $collectionByMode,
                ];
            });
        } catch (\Exception $e) {
            Log::error('Exception in DashboardService@getMetrics', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getBestCollectionTime(): ?array
    {
        try {
            Log::info('Calculating best collection time');

            return Cache::rememberForever('best_collection_time', function () {
                $collections = Collection::all();
                if ($collections->isEmpty()) {
                    return null;
                }

                $slots = [];
                foreach ($collections as $collection) {
                    $hour = $collection->collected_at->hour;
                    $bucketStart = floor($hour / 2) * 2;
                    $slotName = sprintf('%02d:00 - %02d:00', $bucketStart, $bucketStart + 2);

                    if (!isset($slots[$slotName])) {
                        $slots[$slotName] = 0;
                    }
                    $slots[$slotName] += $collection->amount_paid;
                }

                arsort($slots);
                $best = array_key_first($slots);
                
                return [
                    'best_slot' => $best,
                    'amount_collected' => $slots[$best],
                    'all_slots' => $slots
                ];
            });
        } catch (\Exception $e) {
            Log::error('Exception in DashboardService@getBestCollectionTime', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
