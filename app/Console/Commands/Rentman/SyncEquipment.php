<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Services\Rentman\Api\EquipmentFetcher;
use Carbon\Carbon;

class SyncEquipment extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-equipment {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman equipment for all accounts';

    public function handle(EquipmentFetcher $fetcher)
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
            $requiredFields = [
                'id',
                'created',
                'modified',
                'creator',
                'displayname',
                'folder',
                'code',
                'factor_group',
                'name',
                'internal_remark',
                'external_remark',
                'unit',
                'in_shop',
                'surface_article',
                'shop_description_short',
                'shop_description_long',
                'shop_seo_title',
                'shop_seo_keyword',
                'shop_seo_description',
                'shop_featured',
                'price',
                'subrental_costs',
                'critical_stock_level',
                'type',
                'rental_sales',
                'temporary',
                'in_planner',
                'in_archive',
                'stock_management',
                'taxclass',
                'list_price',
                'volume',
                'packed_per',
                'height',
                'width',
                'length',
                'weight',
                'empty_weight',
                'power',
                'current',
                'country_of_origin',
                'image',
                'ledger',
                'ledger_debit',
                'defaultgroup',
                'is_combination',
                'is_physical',
                'can_edit_content_during_planning',
                'strict_container_content',
                'qrcodes',
                'qrcodes_of_serial_numbers',
                'tags',
                'current_quantity_excl_cases',
                'current_quantity',
                'quantity_in_cases',
                'location_in_warehouse',
                'custom',
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
            $endpoint = "equipment" ;

            // Fetch data via your existing service
            $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

            $this->info(".    +--> Equipment synchronisation process finished. We created or updated {$this->nrOfItems} equipment items for account '{$account->account}'.");


        }

        $this->info("\n✅ Equipment synchronisation process finished. We created or updated {$this->totalItems} equipment items over all accounts.");
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
