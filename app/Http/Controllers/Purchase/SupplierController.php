<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('purchase.suppliers.index');
    }

    public function getSupplierDataxxx(Request $request)
    {
        // Tabulator stuurt 'size' (per pagina) en 'page' (huidige pagina) automatisch mee
        $perPage = $request->get('size', 10);

        // If using Tabulator's remote pagination, it sends 'page' and 'size' parameters
        $suppliers = Supplier::paginate($perPage);

        return response()->json([
            'last_page' => $suppliers->lastPage(),
            'data' => $suppliers->items(),
        ]);
    }

    public function getSupplierData(Request $request)
    {
        $query = Supplier::query();

        // 1. Filtering (Let op: 'filter' conform jouw URL)
        if ($request->has('filter')) {
            foreach ($request->input('filter') as $f) {
                $field = $f['field'];
                $value = $f['value'];

                if (in_array($field, ['id', 'name', 'email', 'phone', 'address'])) {
                    // We gebruiken % voor en achter de waarde voor een 'contains' zoekopdracht
                    $query->where($field, 'like', '%' . $value . '%');
                }
            }
        }

        // 2. Sorting (Multi-column support)
        if ($request->has('sort')) {
            foreach ($request->input('sort') as $s) {
                $field = $s['field'];
                $direction = $s['dir'];

                if (in_array($field, ['id', 'name', 'email', 'phone', 'address'])) {
                    $query->orderBy($field, $direction);
                }
            }
        }

        // 3. Pagination
        $perPage = $request->input('size', 10);
        $paginator = $query->paginate($perPage);

        return response()->json([
            'last_page' => $paginator->lastPage(),
            'data' => $paginator->items(),
            'total' => $paginator->total(), // Optioneel maar handig voor info in de footer
        ]);
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSupplier(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
