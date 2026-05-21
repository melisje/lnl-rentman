<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\CrewFetcher;
use App\Services\Rentman\Api\ProjectFetcher;
use Carbon\Carbon;

class SyncProjects extends Command
{
    /**
     * The name and signature of the console command.
     */

    protected $signature = 'rentman:sync-projects
        {account? : The specific account name to sync}
        {--project= : The specific project RMID to sync}';

    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman projects for all accounts within a 5-week window';

    public function handle(ProjectFetcher $fetcher)
    {
        $accountName = $this->argument('account');

        // 1. Determine time window (current week start to +6 weeks end)
        $start = Carbon::now()->subWeeks(20)->startOfWeek()->toIso8601String();
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
            $this->info("\n~~~> Processing account: {$account->account}");

            $hasMore = true;

            // required fields
            $requiredFields = [
                'created',
                'modified',
                'creator',
                'updateHash',
                'displayname',
                'name',
                'reference',
                'number',
                'planperiod_start',
                'planperiod_end',
                'usageperiod_start',
                'usageperiod_end',
                'equipment_period_from',
                'equipment_period_to',
                'account_manager',
                'customer',
                'cust_contact',
                'loc_contact',
                'project_total_price',
                'custom',
                'project_type',
                'location',
                'tags',
            ];

            // 3. Build query parameters with API-side filtering
            $queryParams = [
                'limit' => config('services.rentman.page_limit'),
                'offset' => 0,
                // 'created[gte]' => $start,
                'modified[gte]' => $start,
            ];

            // Optionele filter op project RMID
            $fltProjectId = $this->option('project');
            $queryParams['id'] = $fltProjectId ? $fltProjectId : null;

            // If required fields are defined, put them in the queryparamets array
            if ($requiredFields && !empty($requiredFields)) {
                $queryParams['fields'] = implode(',', $requiredFields);
            }

            // Define endpoint
            $endpoint = "projects" ;

            // Fetch data via your existing service
            $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

            $this->info(".    +--> Project synchronisation process finished. We created or updated {$this->nrOfItems} projects for account '{$account->account}'.");


        }

        $this->info("\n✅ Project synchronisation process finished. We created or updated {$this->totalItems} projects over all accounts.");
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
