<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\Rentman\WebhookCall;

class ProcessWebhookRentman implements ShouldQueue
{
    use Queueable;

    public array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Webhok ontvangen:', $this->payload);

        $eventype = $this->payload['eventType'] ?? 'unknown';
        $itemtype = $this->payload['itemType'] ?? 'unknown';
        $items = $this->payload['items'];
        $eventdate = $this->payload['eventDate'];


        $wbc = new WebhookCall;
        $wbc->account = $this->payload['account'] ?? 'unknown';
        $wbc->user = $this->payload['user']['id'] ?? 'unknown';
        $wbc->eventType = $this->payload['eventType'] ?? 'unknown';
        $wbc->itemType = $this->payload['itemType'] ?? 'unknown';
        $wbc->items = json_encode($this->payload['items']);
        $wbc->eventDate = $this->payload['eventDate'];

        $wbc->save();

    }
}
