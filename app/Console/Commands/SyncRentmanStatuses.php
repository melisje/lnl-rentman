<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Rentman\Api\RentmanApiService;
use App\Models\Rentman\Status;
use App\Models\Rentman\Account;
use Illuminate\Support\Facades\Log;

class SyncRentmanStatuses extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-statuses';

    /**
     * The console command description.
     */
    protected $description = 'Sync project statuses from Rentman API for all registered accounts';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $apiService): int
    {
        /* Fetch all active accounts from the database */
        $accounts = Account::all();

        if ($accounts->isEmpty()) {
            $this->warn('No accounts found in the database to sync.');
            return self::SUCCESS;
        }

        $this->info('Starting Rentman status sync for ' . $accounts->count() . ' accounts...');

        foreach ($accounts as $account) {
            $this->comment('Syncing account: ' . $account->account);

            try {
                /* Fetch statuses specifically for this account context */
                $statuses = $apiService->getStatuses($account->account);

                if (empty($statuses)) {
                    $this->line(' - No statuses found for this account.');
                    continue;
                }

                foreach ($statuses as $data) {
                    /* Use updateOrCreate to prevent duplicates and update changes */
                    Status::updateOrCreate(
                        [
                            'rm_id'   => $data['id'],
                            'account' => $account->account,
                        ],
                        [
                            'name'    => $data['name'],

                        ]
                    );
                }

                $this->info(' - Success');
            } catch (\Exception $e) {
                $this->error(' - Failed for ' . $account->account . ': ' . $e->getMessage());
                Log::error('Sync Error [' . $account->account . ']: ' . $e->getMessage());
            }
        }

        $this->info('Total sync process completed.');
        return self::SUCCESS;
    }
}
