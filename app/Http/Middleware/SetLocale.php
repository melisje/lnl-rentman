<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Controleer of er een taal in de sessie staat
        if (Session::has('locale')) {
            // Stel de taal in op basis van de sessie
            App::setLocale(Session::get('locale'));
        } else {
            // Stel de taal in op basis van de configuratie (de standaard)
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
