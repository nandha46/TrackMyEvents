<?php

namespace App\Listeners;

use App\Events\NewEventAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SeeIfEventAdded
{
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
    public function handle(NewEventAdded $event): void
    {
        Log::info("New event added from listener: ".$event->event->toJson());
    }
}
