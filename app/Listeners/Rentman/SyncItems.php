<?php

namespace App\Listeners\Rentman;

use App\Events\Rentman\WebhookReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncItems implements ShouldQueue // make ot async!
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
    public function handle(WebhookReceived $event): void
    {
        // TODO: implement
        /**
         * The webhookcall object that is connected to the event contains
         * all the info needed:
         * + what endpoint to be called
         * + the item(s) that is infected
         * + the action that needs to be processed (created, updated, deleted)
         *
         */
        Log::info("Listeren: " . $event->webhookcall);
    }
}
