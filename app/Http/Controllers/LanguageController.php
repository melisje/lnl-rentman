<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Wijzigt de taal van de applicatie.
     * * @param string $locale De gewenste taalcode (bijv. 'nl', 'en').
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // 1. Controleer of de taal geldig is (dit wordt al gedaan door de route, maar voor de zekerheid)
        $supportedLocales = ['en', 'nl'];

        if (!in_array($locale, $supportedLocales)) {
            // Valideer de input om beveiligingsrisico's te vermijden
            abort(400, __('invalid language code'));
        }

        // 2. Sla de taal op in de sessie
        Session::put('locale', $locale);

        // 3. Stel de applicatietaal in (optioneel, maar goed voor de huidige request)
        App::setLocale($locale);

        // 4. Stuur de gebruiker terug naar de vorige pagina
        return redirect()->back();
    }
}
