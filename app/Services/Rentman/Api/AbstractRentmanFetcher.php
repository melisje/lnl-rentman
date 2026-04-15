<?php

namespace App\Services\Rentman\Api;

use App\Contracts\Rentman\DataProcessor;
use App\Models\Rentman\Account;
use App\Models\Rentman\CustomFieldMapping;
use App\Scopes\AccountScope;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
                $onPageFetched($items, "     |  +-> Processing $endpoint chunk page $page ($cntOfItems items)");
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

        return $fillables;
    }

    /**
     * Process custom fields for a given item and model.
     */
    public function processCustomFields(string $account, array $item, $model): void
    {
        // List model attributes
        $fields = $model->getAttributes();

        // process custom fields
        $customFields = $item['custom'] ?? [];

        // Loop through custom fields and find the mapping for each field,
        // then save the value in the corresponding model fields.
        foreach ($customFields as $key => $value) {
            // Find the custom field mapping for this account
            $cf_rm_id = (int)Str::afterLast($key, '_'); // Assuming the key is something like "custom_field_123", we extract "123" as the RMID
            $custom_field_mapping = CustomFieldMapping::withoutGlobalScope(AccountScope::class)
                ->where('account', $account)
                ->where('rm_id', $cf_rm_id)->value('customfield_id');

            // Check if the mapping exists and if the corresponding field is fillable in the model
            if (array_key_exists($custom_field_mapping, $fields)) {
                $model->$custom_field_mapping = $value;
            }

            if (array_key_exists($key, $fields)) {
                // Het attribuut is aanwezig in de huidige instantie
                $model->$key = $value;
            }

            $model->save();

            // dump("$model->displayname: $cf_rm_id, $custom_field_mapping: $value");
        }
    }
}