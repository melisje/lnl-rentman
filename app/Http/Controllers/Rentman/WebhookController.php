<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessWebhookRentman;
use App\Models\Rentman\WebhookCall;

class WebhookController extends Controller
{
    public array $payload;

    //
    public function handle(Request $request)
    {
        // 1. Log incoming data (Usefull for debugging)
        Log::info('Webhook ontvangen:',$request->all());

        $this->payload = $request->all();

        // Signature verification
        $secret = config('services.webhook.secret'); // the secret key
        $signature = $request->header('X-Signature-Header'); // The header can be different per service

        // Calculate the expecte signatur (often HMAC SHA256)
        $payload = $request->getContent();
        $expected = hash_hmac('sha256', $payload, $secret);

        // if (!hash_equals($expected, $signature)) {
        //     abort(403, 'Unauthorized webhook signature.');
        // }

        // Dispatch job and immediately return response
        // ProcessWebhookRentman::dispatch($request->all());
        ProcessWebhookRentman::dispatch($this->payload);


        // $eventype = $this->payload['eventType'] ?? 'unknown';
        // $itemtype = $this->payload['itemType'] ?? 'unknown';
        // $items = $this->payload['items'];
        // $eventdate = $this->payload['eventDate'];

        // Log::info("Eventype: $eventype");
        // Log::info("Itemtype: $itemtype");
        // Log::info("Items: " . json_encode($items));
        // Log::info("Eventdate: $eventdate");

        // $wbc = new WebhookCall;
        // $wbc->account = $this->payload['account'] ?? 'unknown';
        // $wbc->user = $this->payload['user']['id'] ?? 'unknown';
        // $wbc->eventType = $this->payload['eventType'] ?? 'unknown';
        // $wbc->itemType = $this->payload['itemType'] ?? 'unknown';
        // $wbc->items = json_encode($this->payload['items']);
        // $wbc->eventDate = $this->payload['eventDate'];

        // $wbc->save();


        // ALWAYS return a 200 OK code, asap
        return response()->json(['status' => 'received']);
    }
}
