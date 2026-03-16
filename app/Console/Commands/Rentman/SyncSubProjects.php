<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\CrewFetcher;
use App\Services\Rentman\Api\ProjectFetcher;
use App\Services\Rentman\Api\SubProjectFetcher;
use Carbon\Carbon;

class SyncSubProjects extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-subprojects {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman subprojects for all accounts';

    public function handle(SubProjectFetcher $fetcher)
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
                'created',
                'modified',
                'creator',
                'updateHash',
                'displayname',
                'name',
                'reference',
                'project',
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

            // find rm_ids from projects
            $rmids = Project::where('account',$account->account)->pluck('rm_id')->toArray();

            // Verdeel de ID's in groepjes van 50 (or otherwise configured)
            $chunks = array_chunk($rmids, config('services.rentman.chunck_size', 50));
            $allData = [];

            foreach ($chunks as $chunk) {

                // Build query parameters with API-side filtering
                $queryParams = [
                    'limit' => config('services.rentman.page_limit'),
                    'offset' => 0,
                    // 'created[gte]' => '2026-01-01',
                    // 'modified[gte]' => '2026-03-01',
                    // 'modified' => '2026-01-01',
                    'project' => implode(',', $chunk),
                ];


                if ($requiredFields && !empty($requiredFields)) {
                    $queryParams['fields'] = implode(',', $requiredFields);
                }

                $endpoint = "subprojects" ;

                // Fetch data via your existing service
                $fetcher->fetchAll($account,$endpoint,$queryParams, $requiredFields, [$this,'myCallable'] );

                $this->info(".    +--> Synchronization process finished. We created or updated {$this->nrOfItems} subprojects for account '{$account->account}'.");

            }
        }

        $this->info("\n✅ Synchronization process finished. We created or updated {$this->totalItems} subprojects over all accounts.");
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
