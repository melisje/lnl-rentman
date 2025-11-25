<?php

namespace App\Console\Commands\Rentman;

use App\Models\Rentman\Project;
use App\Services\RentmanApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentman:fetch-projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch projects from Rentman API';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanApi)
    {
        $this->info("RENTMAN");
        $this->info("Start fetching projects");

        // $endpoint = 'projects/8444';
        // $endpoint = 'projects/8444';
        $endpoint = 'projects';

        $filters = [
            'modified[gte]' => '2025-11',
            // 'id[gte]' => '8440',
            // 'name[gte]' => 'RFID',
      ];

      $timestampFields = [
            'created',
            'modified',
            'planperiod_start',
            'planperiod_end',
            'usageperiod_start',
            'usageperiod_end',
            'equipment_period_from',
            'equipment_period_to',
      ];

        $fields = array_merge([
            // 'id',
            'displayname',
            'name',
            'custom_1',
            'custom_2',
            'custom_32',
            'custom_33',
            'updateHash',
            'creator',
            'number',
            'reference',
            'customer',
            'project_type',
            'color',
            'cust_contact',
            'loc_contact',
            'account_manager',
            // '',
        ],$timestampFields) ;

        $queryParameters = [
            'fields' => implode(',',$fields),
            'sort' => "-modified"
        ] ;


        // Add filters to $queryParameters
        foreach($filters as $key => $value)
        {
            $queryParameters[$key] = $value;
        }

        $this->info(json_encode($queryParameters, JSON_PRETTY_PRINT));


        $projects = $rentmanApi->getEndpointData($endpoint,$queryParameters);

        $this->info(count($projects) . " projects found");


        $this->info(json_encode($projects, JSON_PRETTY_PRINT));

        foreach($projects as $key => $project)
            {
            // create or update project in database
            // $project['planperiod_start'] = Carbon::parse($project['planperiod_start']);
            // Project::upsert($project,uniqueBy: ['id'], update: $fields);
        }
        Project::upsert($projects,uniqueBy: ['id'], update: $fields);

    }
}
