<?php

namespace App\Http\Middleware\Rentman;

use App\Models\Rentman\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyRentmanDigest
{
    /**
     * Handle an incoming Rentman webhook request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Validate Header existence first
        $digestHeader = $request->header('Digest');
        if (!$digestHeader || !str_contains($digestHeader, '=')) {
            return response()->json(['error' => 'Missing or malformed Digest header'], 401);
        }

        // 2. Extract account from JSON body
        $accountName = $request->input('account');
        if (!$accountName) {
            return response()->json(['error' => 'No account specified in payload'], 400);
        }

        // 3. Retrieve Account model
        $account = Account::where('account', $accountName)->first();

        if (!$account || !$account->webhook_token) {
            Log::warning("Webhook signature check skipped: Account '{$accountName}' not found or token missing.");
            return response()->json(['error' => 'Unauthorized or unconfigured account'], 401);
        }

        // 4. Extract Algorithm and Hash from header (e.g., sha256=abcdef...)
        [$algo, $receivedDigest] = explode('=', $digestHeader, 2);

        // 5. Calculate our own hash
        $rawBody = $request->getContent();
        $calculatedDigest = hash_hmac(strtolower($algo), $rawBody, $account->webhook_token);

        // 6. DEBUG LOGGING
        // We log both so you can compare them in storage/logs/laravel.log
        Log::info("Rentman Webhook Debug [{$accountName}]:", [
            'algo'       => $algo,
            'received'   => $receivedDigest,
            'calculated' => $calculatedDigest,
            'match'      => hash_equals($receivedDigest, $calculatedDigest) ? 'YES' : 'NO'
        ]);

        // 7. Verify Signature
        if (!hash_equals($receivedDigest, $calculatedDigest)) {
            Log::error("Signature mismatch for Rentman account: {$accountName}");
            return response()->json(['error' => 'Invalid signature digest'], 401);
        }

        // 8. Attach the account to the request
        $request->attributes->add(['rentman_account' => $account]);

        return $next($request);
    }
}
