<?php

namespace App\Http\Middleware\Rentman;

use App\Models\Rentman\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyRentmanDigest
{
    /**
     * Handle an incoming request.
     *
     * If the webhook request is sent by Rentman, the json payload in the request body contains an
     * "account" property.
     * Based on the account, we know what encryption key is used by rentman to make a digest of the
     * raw body content.  This encryption key can be found in the configuration section under
     * 'integrations' > 'webhooks' in the Rentman web app for this envrionment.
     * We have stored those keys in the rm_api_tokens table.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fetch the account from the JSON body
        $accountName = $request->input('account');

        if (!$accountName) {
            return response()->json(['error' => 'No account specified in body'], 400);
        }

        // Find the account's encryption token in the database
        $apiToken = ApiToken::where('account', $accountName)->first();

        if (!$apiToken) {
            return response()->json(['error' => 'Unknown account'], 401);
        }

        // Validate the Digest header
        $digestHeader = $request->header('Digest');
        if (!$digestHeader || !str_contains($digestHeader, '=')) {
            return response()->json(['error' => 'Invalid or missing Digest header'], 401);
        }

        [$algo, $receivedDigest] = explode('=', $digestHeader, 2);

        // Gebruik de raw content voor de verificatie
        // Verify the raw content
        $rawBody = $request->getContent();
        $calculatedDigest = hash_hmac($algo, $rawBody, $apiToken->token);

        Log::info("Digest: ", [$calculatedDigest]);

        if (!hash_equals($receivedDigest, $calculatedDigest))
        {
            Log::error('Signature mismatch');
            return response()->json(['error' => 'Signature mismatch'], 401);
        }

        Log::info("Signature matched...");
        // Optional: add the model to the request for usage in the controller
        $request->attributes->add(['api_token_model' => $apiToken]);

        return $next($request);
    }
}
