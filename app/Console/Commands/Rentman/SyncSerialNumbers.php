<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Services\Rentman\Api\SerialNumberFetcher;
use Carbon\Carbon;

class SyncSerialNumbers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-serialnumbers {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman equipment serials for all accounts';

    public function handle(SerialNumberFetcher $fetcher)
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
            $this->info("\n~~~> Processing equipment for account: {$account->account}");

            $hasMore = true;

            // required fields
            // {
            // "id": 0,
            // "created": "2019-08-24T14:15:22Z",
            // "modified": "2019-08-24T14:15:22Z",
            // "creator": "/crew/0",
            // "displayname": "string",
            // "combination": "/serialnumbers/0",
            // "serialnumber": "/serialnumbers/0"
            // }
            $requiredFields = [
                'created',
                'modified',
                'creator',
                'displayname',
                'equipment',
                'serial',
                'purchasedate',
                'depreciation_monthly',
                'book_value',
                'residual_value',
                'purchase_costs',
                'active',
                'remark',
                'ref',
                'asset_location',
                'image',
                'current_book_value',
                'next_inspection',
                'qrcodes',
                'tags',
                'last_subproject',
                'sealed',
                'custom',
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
            // https://api.rentman.net/serialnumbers
            $endpoint = "serialnumbers" ;

            // Fetch data via your existing service
            $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

            $this->info(".    +--> Serialnumber synchronisation process finished. We created or updated {$this->nrOfItems} serial items for account '{$account->account}'.");


        }

        $this->info("\n✅ Serialnumber synchronisation process finished. We created or updated {$this->totalItems} serial items over all accounts.");
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
