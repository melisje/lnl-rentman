<?php

namespace App\Jobs;

use App\Events\Rentman\WebhookReceived;
use App\Models\Rentman\ApiToken;
use App\Models\Rentman\Crew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\Rentman\WebhookCall;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Support\Facades\Http;

class ProcessWebhookRentman implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     * 1. Store the webhook call in the database
     * 2. Notify listeners for the received events
     */
    public function handle(RentmanApiService $rentmanApiService): void
    {
        // Create a new WebhookCall instance
        $whc = new WebhookCall;

        // Explode received data array and store it in the model
        $whc->headers = $this->data['headers'];
        $whc->payload = $this->data['rawcontent'];
        $whc->ip = $this->data['ip'];

        Log::info('Processing webhook payload:', [$whc->payload]);

        $payload = $this->data['payload'];
        $whc->account = $payload['account'];
        $whc->eventType = $payload['eventType'] ?? 'unknown';
        $whc->itemType = $payload['itemType'] ?? 'unknown';
        $whc->items = json_encode($payload['items']);
        $whc->eventDate = $payload['eventDate'];

        // Make sure user exists in crew table from given account
        // $whc->user = $payload['user'] ? $payload['user']['id'] : null;
        $user = $payload['user'];
        $whc->user = $user ? $user['id'] : null;

        $rentmanApiService->sync_crew_user($whc->account,$user);

        // save model to db
        $whc->save();

        // TODO: process - inform interested listeners about new webhook call
        /**
         * We implement the observer/listener patern.
         * This means that we reveiving a webcall fires an event (the observer).
         * Then we can implment listeners that are activated when the event
         * happens.
         * This allows us to decouple the implementation of specific actions from
         * the functionality of recieving the wehbookcall.
         */
        WebhookReceived::dispatch($whc); // Fire event, the observer !
    }


}