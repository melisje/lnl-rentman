<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\RentmanApiService;
use Carbon\Carbon;

class SyncProjects extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-projects';

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman projects for all accounts within a 5-week window';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanService): int
    {
        // Calculate the timeframe: Today until 5 weeks from now
        $start = Carbon::now()->startOfDay()->toIso8601String();
        $end = Carbon::now()->addWeeks(5)->endOfDay()->toIso8601String();

        // Build the query string using the filters required by Rentman API v2
        // We append this to the projects endpoint
        $endpoint = "/projects?planperiod_start[gte]={$start}&planperiod[_start[lte]={$end}";
        dump($endpoint);

        // Get all accounts to iterate through
        $accounts = Account::all();

        if ($accounts->isEmpty()) {
            $this->warn('No accounts found in rm_accounts.');
            return Command::SUCCESS;
        }

        foreach ($accounts as $account) {
            $this->info("Fetching projects for account: {$account->account}...");

            try {
                /** * Using your existing service method.
                 * Note: $account->account refers to the identifier used in your where clause.
                 */
                $projects = $rentmanService->get_rentman_endpoint($account->account, $endpoint);

                if (empty($projects)) {
                    $this->line("No projects found for {$account->account} in the given period.");
                    continue;
                }

                foreach ($projects as $data) {
                    // Save to the rm_projects table using the Project model
                    Project::updateOrCreate(
                        ['id' => $data['id']], // Unique Rentman ID
                        [
                            'name'              => $data['name'],
                            'plan_period_start' => $data['plan_period']['start'] ?? null,
                            'plan_period_end'   => $data['plan_period']['end'] ?? null,
                            'status'            => $data['status'] ?? null,
                            'account_id'        => $account->id, // Maintain reference to the account
                        ]
                    );
                }

                $this->info("Successfully synced " . count($projects) . " projects for {$account->account}.");
            } catch (\Exception $e) {
                $this->error("Error syncing account {$account->account}: " . $e->getMessage());
            }
        }

        $this->info('Synchronization process finished.');
        return Command::SUCCESS;
    }
}
