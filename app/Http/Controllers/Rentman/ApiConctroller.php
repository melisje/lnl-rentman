<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\String\u;

class ApiConctroller extends Controller
{
    public static function build_rentman_url($endpoint, $queryParams): string
    {
        $token = env('RENTMAN_BEARER_TOKEN', '*** Add token in .env as RENTMAN_BEARER_TOKEN ***');
        $baseurl        = env('RENTMAN_API_URL');
        $endpoint_url   = $baseurl . "/" . $endpoint;

        $filter = $queryParams['filter'];
        unset($queryParams['filter']);

        // build full http request url incl. query parameters
        $queryString = http_build_query($queryParams);
        $fullRequestUrl = $endpoint_url . '?' . $queryString;

        if ($filter)
        {
            $fullRequestUrl = $fullRequestUrl . '&' . $filter;
        }

        // The filter param should not be prefixed with 'filter=' in the url that is expected by the Rentman API
        // $fullRequestUrl = str_replace('filter=', '', $fullRequestUrl);

        return $fullRequestUrl;
    }

    public static function sendRequest($endpoint,$queryParams)
    {
        $token = env('RENTMAN_BEARER_TOKEN', '*** Add token in .env as RENTMAN_BEARER_TOKEN ***');
        $baseurl        = env('RENTMAN_API_URL');
        $endpoint_url   = $baseurl . "/" . $endpoint;

        // Check if limit is given in queryParams. If not, take default limit
        // value from env file or, if this does not exist, the defaule max.
        // limit as documented by Rentman.
        if (Arr::has($queryParams, 'limit')) {
            //  limit is given in $queryParams => nothing to be done
        } else {
            // A value for 'limit' is not given in the queryParameters, fetch
            // value from .env file. If not found in .env file, take the
            // max limit as documented in the Rentman API Documentation
            // See https://api.rentman.net/#section/Introduction/Response-data-limitation
            $queryParams['limits'] = env('RENTMAN_PAGE_LIMIT', 300);
        }

        /*
         * Fetch data from API endpoint. Repsonses are limited to 300 items. If the amount of items
         * equals the page size (limit), we can assume that more items are available. We keep
         * fetching data with offset adjusted with page size until no items are received,
         * or number of received items is less than page limit.
         */

        $itemcount = 0;
        $limit = $queryParams['limit'];
        // $result = collect([]);
        // $result = new Collection();
        $result = [];
        do {
            // build full http request url incl. query parameters

            $fullRequestUrl = ApiConctroller::build_rentman_url($endpoint, $queryParams);

            Log::debug($fullRequestUrl);
            // Send the request
            $response = Http::withToken($token)->get($fullRequestUrl);
            $status = $response->status(); // should be 200

            if ($response->successful()) {
                $data = $response['data'];
                // Log::debug($data);

                $itemCount = (int)count($data);
                $limit = (int)$response['limit'];

                // Log::info($response['data']);

                $queryParams['offset'] += $limit;

                $result = array_merge($result, $data);
                // $result->merge($data);
            } else {
                // Log de fout en stop de verwerking indien nodig
                Log::error('API Call Failed', ['url' => $fullRequestUrl, 'status' => $response->status(), 'errorMessage' => $response->body()]);
                break; // Stop de loop bij een fout
            }
        } while ($limit === $itemCount);

        // Log::info($result);
        return collect($result);
    }

    public static function fetch($endpoint,$queryParams)
    {
        $token = env('RENTMAN_BEARER_TOKEN', '*** Add token in .env as RENTMAN_BEARER_TOKEN ***');
        $baseurl        = env('RENTMAN_API_URL');
        $endpoint_url   = $baseurl . "/" . $endpoint;

        // Check if limit is given in queryParams. If not, take default limit
        // value from env file or, if this does not exist, the defaule max.
        // limit as documented by Rentman.
        if (Arr::has($queryParams, 'limit'))
        {
            //  limit is given in $queryParams => nothing to be done
        }
        else
        {
            // A value for 'limit' is not given in the queryParameters, fetch
            // value from .env file. If not found in .env file, take the
            // max limit as documented in the Rentman API Documentation
            // See https://api.rentman.net/#section/Introduction/Response-data-limitation
            $queryParams['limits'] = env('RENTMAN_PAGE_LIMIT',300);
        }

        /*
         * Fetch data from API endpoint. Repsonses are limited to 300 items. If the amount of items
         * equals the page size (limit), we can assume that more items are available. We keep
         * fetching data with offset adjusted with page size until no items are received,
         * or number of received items is less than page limit.
         */

        $itemcount = 0;
        $limit = $queryParams['limit'];
        // $result = collect([]);
        // $result = new Collection();
        $result = [];
        do
        {
            // build full http request url incl. query parameters

            $fullRequestUrl = ApiConctroller::build_rentman_url($endpoint, $queryParams);

            Log::debug($fullRequestUrl);
            // Send the request
            $response = Http::withToken($token)->get($fullRequestUrl);
            $status = $response->status(); // should be 200

            if ($response->successful())
            {
                $jsonData = $response->json();
                // Log::debug($jsonData['data']);



                $data = $response['data'];
                Log::debug($data);

                $itemCount = (int)count($data);
                $limit = (int)$response['limit'];

                // Log::info($response['data']);

                $queryParams['offset'] += $limit;

                $result = array_merge($result,$data);
                // $result->merge($data);
            } else
            {
                // Log de fout en stop de verwerking indien nodig
                Log::error('API Call Failed', ['url' => $fullRequestUrl, 'status' => $response->status(), 'errorMessage' => $response->body()]);
                break; // Stop de loop bij een fout
            }
        }
        while ($limit === $itemCount);

        // Log::info($result);
        return collect($result);

    }

    public function fetchxxx(Request $request)
    {
        $url = 'https://api.rentman.net/invoices';
        $token = env('RENTMAN_BEARER_TOKEN', '*** Add token in .env as RENTMAN_BEARER_TOKEN ***');
        $limit = env('RENTMAN_PAGE_LIMIT', 10);
        $offset = 0;

        $queryParams = [
            'limit' => $limit,
            'offset' => $offset
        ];

        // build full http request url incl. query parameters
        $queryString = http_build_query($queryParams);
        $fullRequestUrl = $url . '?' . $queryString;

        // Send the request
        // $response = Http::withToken($token)->get($url,$queryParams);
        $response = Http::withToken($token)->get($fullRequestUrl);

        if ($response->successful()) {
            $invoices = $response->json();

            $response4view = $response->json([
                'status' => 'success',
                'data' => $invoices
            ], 200);

            $response4view = $response;
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
            $response4view = response()->json([
                'url' => $fullRequestUrl,
                'status' => 'error',
                'message' => 'We could not fetch the data from the external API',
                'statusCode' => $statusCode,
                'details' => $errorMessage
            ], $statusCode);
        }

        // dd(
        //     $response,
        //     $fullRequestUrl,
        //     $response->object()
        // );

        return view('rentman.invoice.fetch', [
            'response' => $response,
            'fullRequestUrl' => $fullRequestUrl,
            'responseObject' => $response->object(),
            'response' => $response4view,
            'token' => $token
        ]);
    }
    //

    /**
     * Helper functie om de API response uniform te maken.
     * Zorgt ervoor dat we ALTIJD een array van items terugkrijgen,
     * zelfs als de API maar één enkel object teruggeeft of een 'data' wrapper gebruikt.
     * * @param array $payload De ruwe JSON response body
     * @return array Een lijst van items (indexed array)
     */
    protected function normalizeResponse(array $payload): array
    {
        // 1. Pak de 'data' wrapper uit als die bestaat (Rentman standaard)
        $data = isset($payload['data']) ? $payload['data'] : $payload;

        // 2. Als de data leeg is, retourneer lege array
        if (empty($data)) {
            return [];
        }

        // 3. Controleer of het een lijst is of één object
        // array_is_list() retourneert true voor [0 => {}, 1 => {}]
        // en false voor ['id' => 1, 'name' => 'Project']
        if (!array_is_list($data)) {
            // Het is één enkel object (associatieve array), stop het in een lijst
            return [$data];
        }

        // Het is al een lijst van projecten
        return $data;
    }
}
