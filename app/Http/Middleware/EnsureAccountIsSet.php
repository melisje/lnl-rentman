<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) : Response
    {
        if (!$request->session()->has('current_account')) {
            // Check eerst database, dan cookie
            $account = auth()->user()->current_account ?? $request->cookie('remember_account');

            // Make sure, if the account is known, it is stored in the current session
            if ($account) {
                session(['current_account' => $account]);
            }
        }
        return $next($request);
    }
}
