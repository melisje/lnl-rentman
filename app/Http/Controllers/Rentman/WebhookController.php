<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessWebhookRentman;
use App\Models\Rentman\WebhookCall;

class WebhookController extends Controller
{
    /**
     * Retrieve info and data from request
     *
     * The signature verification is already done via the VerifyRentmanDigest middleware.
     * So we are sure the webhook is sent by the correct Rentman environment
     *
     * In this method we receive the verified request and acknowledge reception to the sender.
     * Then we dispatch the processing of the payload to the ProcessWebhookRentman worker job.
     */
    public function handle(Request $request)
    {
        // retrieve data from request
        $data =
        [
            'payload' => $request->all(),  // allprocessed data, inclusive (request/query) parameters
            'rawcontent' => $request->getContent(),  // the raw content of the request
            'headers' => json_encode($request->headers->all()),  // all headers
            'ip' =>  $request->ip(),
        ];

        // Log incoming data (Usefull for debugging)
        Log::info("Webhook received from ". $data['ip']. ":", $data['payload']);

        // Dispatch data to job ...
        ProcessWebhookRentman::dispatch($data);

        // ... and immediately return response
        // ALWAYS return a 200 OK code, asap
        return response()->json(['status' => 'received']);
    }
}
