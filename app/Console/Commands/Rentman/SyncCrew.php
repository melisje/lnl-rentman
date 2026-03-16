<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\CrewFetcher;
use App\Services\Rentman\Api\ProjectFetcher;
use Carbon\Carbon;

class SyncCrew extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-crew {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman crew for all accounts';

    public function handle(CrewFetcher $fetcher)
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
            $this->info("\n~~~> Syncing Crew for account: {$account->account}");

            $this->nrOfItems = 0;
            $hasMore = true;

            // required fields
            $requiredFields = [
                // general fields
                'created',
                'modified',
                'creator',
                'displayname',
                'updateHash',

                // specific fields
                'folder',
                'street',
                'housenumber',
                'city',
                'postal_code',
                'addressline2',
                'state',
                'country',
                'birthdate',
                'passport_number',
                'emergency_contact',
                'remark',
                'driving_license',
                'contract',
                'bank',
                'contract_date',
                'company_name',
                'vat_code',
                'coc_code',

                // custom fields
                'custom',
            ];

            // Build query parameters with API-side filtering
            $queryParams = [
                'limit' => config('services.rentman.page_limit'),
                'offset' => 0,
                // 'modified[gte]' => '2026-03-01' ,
            ];

            // If required fields are defined, put them in the queryparamets array
            if ($requiredFields && !empty($requiredFields)){
                $queryParams['fields'] = implode(',', $requiredFields);
            }

            // define endpoint
            $endpoint = "crew" ;

            // Fetch data via your existing service
            $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

            $this->info(".    +--> Crew syncing process finished. We created or updated {$this->nrOfItems} crew members for account '{$account->account}'.");
            // ✅

        }

        $this->info("\n✅ Crew syncing process finished. We created or updated {$this->totalItems} crew members over all accounts.");
        return Command::SUCCESS;
    }

    /**
     * A command specific callable function that is given to the service class
     * @param array $items - an array with items
     * @param string $msg - optional, a message to be shown, if given
     */
    public function myCallable(array $items, ?string $msg = null){
        if ($msg){
            $this->info($msg);
        }

        $this->nrOfItems  += count($items);
        $this->totalItems += count($items);
    }
}
