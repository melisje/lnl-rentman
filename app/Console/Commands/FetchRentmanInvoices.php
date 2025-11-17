<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
Use Illuminate\Support\Facades\Log;

class FetchRentmanInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fetch-rentman-invoices';
    protected $signature = 'rentman:fetch-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new or updated rentman invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starten met het ophalen van gebruikers...'); // Terminal bericht

        // API Call Logica
        $response = Http::get('https://jsonplaceholder.typicode.com/users');

        if ($response->successful()) {
            $users = $response->json();

            // Log de opgehaalde gegevens, zodat je weet dat het werkt
            Log::info('Gebruikers succesvol opgehaald', ['count' => count($users)]);

            // TODO: Voeg hier je logica toe om de data op te slaan in de database
            // of andere verwerking.

            $this->info('Klaar. ' . count($users) . ' gebruikers gevonden.'); // Terminal bericht
            return Command::SUCCESS;
        } else {
            $this->error('Fout bij het ophalen van gebruikers. Status: ' . $response->status()); // Terminal bericht
            Log::error('Fout bij API-aanroep voor gebruikers', ['status' => $response->status(), 'body' => $response->body()]);
            return Command::FAILURE;
        }
    }
}
