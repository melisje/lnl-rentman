<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Services\Rentman\Api\SerialNumberFetcher;
use App\Services\Rentman\Api\StockLocationFetcher;
use Carbon\Carbon;

class SyncStockLocations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-stocklocations {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman stock locations for all accounts';

    public function handle(StockLocationFetcher $fetcher)
    {
        $accountName = $this->argument('account');

        // 1. Determine time window (current week start to +6 weeks end)
        $start = Carbon::now()->startOfWeek()->toIso8601String();
        $end = Carbon::now()->addWeeks(6)->endOfWeek()->toIso8601String();

        // 2. Get accounts
        // De when methode voert de where clausule alleen uit als $accountName een waarde heeft (niet null of false is).
        // * Als je php artisan rentman:sync-functions typt (zonder argument), haalt hij alle accounts op.
        // * Als je php artisan rentman:sync-functions mijn-account typt, haalt hij alleen dat account op.
        $accounts = Account::when($accountName, fn($q) => $q->where('account', $accountName))->get();

        if ($accounts->isEmpty()) {
            $this->error("No accounts found.");
            return Command::FAILURE;
        }

        foreach ($accounts as $account) {
            $this->info("\n~~~> Processing stock locations for account: {$account->account}");

            $hasMore = true;

            $requiredFields = [
                'rm_id',
                'account',
                'created',
                'modified',
                'creator',
                'displayname',
                'name',
                'city',
                'street',
                'house_number',
                'postal_code',
                'state_province',
                'country',
                'active',
                'type',
                'color',
                'in_archive',
                'updateHash',
            ];

            // 3. Build query parameters with API-side filtering
            $queryParams = [
                'limit' => config('services.rentman.page_limit'),
                'offset' => 0,
                // 'created[gte]' => '2026-01-01',
                // 'modified[gte]' => '2026-01-01',
            ];

            // If required fields are defined, put them in the queryparamets array
            if ($requiredFields && !empty($requiredFields)) {
                $queryParams['fields'] = implode(',', $requiredFields);
            }

            // Define endpoint
            // https://api.rentman.net/stocklocations
            $endpoint = "stocklocations"; ;

            // Fetch data via your existing service
            $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

            $this->info(".    +--> Stocklocation synchronisation process finished. We created or updated {$this->nrOfItems} serial items for account '{$account->account}'.");
        }

        $this->info("\n✅ Stocklocation synchronisation process finished. We created or updated {$this->totalItems} serial items over all accounts.");
        return Command::SUCCESS;
    }

    public function myCallable(array $items, ?string $msg = null){
        if ($msg){
            $this->info($msg);
        }

        $this->nrOfItems  += count($items);
        $this->totalItems += count($items);
    }
}
