<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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

    }
}
