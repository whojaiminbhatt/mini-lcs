<?php

namespace App\Listeners;

use App\Events\LoanCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\LoanCreatedMail;

class SendLoanCreatedEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoanCreated $event): void
    {
        Mail::to($event->user->email)->send(new LoanCreatedMail($event->loan));
        
        // Invalidate dashboard caches
        Cache::forget('dashboard_metrics');
        Cache::forget('best_collection_time');
    }
}
