<?php

namespace App\Services\Rentman\Api;

use App\Contracts\Rentman\DataProcessor;
use App\Models\Rentman\Account;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractRentmanFetcher implements DataProcessor
{
    protected string $baseUrl;
    protected string $token;
    protected PendingRequest $client;

    public function __construct(protected RentmanApiService $rentmanApiService)
    {
        // build endpoint url
        $this->baseUrl = config('services.rentman.base_url');
        $this->client = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
            ])

            /**
             * Since we are calling the api for multiple accounts (ledvisions
             * and llstageservice) we need to be able to dynamically choose
             * an apitoken based on the account. We ommit adding the token
             * in the constructor and add it to the call
             */
            // Gebruik de Authorization header voor het Bearer Token
            // ->withToken(config('services.rentman.token'))
        ;
    }

    /**
     * Fetch all data with pagination.
     */
    public function fetchAll(Account $account, string $endpoint, array $queryParams = [], ?array $fields = null, ?callable $onPageFetched = null): void
    {
        // fetch the API token for the given account
        $token = $account->api_token;

        $queryParams['limit'] = $limit = $queryParams['limit'] ?? 100;
        $queryParams['offset'] = $offset = $queryParams['offset'] ?? 0;
        $hasMore = true;
        $nrOfItems = 0;
        $page = 0;

        // dump($fields);
        if($fields){
            $queryparameters['fields'] = implode(',', $fields); // Maakt: name,status,project_number...
        }


        while ($hasMore) {
            // Send the request with the current $queryparameters.
            $response = $this->client
            ->withToken($token)
            ->beforeSending(function ($request) use ($account) {
                $url = $request->url();
                Log::info("~~~~> Calling endpoint $url for account {$account->account} ... ");
                })
            ->get($endpoint, $queryParams);

            if ($response->failed()) {
                Log::error("Rentman API error at $endpoint", ['response' => $response->body()]);
                break;
            }

            $data = $response->json();
            // Rentman often returns data in 'items' or directly as an array
            $items = $data['items'] ?? $data['data'] ?? $data;

            $cntOfItems = count($items);
            $nrOfItems += $cntOfItems;
            $page++;

            // Optional external callback
            if ($onPageFetched) {
                $onPageFetched($items, "     |  +-> Processing $endpoint page $page ($cntOfItems items)");
            }

            // Process the items received for this page
            $this->processPage($account->account, $items, $fields);

            if (empty($items)) {
                $hasMore = false;
                break;
            }

            $queryParams['offset'] += $limit;

            if (count($items) < $limit) {
                $hasMore = false;
            }

        }
    }

    /**
     * Make an array with fillable fields, based on a given item
     * @param array $item - an item as received from the Rentman API
     * @return array - the fillable fields
     */
    protected function fillables(array $item): array {
        // define array for fillable fields
        $fillables = [];

        // Add field mappings to $fillables array in key=>value
        foreach ($item as $key => $value){
            // dump("$key => $value");

            switch ($key) {
                // special mappings
                case 'id':
                    $fillable['rm_id'] = $value;
                    break;
                case 'account':
                    break;
                case 'custom':
                    $custom = json_encode($value);
                    $fillables[$key] = $custom;
                    break;

                // default mapping
                default:
                    $fillables[$key] = $value;
            }
        }

        // dump($fillables);
        return $fillables;
    }
}