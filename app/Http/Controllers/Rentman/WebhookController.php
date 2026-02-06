<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessWebhookRentman;
use App\Models\Rentman\WebhookCall;

class WebhookController extends Controller
{
    //
    public function handle(Request $request)
    {
        // Log incoming data (Usefull for debugging)
        Log::info('Webhook ontvangen:',$request->all());

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
        ProcessWebhookRentman::dispatch($request->all());

        // ALWAYS return a 200 OK code, asap
        return response()->json(['status' => 'received']);
    }
}
