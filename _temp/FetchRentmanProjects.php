<?php

namespace App\Console\Commands\Rentman;

use App\Services\RentmanApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
Use Illuminate\Support\Facades\Log;

class FetchProjects2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:fetch-rentman-invoices';
    protected $signature = 'rentman:fetch-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new or updated rentman projects';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanApi)
    {
        $this->info('Start fetching projects ...'); // Terminal message

        // API Call Logica
        $endpoint = 'projects';
        $queryParameters = [
            'modified[gte]' => '2025-11-20',
        ];

        $projects = $rentmanApi->getEndpointData($endpoint,$queryParameters);

        foreach($projects as $key => $project)
        {
            $this->info($project);
        }

        // if ($response->successful()) {
        //     $users = $response->json();

        //     // Log de opgehaalde gegevens, zodat je weet dat het werkt
        //     Log::info('Gebruikers succesvol opgehaald', ['count' => count($users)]);

        //     // TODO: Voeg hier je logica toe om de data op te slaan in de database
        //     // of andere verwerking.

        //     $this->info('Klaar. ' . count($users) . ' gebruikers gevonden.'); // Terminal bericht
        //     return Command::SUCCESS;
        // } else {
        //     $this->error('Fout bij het ophalen van gebruikers. Status: ' . $response->status()); // Terminal bericht
        //     Log::error('Fout bij API-aanroep voor gebruikers', ['status' => $response->status(), 'body' => $response->body()]);
        //     return Command::FAILURE;
        // }
    }
}
