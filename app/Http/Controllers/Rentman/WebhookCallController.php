<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Models\Rentman\WebhookCall;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class WebhookCallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index0()
    {
        $items = WebhookCall::orderBy('id','desc')
            ->paginate(25)
        ;

        return view('rentman.webhook.index',compact('items'));

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Gebruik select() om niet de zware 'payload' kolom op te halen als dat niet nodig is
            $model = WebhookCall::select(['id', 'account', 'ip', 'user', 'itemType','eventType', 'eventDate', 'created_at']);

            return DataTables::of($model)
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->editColumn('status', function ($row) {
                    $class = $row->status == 'success' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $class . '">' . $row->status . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-info text-white shadow-sm">Details</button>';
                })
                ->rawColumns(['status', 'action']) // Zorg dat HTML gerenderd wordt
                ->make(true);
        }

        $items = WebhookCall::orderBy('id', 'desc')
            ->paginate(25);

        return view('rentman.webhook.index', compact('items'));

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
    public function show(WebhookCall $webhookcall)
    {
        return view('rentman.webhook.show', compact('webhookcall'));
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
