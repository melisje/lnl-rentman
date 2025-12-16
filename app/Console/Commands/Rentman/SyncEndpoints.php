<?php

namespace App\Console\Commands\Rentman;

use App\Models\Rentman\Endpoint;
use App\Models\Rentman\EndpointField;
use App\Services\RentmanApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;

class SyncEndpoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentman:sync-endpoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync endpoints that are marked to sync (see table rm_endpoints)';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanApi)
    {
        // what endpoints need to be synced ?
        $endpoints = Endpoint::where('sync',true)->get();

        // $this->info(json_encode($endpoints,JSON_PRETTY_PRINT));
        foreach($endpoints as $endpoint)
        {
            // get the fields that we need for this enpoint
            // and make a string for the API call filter
            $fields = $endpoint->fields;
            foreach($fields as $field)
            {
                $fieldnames[] = $field->name;
            }
            $queryParams['fields'] = implode(",", $fieldnames);

            // limit the results in the time
            // This should be more flexible, based on last modified timestamp in the table
            $queryParams['modified[gte]'] = '2025-11-21';

            $this->info("queryParams: ". json_encode($queryParams,JSON_PRETTY_PRINT));

            // Now we can call the API, one page at a time
            $dataset = $rentmanApi->getEndpointDataPage($endpoint,$queryParams);

            $this->info($dataset);


        }
    }
}
