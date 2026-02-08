<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\Rentman\WebhookCall;

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
    public function handle(): void
    {
        // Explode received data array
        $payload = $this->data['payload'];
        $headers = $this->data['headers'];
        $rawcontent = $this->data['rawcontent'];
        $ip = $this->data['ip'];

        Log::info('Processing webhook payload:', $payload);

        // Make sure user exists in crew table from given account
        $account = $payload['account'];
        $user = $payload['user']['id'];
        $this->sync_crew_user($account,$user);

        // store the call info in the database
        $wbc = new WebhookCall;
        // $wbc->payload = json_encode($payload);
        $wbc->payload = $rawcontent;
        $wbc->account = $account;
        $wbc->user = $user;
        $wbc->ip = $ip;
        $wbc->headers = $headers;
        $wbc->eventType = $payload['eventType'] ?? 'unknown';
        $wbc->itemType = $payload['itemType'] ?? 'unknown';
        $wbc->items = json_encode($payload['items']);
        $wbc->eventDate = $payload['eventDate'];
        $wbc->save();

        // TODO: process - inform interested listeners about new webhook call

    }

    /**
     * To call a rentman API endpoint, we must have the proper api key that belongs to the correct account
     */
    public function sync_crew_user($account,$user)
    {
        $rmapiurl = "";
        $url = "$account.$rmapiurl";
    }
}