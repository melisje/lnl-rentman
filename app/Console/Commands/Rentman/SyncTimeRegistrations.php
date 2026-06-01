<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Services\Rentman\Api\TimeRegistrationFetcher;
use Carbon\Carbon;

class SyncTimeRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-timeregistrations {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman TimeRegistrations for all accounts';

    public function handle(TimeRegistrationFetcher $fetcher)
    {
        $accountName = $this->argument('account');

        // 1. Determine time window (current week start to +6 weeks end)
        $start = Carbon::now()->subWeeks(20)->startOfWeek()->toIso8601String();
        // $end = Carbon::now()->addWeeks(6)->endOfWeek()->toIso8601String();

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
            $this->info("\n~~~> Processing timeregistrations for account: {$account->account}");

            $hasMore = true;

            // required fields
            $requiredFields = [
                'id',
                'created',
                'modified',
                'creator',
                'displayname',
                'crewmember',
                'start',
                'end',
                'distance',
                'is_lunch_included',
                'leavetype',
                'leaverequest',
                'duration',
                'break_duration',
                'travel_time',
                'correction_duration',
                'remark',
                'status',
                'break_duration_with_start_end',
                'custom',
            ];

            // 3. Build query parameters with API-side filtering
            $queryParams = [
                'limit' => config('services.rentman.page_limit'),
                'offset' => 0,
                'created[gte]' => $start,
                // 'modified[gte]' => $start,
            ];

            // If required fields are defined, put them in the queryparamets array
            if ($requiredFields && !empty($requiredFields)) {
                $queryParams['fields'] = implode(',', $requiredFields);
            }

            // Define endpoint
            // https://api.rentman.net/serialnumbers
            $endpoint = "timeregistration"; ;

            // Fetch data via your existing service
            $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

            $this->info(".    +--> TimeRegistration synchronisation process finished. We created or updated {$this->nrOfItems} time registration items for account '{$account->account}'.");


        }

        $this->info("\n✅ TimeRegistration synchronisation process finished. We created or updated {$this->totalItems} time registration items over all accounts.");
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
