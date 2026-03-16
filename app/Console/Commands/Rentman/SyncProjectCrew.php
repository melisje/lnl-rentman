<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Services\Rentman\Api\RentmanApiService;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectFunction; // Zorg dat dit model bestaat
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\ProjectCrewFetcher;
use App\Services\Rentman\Api\ProjectFunctionFetcher;
use Carbon\Carbon;

class SyncProjectCrew extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-project-crew {account? : The specific account name to sync}';
    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman project functions for the current week + 6 weeks';

    public function handle(ProjectCrewFetcher $fetcher)
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
                'displayname',
                'cost_rate',
                'cost_accommodation',
                'cost_catering',
                'cost_travel',
                'cost_other',
                'function',
                'crewmember',
                'visible',
                'planperiod_start',
                'planperiod_end',
                'transport',
                'remark',
                'remark_planner',
                'invoice_reference',
                'project_leader',
                'is_visible_on_dashboard',
                'costs',
                'cost_actual',
                'hours_registered',
                'hours_planned',
                'cost_planned',
                'diff_cost',
                'diff_hours',
                'activity_status',
                'updateHash',
                'custom',
                'project',
                'subproject',
            ];

            /* Since projectcrew is linked to projectfunctions, we want to filter
             * the projectfunction ids in the db for which we need to call the
             * projectcrew items.
             */
            $rmids = ProjectFunction::where('account',$account->account)->pluck('rm_id')->toArray();
            // $rmids = ProjectFunction::where('account',$account->account)->toRawSql();
            // dd($rmids);

            // Verdeel de ID's in groepjes van 50 (or otherwise configured)
            $chunks = array_chunk($rmids, config('services.rentman.chunck_size', 50));

            foreach ($chunks as $chunk) {

                // Build query parameters with API-side filtering
                $queryParams = [
                    'limit' => config('services.rentman.page_limit'),
                    'offset' => 0,
                    'function' => implode(',', $chunk),
                ];

                // If required fields are defined, put them in the queryparamets array
                if ($requiredFields && !empty($requiredFields)) {
                    $queryParams['fields'] = implode(',', $requiredFields);
                }
                // Define endpoint
                $endpoint = "projectcrew";

                // Fetch data via your existing service
                $fetcher->fetchAll($account, $endpoint, $queryParams, $requiredFields, [$this, 'myCallable']);

                $this->info(".    +--> ProjectCrew syncing process finished. We created or updated {$this->nrOfItems} project crew for account '{$account->account}'.");
            }
        }

        $this->info("\n✅ ProjectCrew syncing process finished. We created or updated {$this->totalItems} project crew over all accounts.");
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
