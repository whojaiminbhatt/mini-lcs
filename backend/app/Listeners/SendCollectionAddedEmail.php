<?php

namespace App\Listeners;

use App\Events\CollectionAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\CollectionAddedMail;

class SendCollectionAddedEmail implements ShouldQueue
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
    public function handle(CollectionAdded $event): void
    {
        Mail::to($event->user->email)->send(new CollectionAddedMail($event->collection));
        
        // Invalidate dashboard caches
        Cache::forget('dashboard_metrics');
        Cache::forget('best_collection_time');
    }
}
