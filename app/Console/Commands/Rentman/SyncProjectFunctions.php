<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Services\Rentman\Api\RentmanApiService;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectFunction; // Zorg dat dit model bestaat
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\ProjectFunctionFetcher;
use Carbon\Carbon;

class SyncProjectFunctions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-project-functions {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman project functions for the current week + 6 weeks';

    public function handle(ProjectFunctionFetcher $fetcher)
    {
        $accountName = $this->argument('account');

        // Get accounts
        // De when methode voert de where clausule alleen uit als $accountName een waarde heeft (niet null of false is).
        // * Als je php artisan rentman:sync-functions typt (zonder argument), haalt hij alle accounts op.
        // * Als je php artisan rentman:sync-functions mijn-account typt, haalt hij alleen dat account op.
        $accounts = Account::when($accountName, fn($q) => $q->where('account', $accountName))->get();

        if ($accounts->isEmpty()) {
            $this->error("No accounts found.");
            return Command::FAILURE;
        }

        foreach ($accounts as $account) {
            $this->info("\n~~~> Processing account: {$account->account}");

            $hasMore = true;

            // required fields
            $requiredFields = [
                // Basis informatie
                'displayname',
                'name',
                'name_external',
                'type',
                'updateHash',

                // Relaties (ID extractie via basename)
                'creator',
                'project',
                // 'project_id'           => isset($data['project']) ? basename($data['project']) : null,
                'subproject',
                // 'subproject_id'        => isset($data['subproject']) ? basename($data['subproject']) : null,
                'group',
                'taxclass',
                'ledger',

                // Tijden en Periodes (API v2 geneste structuur)
                'usageperiod_start',
                'usageperiod_end',
                'planperiod_start',
                'planperiod_end',
                'travel_time_before',
                'travel_time_after',
                'duration',
                'break',

                // Financiën (Prijzen)
                'price_rate',
                'price_fixed',
                'price_variable',
                'price_accommodation',
                'price_catering',
                'price_travel',
                'price_other',
                'price_total',

                // Financiën (Kosten)
                'cost_rate',
                'costs_fixed',
                'costs_variable',
                'cost_accommodation',
                'cost_catering',
                'cost_travel',
                'cost_other',
                'costs_total',

                // Planning logica strings
                'planperiod_start_schedule_is_start',
                'usageperiod_start_schedule_is_start',
                'planperiod_end_schedule_is_start',
                'usageperiod_end_schedule_is_start',

                // Hoeveelheden & Afstand
                'amount',
                'distance',
                'twoway',

                // Booleans en overig
                'is_template',
                'in_financial',
                'in_planning',
                'is_plannable',

                // Recurrence (Herhaling)
                'recurrence_group',
                'recurrence_enddate',
                'recurrence_interval_unit',
                'recurrence_interval',
                'recurrence_weekdays',

                // Tekst & Tags
                'order',
                'remark_crew',
                'remark_planner',
                'remark_client',
                'tags',

                // Custom fields (Verwacht JSON in migratie)
                // 'custom'               => isset($data['custom']) ? $data['custom'] : null,
                'custom',

                // Timestamps van Rentman zelf
                'created',
                'modified',
            ];

            // filter related subprojects
            $subproject_rmids = SubProject::where('account',$account->account)->pluck('rm_id')->toArray();

            // Verdeel de ID's in groepjes van 50 (or otherwise configured)
            $chunks = array_chunk($subproject_rmids, config('services.rentman.chunck_size',50));

            foreach($chunks as $chunk){

                // Build query parameters with API-side filtering
                $queryParams = [
                    'limit' => config('services.rentman.page_limit'),
                    'offset' => 0,
                    // 'created[gte]' => '2026-01-01',
                    // 'modified[gte]' => '2026-03-01',
                    // 'modified' => '2026-03-15',
                    'subproject' => implode(',',$chunk),
                ];

                // If required fields are defined, put them in the queryparamets array
                if ($requiredFields && !empty($requiredFields)) {
                    $queryParams['fields'] = implode(',', $requiredFields);
                }

                // Define endpoint
                $endpoint = "projectfunctions";

                // Fetch data via your existing service
                $fetcher->fetchAll($account, $endpoint, $queryParams, $requiredFields, [$this, 'myCallable']);
            }

            $this->info(".    +--> SubProject synchronisation process finished. We created or updated {$this->nrOfItems} project functions for account '{$account->account}'.");
        }

        $this->info("\n✅ SubProject synchronisation process finished. We created or updated {$this->totalItems} project functions over all accounts.");
        return Command::SUCCESS;
    }

    /**
     * A command specific callable function that is given to the service class
     * @param array $items - an array with items
     * @param string $msg - optional, a message to be shown, if given
     */
    public function myCallable(array $items, ?string $msg = null)
    {
        if ($msg) {
            $this->info($msg);
        }

        $this->nrOfItems  += count($items);
        $this->totalItems += count($items);
    }
}
