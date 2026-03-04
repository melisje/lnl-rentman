<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Account;
use App\Models\Rentman\Crew;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class RentmanApiService
{
    protected PendingRequest $client;
    protected $baseUrl;

    // Maximum limit per page. Rentman support often higher limits,
    // but this value is a safe standard to avoid timeouts or size limits.
    const PAGE_LIMIT = 100;

    /**
     * De constructor configureert de HTTP-client met de basis-URL en het Bearer Token.
     */
    public function __construct()
    {

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
     * Test function to check if the RentmanApiService
     * class is injected and accessible
     */
    public function service_injected(string $message, string $level = 'info')
    {
        Log::info("RentmanApiService is injected and accessible");
    }

    /**
     * Post data to a given Rentman endpoint.
     * @param array $data De data om te posten.
     * @return array
     *
     * @param  string  $endpoint
     * @param  array|string|null  $data that will be sent to the endpoint
     * @return array the result from the $response->json() call
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function postEndpoint(string $endpoint, array $data): array
    {
        $response = $this->client->post($endpoint, $data);

        // Throw an ConnectionExectpion when the statuscode indicates an error (4xx or 5xx)
        $response->throw();

        return $response->json();
    }
    public function getEndpointDataPage($endpoint, array $queryParameters): array
    {
        // Check if a value for limit is given with queryParameters
        // If not, take default value from RentmanApiService container
        if (! isset($queryParameters['limit'])) {
            $queryParameters['limit'] = config('services.rentman.page_limit', self::PAGE_LIMIT);
        }

        // If no offset is given, the default offset is 0
        // Actually, this is not necessary, because this is also the
        // standard offset from Rentman when no offset is given
        if (! isset($queryParameters['offset'])) {
            $queryParameters['offset'] = 0;
        }

        // Initialise dataset that will be returned
        $dataset = [];

        // Send the request with the current $queryparameters.
        $response = $this->client->get($endpoint, $queryParameters);
        // we receive a status 200
        if ($response->successful()) {
            // Status 200, 201, 202, etc. (2xx range).
            // De request finished successfull.
            // Handle response data ...

            // get the payload as an object from the respons
            $payload = $response->object(); // get payload as object

            // If no errors The payload contains following info:
            // - data: the retrieved item(s)
            // - itemCount: the number of items in the dataset
            // - limit: the max number of items that could be retrieved in this request
            // - offset: the offset from where the dataset is retrieved from all available items that meet the current filter


            // Check if the data property from the payload instance is
            // a single object or an array of objects.
            // If is it a single object, we change
            // it in an array containing this
            // single object.
            if (is_object($payload->data)) {
                //
                $payload->data = [$payload->data];
                $singleitem = true;  // to break the loop later
            }
            else
            {
                $singleitem = false;
            }

            // Loop through the array of items in the data property
            // and, after flatten the custom fields, add the item
            // to the dataset to be returned.
            foreach ($payload->data as $key => $item) {
                // if $item contains custom fields, flatten ...
                if (isset($item->custom)) {
                    // Flatten custom property !
                    // Add each custom item as a property of the item
                    foreach ($item->custom as $fname => $value) {
                        $item->$fname = $value;
                    }
                    // ... and remove the custom property from the item object.
                    unset($item->custom);
                }

                // add the item instance to the dataset array
                $dataset[] = (array)$item;
            }
        }
        else
        {
            // status 3xx,4xx,5xx
            $response->throw();
        }

        return $dataset;
    }

    public function getEndpointData($endpoint, array $queryParameters): array
    {
        Log::info("Start fetching '" . $endpoint . "'...");

        try
        {
            // Check if a value for limit is given with queryParameters
            // If not, take default value from RentmanApiService container
            if (! isset($queryParameters['limit']))
            {
                $queryParameters['limit'] = config('services.rentman.page_limit', self::PAGE_LIMIT);
            }

            // initialise the offset to start with
            $queryParameters['offset'] = 0;

            // Initialise the dataset that will be returned
            $dataset = [];

            // initialise the number of loops. Each loop will send a request
            $loops=0;
            $fullRequestUrl="to be build"; // TODO

            while(true)
            {
                // Send the request with the current $queryparameters.
                $response = $this->client->get($endpoint, $queryParameters);

                $server_headers = $response->headers();
                Log::debug("Headers: ". json_encode($server_headers));
                foreach($server_headers as $key => $header)
                {
                    Log::debug("Header: $key: ". json_encode($header));
                }

                // we receive a status 200
                if ($response->successful())
                {
                    // Status 200, 201, 202, etc. (2xx range).
                    // De request finished successfull.
                    // Handle response data ...

                    // get the payload as an object from the respons
                    $payload = $response->object(); // get payload as object

                    // If no errors The payload contains following info:
                    // - data: the retrieved item(s)
                    // - itemCount: the number of items in the dataset
                    // - limit: the max number of items that could be retrieved in this request
                    // - offset: the offset from where the dataset is retrieved from all available items that meet the current filter


                    // Check if the data property from the payload instance is
                    // a single object or an array of objects.
                    // If is it a single object, we change
                    // it in an array containing this
                    // single object.
                    if (is_object($payload->data))
                    {
                        //
                        $payload->data = [$payload->data];
                        $singleitem = true;  // to break the loop later
                    }
                    else
                    {
                        $singleitem = false;
                    }

                    // Loop through the array of items in the data property
                    // and, after flatten the custom fields, add the item
                    // to the dataset to be returned.
                    foreach ($payload->data as $key => $item)
                    {
                        // if $item contains custom fields, flatten ...
                        if (isset($item->custom))
                        {
                            // Flatten custom property !
                            // Add each custom item as a property of the item
                            foreach($item->custom as $fname => $value)
                            {
                                $item->$fname = $value;
                            }
                            // ... and remove the custom property from the item object.
                            unset($item->custom);
                        }

                        // add the item instance to the dataset array
                        $dataset[] = (array)$item;
                    }

                    // check some conditions to exit the loop

                    // If less items are received than the page limit for this API call,
                    // no more items are left and we could exit the loop.
                    if ($payload->itemCount < $payload->limit)
                    {
                        Log::debug("last items received");
                        break;
                    };

                    // If the payload contains only a single item, we can also exit the loop
                    if ($singleitem)
                    {
                        Log::debug("payload does not contain collection");
                        break;
                    }

                    // At this point no conditions are met to exit the loop
                    // and the next page of items can be requested.
                    // Adjust the offset for the next loop.

                    $queryParameters['offset'] += $payload->limit;
                }
                else
                {
                    // Request failed (3xx, 4xx, 5xx, )
                    if ($response->serverError())
                    {
                        // Status 500, 502, 503, etc. (5xx range)
                        // De API server reports an error.
                        Log::critical("Rentman Server Error: " . $response->status(), [
                            'url' => $fullRequestUrl,
                            'body' => $response->body()
                        ]);
                        // Gooi een exception of probeer het later opnieuw
                    }
                    elseif ($response->clientError())
                    {
                        // Status 400, 401, 403, 404, etc. (4xx range)
                        // This is often the result of an error in the request that we have sent.
                        // (authentication failure, wrong url, ...)
                        Log::warning("API Client Error: " . $response->status(), [
                            'url' => $fullRequestUrl,
                            'foutmelding' => $response['message'] ?? 'Unknown 4xx fout'
                        ]);
                    }
                    else
                    {
                        // Other statusses (e.g. 3xx redirects)
                        // You can check the specific status here and act accordingly
                        if ($response->status() === 301)
                        {
                            // ... handel de redirect af (hoewel de Laravel HTTP Client dit vaak automatisch volgt)
                        }
                    }

                    // exit loop in case of error
                    $response->throw();
                    break;
                }
            }
        }
        catch (ConnectionException $e)
        {
            Log::error("Error during sending request: " . $e->getMessage());
            throw $e;
        }
        catch (RequestException $e)
        {
            Log::error("Error during pagination: " . $e->getMessage());
            throw $e;
        }

        // The dataset contains all items from all the loops and can be returned.
        return $dataset;
    }

    /**
     * Send GET call to Rentman API for a given $account
     * @param string $account The account to be used in the API call
     * @param string $endpoint The endpoint path
     * @return array the retrieved data
     */
    public function get_rentman_endpoint(string $account, string $endpoint) : array|null
    {
        // build endpoint url
        $base_url = config('services.rentman.base_url');
        $url = $base_url . $endpoint;

        // fetch the API token for the given account
        $apiToken = Account::where('account', $account)->first();
        $token = $apiToken->api_token;

        Log::info("~~~~> Calling endpoint $url for account $account ... ");

        // send get request
        $response = Http::withHeaders(
            [
                'Accept' => 'application/json',
            ]
        )
            ->withToken($token)
            ->get($url);

        if ($response->successful())
        {
            // The results can be found in the 'data' message
            $data = $response->json()['data'] ?? [];
            return $data;
        }
        else
        {
            return $response->throw();
        }
    }


    /**
     * Fetch the current data for the given crewmember from Rentman
     *
     * To call a rentman API endpoint, we must have the proper
     * api_token that belongs to the correct account
     *
     * @param string $account
     * @param array $user
     */
    public function sync_crew_user($account, $user) : array|null
    {
        if ($user === null)
        {
            Log::warning("No user given !");
            return null;
        }

        $userid = $user['id'];
        Log::info("~~> fetching crew member $userid  for account $account ...");

        // initialise some variables
        $endpoint = $user['ref'];

        // Fetch crew member data
        $data = $this->get_rentman_endpoint($account,$endpoint);

        return $data;

    }

    /**
     * Fetch project data from Rentman API
     */
    public function get_project($account, $rentman_id) : array|null
    {
        Log::info("~~> fetching project $rentman_id for account $account ...");

        // build endpoint path
        $endpoint = "/projects/$rentman_id";

        // fetch and return project data
        return $this->get_rentman_endpoint($account, $endpoint);


    }

    /**
     * Fetch subproject data from Rentman API
     */
    public function get_subproject($account, $rentman_id) : array|null
    {
        Log::info("~~> fetching subproject $rentman_id for account $account ...");

        // build endpoint path
        $endpoint = "/subprojects/$rentman_id";

        // fetch and return project data
        return $this->get_rentman_endpoint($account, $endpoint);


    }

    /**
     * Fetch the subprojects'data for a given $project_id
     * from the Rentman API for a given $account
     * @param string $account The Rentman account identifier
     * @param string $project_id The id of the project the subprojects are fetched for
     * @return array Data array with the subprojects
     */
    public function get_subprojects($account, $project_id)
    {
        Log::info("~~> Fetching subprojects for project $project_id for account $account");

        // build endpoint path
        $endpoint = "/projects/$project_id/subprojects";

        // fetch and return subprojects data
        return $this->get_rentman_endpoint($account,$endpoint);
    }


    /**
     * Fetch statuses for a specific account.
     */
    public function getStatuses($account): array
    {
        Log::info("~~> Fetching statuses for account $account");

        // build endpoint path
        $endpoint = "/statuses";

        // fetch and return subprojects data
        return $this->get_rentman_endpoint($account, $endpoint);

    }
}
