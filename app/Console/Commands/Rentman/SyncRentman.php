<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;

class SyncRentman extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentman:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Start totale Rentman synchronisatie...');

        $this->call('rentman:sync-crew');
        $this->call('rentman:sync-projects');
        $this->call('rentman:sync-subprojects');
        $this->call('rentman:sync-project-functions');
        $this->call('rentman:sync-project-crew');
        $this->call('rentman:sync-equipment');
        // $this->call('rentman:import-projects', [
        //     '--limit' => 500 // Je kunt ook argumenten/opties meegeven
        // ]);

        $this->getOutput()->success('Alles is succesvol bijgewerkt! ✅');        //
    }
}
