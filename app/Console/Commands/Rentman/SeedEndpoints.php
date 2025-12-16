<?php

namespace App\Console\Commands\Rentman;

use App\Models\Rentman\Endpoint;
use App\Models\Rentman\EndpointField;
use App\Models\Rentman\Project;
use App\Services\RentmanApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SeedEndpoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentman:seed-endpoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding enpoints';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanApi)
    {
        $this->info("Start seeding endpoints");

        // add projects endpoint
        Endpoint::upsert(
        [
            'endpoint' => 'projects',
            'model' => 'Project',
            'sync' => true,
        ],
        uniqueBy: ['endpoint'],
        update: ['endpoint', 'sync']
        );

        $fieldnames =
            [
                // 'id',
                'created',
                'modified',
                'planperiod_start',
                'planperiod_end',
                'usageperiod_start',
                'usageperiod_end',
                'equipment_period_from',
                'equipment_period_to',
                'displayname',
                'name',
                'custom_1',
                'custom_2',
                'custom_32',
                'custom_33',
                'updateHash',
                'creator2',
                'number',
                'reference',
                'customer',
                'project_type',
                'color',
                'cust_contact',
                'loc_contact',
                'account_manager',
                // '',
            ];

        $fields = [];
        foreach($fieldnames as $name)
        {
            $fields[] = ['endpoint_id' => 1,'name' => $name];
        }

        // $this->info(json_encode($fields, JSON_PRETTY_PRINT));
        // dd($fields);

        EndpointField::upsert($fields,uniqueBy: ['endpoint','name'],update: ['name']);
    }
}
