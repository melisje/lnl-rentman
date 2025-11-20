<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function fetch(Request $request)
    {
        $url = 'https://api.rentman.net/invoices';
        $token = env('RENTMAN_BEARER_TOKEN','*** Add token in .env as RENTMAN_BEARER_TOKEN ***');
        $limit = env('RENTMAN_PAGE_LIMIT',10);
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

        if ($response->successful())
        {
            $invoices = $response->json();

            $response4view = $response->json([
                'status' => 'success',
                'data' => $invoices
            ], 200);

            $response4view = $response;
        }
        else
        {
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Haal de sorteerparameters op uit de URL (met veilige defaults)
        $sortBy = $request->get('sort', 'number'); // Sorteer standaard op 'name'
        $sortDirection = $request->get('direction', 'asc'); // Standaard oplopend

        // 2. Definieer toegestane kolommen om te voorkomen dat kwaadwillende code wordt uitgevoerd
        $allowedColumns = ['id', 'number', 'displayname', 'customer', 'account_manager', 'created', 'modified'];

        // Beveiliging: Als de kolom niet is toegestaan, gebruik dan de default
        if (!in_array($sortBy, $allowedColumns)) {
            $sortBy = 'number';
        }

        // Zorg ervoor dat de richting 'asc' of 'desc' is
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        // 3. Pas de sortering en paginering toe op de query
        $invoices = Invoice::orderBy($sortBy,$sortDirection)
        ->paginate(15) // paginate
        ->withQueryString(); // Important: Remain the sort/direction parametes from the pagination links

        // 4. Stuur alle data naar de view
        return view('rentman.invoice.index',compact('invoices','sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return 'STORE';
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    public function show(Request $request, Invoice $invoice)
    {
        // return $invoice;
        // return 'SHOW';
        return view('rentman.invoice.show')
            ->with('invoice', $invoice)
        ;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return 'EDIT';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'UPDATE';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return 'DESTROY';
    }
}
