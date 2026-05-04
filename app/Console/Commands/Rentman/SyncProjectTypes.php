<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectType;
use App\Scopes\AccountScope;
use App\Services\Rentman\Api\ProjectTypeFetcher;

class SyncProjectTypes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-project-types
        {account? : The specific account name to sync}';

    protected $nrOfItems = 0; // counter of items, resetted per account
    protected $totalItems = 0; // total nr of items over all accounts

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman project types';

    public function handle(ProjectTypeFetcher $fetcher)
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

        foreach ($accounts as $account)
        {
            $this->info("\n~~~> Processing account: {$account->account}");

            $hasMore = true;

            // required fields
            $requiredFields =
                [
                    // Basis informatie
                    'displayname',
                    'name',
                    'name_external',
                    'color',        // Komt van "color"
                    'type',         // Komt van "type"
                    'creator_path', // Komt van "creator" (/crew/0)
                    'updateHash',
                ];


            // Build query parameters with API-side filtering
            $queryParams = [
                'limit' => config('services.rentman.page_limit'),
                'offset' => 0,
            ];

            // If required fields are defined, put them in the queryparamets array
            if ($requiredFields && !empty($requiredFields)) {
                $queryParams['fields'] = implode(',', $requiredFields);
            }

            // Define endpoint
            $endpoint = "projecttypes";

            // Fetch data via your existing service
            $fetcher->fetchAll($account, $endpoint, $queryParams, $requiredFields, [$this, 'myCallable']);

            $this->info(".    +--> SubProject synchronisation process finished. We created or updated {$this->nrOfItems} project functions for account '{$account->account}'.");
        }

        $this->info("\n✅ ProjectTypes synchronisation process finished. We created or updated {$this->totalItems} project types over all accounts.");

        // Update Projects with project types id
        $this->updateProject();

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

    public function updateProject()
    {
        $this->info("++++-> Updating projects with project type ids.");
        // List all accounts in db
        $accounts = Account::pluck('account')->toArray();

        // Loop through accounts and update projects with project type ids based on related ProjectType records
        foreach ($accounts as $account) {
            $this->info("   ~~~> Processing account: $account");

            // Get all projecttypes for the current account and get their rm_ids
            $projectTypes =    ProjectType::withoutGlobalScope(AccountScope::class)
                ->where('account', $account)
                ->get();

            // Loop through the rm_ids and update the projects with the corresponding project type ids
            foreach ($projectTypes as $projectType) {
                // Build the project type path based on the rm_id of the project type
                $projectTypePath = '/projecttypes/' . $projectType->rm_id;
                $this->info("      ~~~> Updating projects for account: $account with project type path: $projectTypePath to have project type id: $projectType->id");

                // Update the projects with the corresponding project type ids based on the project
                // type path and account, while ignoring the global scope to ensure we can update
                // all relevant projects
                Project::withoutGlobalScope(AccountScope::class)
                    ->where('account', $account)
                    ->where('project_type', $projectTypePath)
                    ->update(['project_type_id' => $projectType->id]);
            }
        }

    }
}
