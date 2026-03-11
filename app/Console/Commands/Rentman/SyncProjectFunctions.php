<?php

namespace App\Console\Commands\Rentman;

use Illuminate\Console\Command;
use App\Services\Rentman\Api\RentmanApiService;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectFunction; // Zorg dat dit model bestaat
use App\Models\Rentman\SubProject;
use Carbon\Carbon;

class SyncProjectFunctions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rentman:sync-functions {account? : The specific account name to sync}';

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman project functions for the current week + 6 weeks';

    public function handle(RentmanApiService $rentmanService): int
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
            $this->info("Processing account: {$account->account}");

            $offset = 0;
            $limit = 200;
            $hasMore = true;

            do {
                // 3. Build query parameters with API-side filtering
                $queryParameters = [
                    'planperiod_start[gte]' => $start,
                    'planperiod_start[lte]' => $end,
                    'limit' => $limit,
                    'offset' => $offset,
                ];

                // Build query string
                $queryString = http_build_query($queryParameters);
                $endpoint = "/projectfunctions?" . $queryString;

                // Fetch data via your existing service
                $functions = $rentmanService->get_rentman_endpoint($account->account, $endpoint);

                if (empty($functions)) {
                    $hasMore = false;
                    continue;
                }

                // 4. Save to database
                foreach ($functions as $data)
                {
                    // 1. Handle Project Dependency
                    $projectId = isset($data['project']) ? basename($data['project']) : null;



                    if ($projectId && !Project::where('rm_id', $projectId)->exists()) {
                        $this->line("Project {$projectId} missing. Fetching from API...");

                        // Fetch single project from API
                        $projectData = $rentmanService->get_rentman_endpoint($account->account, "/projects/{$projectId}");

                        if ($projectData) {
                            $project = Project::updateOrCreate(
                                [
                                    'account' => $account->account,
                                    'rm_id' => $projectData['id']
                                ],
                                [
                                    'name'    => $projectData['name'] ?? 'Unknown Project',
                                    // Voeg hier de minimale velden toe die je Project model nodig heeft
                                ]
                            );
                        }
                    }



                    // 2. Handle Subproject Dependency
                    // $subprojectId = isset($data['subproject']) ? basename($data['subproject']) : null;
                    // if ($subprojectId && !SubProject::where('rm_id', $subprojectId)->exists()) {
                    //     $this->line("Subproject {$subprojectId} missing. Fetching from API...");

                    //     $subprojectData = $rentmanService->get_rentman_endpoint($account->account, "/subprojects/{$subprojectId}");

                    //     if ($subprojectData) {
                    //         Subproject::updateOrCreate(
                    //             [
                    //                 'account'    => $account->account,
                    //                 'rm_id' => $subprojectData['id']
                    //             ],
                    //             [
                    //                 'project_id' => $project->id,
                    //                 'name'       => $subprojectData['name'] ?? 'Unknown Subproject',
                    //             ]
                    //         );
                    //     }
                    // }

                    // save to database
                    ProjectFunction::updateOrCreate(
                        ['account' => $account->account, //
                        'rm_id' => $data['id']], // Unique Rentman ID
                        [
                            // Basis informatie
                            'displayname'          => $data['displayname'] ?? null,
                            'name'                 => $data['name'] ?? null,
                            'name_external'        => $data['name_external'] ?? null,
                            'type'                 => $data['type'] ?? null,

                            // Relaties (ID extractie via basename)
                            'creator'              => $data['creator'] ?? null,
                            'project'              => $data['project'] ?? null, // Het volledige pad
                            // 'project_id'           => isset($data['project']) ? basename($data['project']) : null,
                            'subproject'           => $data['subproject'] ?? null,
                            // 'subproject_id'        => isset($data['subproject']) ? basename($data['subproject']) : null,
                            'group'                => $data['group'] ?? null, // Het volledige pad
                            'taxclass'             => $data['taxclass'] ?? null,
                            'ledger'               => $data['ledger'] ?? null,

                            // Tijden en Periodes (API v2 geneste structuur)
                            'usageperiod_start'    => $data['usageperiod_start'] ?? null,
                            'usageperiod_end'      => $data['usageperiod_end'] ?? null,
                            'planperiod_start'     => $data['planperiod_start'] ?? null,
                            'planperiod_end'       => $data['planperiod_end'] ?? null,
                            'travel_time_before'   => $data['travel_time_before'] ?? 0,
                            'travel_time_after'    => $data['travel_time_after'] ?? 0,
                            'duration'             => $data['duration'] ?? 0,
                            'break'                => $data['break'] ?? 0,

                            // Financiën (Prijzen)
                            'price_rate'           => $data['price_rate'] ?? null,
                            'price_fixed'          => $data['price_fixed'] ?? 0,
                            'price_variable'       => $data['price_variable'] ?? 0,
                            'price_accommodation'  => $data['price_accommodation'] ?? 0,
                            'price_catering'       => $data['price_catering'] ?? 0,
                            'price_travel'         => $data['price_travel'] ?? 0,
                            'price_other'          => $data['price_other'] ?? 0,
                            'price_total'          => $data['price_total'] ?? 0,

                            // Financiën (Kosten)
                            'cost_rate'            => $data['cost_rate'] ?? null,
                            'costs_fixed'          => $data['costs_fixed'] ?? 0,
                            'costs_variable'       => $data['costs_variable'] ?? 0,
                            'cost_accommodation'   => $data['cost_accommodation'] ?? 0,
                            'cost_catering'        => $data['cost_catering'] ?? 0,
                            'cost_travel'          => $data['cost_travel'] ?? 0,
                            'cost_other'           => $data['cost_other'] ?? 0,
                            'costs_total'          => $data['costs_total'] ?? 0,

                            // Planning logica strings
                            'planperiod_start_schedule_is_start'  => $data['plan_period_start_schedule_is_start'] ?? null,
                            'usageperiod_start_schedule_is_start' => $data['usage_period_start_schedule_is_start'] ?? null,
                            'planperiod_end_schedule_is_start'    => $data['plan_period_end_schedule_is_start'] ?? null,
                            'usageperiod_end_schedule_is_start'   => $data['usage_period_end_schedule_is_start'] ?? null,

                            // Hoeveelheden & Afstand
                            'amount'               => $data['amount'] ?? 0,
                            'distance'             => $data['distance'] ?? 0,
                            'twoway'               => $data['twoway'] ?? true,

                            // Booleans en overig
                            'is_template'          => $data['is_template'] ?? false,
                            'in_financial'         => $data['in_financial'] ?? true,
                            'in_planning'          => $data['in_planning'] ?? false,
                            'is_plannable'         => $data['is_plannable'] ?? false,

                            // Recurrence (Herhaling)
                            'recurrence_group'     => $data['recurrence_group'] ?? 0,
                            'recurrence_enddate'   => $data['recurrence_enddate'] ?? null,
                            'recurrence_interval_unit' => $data['recurrence_interval_unit'] ?? null,
                            'recurrence_interval'  => $data['recurrence_interval'] ?? 0,
                            'recurrence_weekdays'  => isset($data['recurrence_weekdays']) ? json_encode($data['recurrence_weekdays']) : null,

                            // Tekst & Tags
                            'order'                => $data['order'] ?? null,
                            'remark_crew'          => $data['remark_crew'] ?? null,
                            'remark_planner'       => $data['remark_planner'] ?? null,
                            'remark_client'        => $data['remark_client'] ?? null,
                            'tags'                 => $data['tags'] ?? null,

                            // Custom fields (Verwacht JSON in migratie)
                            // 'custom'               => isset($data['custom']) ? $data['custom'] : null,

                            // Timestamps van Rentman zelf
                            'created'              => $data['created'] ?? null,
                            'modified'             => $data['modified'] ?? null,
                        ]
                    );
                }

                $this->line("Synced " . count($functions) . " functions at offset {$offset}...");

                // 5. Pagination check
                if (count($functions) < $limit) {
                    $hasMore = false;
                } else {
                    $offset += $limit;
                }
            } while ($hasMore);
        }

        $this->info('Project functions synchronization completed.');
        return Command::SUCCESS;
    }
}
