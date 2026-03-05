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

            $model = WebhookCall::query()
                ->leftJoin('rm_crew', function ($join) {
                    $join->on('rm_webhook_calls.user', '=', 'rm_crew.rm_id')
                        ->on('rm_webhook_calls.account', '=', 'rm_crew.account');
                })
                ->where('rm_webhook_calls.account', session('current_account'))
                ->select([
                    'rm_webhook_calls.id',
                    'rm_webhook_calls.account',
                    'rm_webhook_calls.ip',
                    'rm_webhook_calls.user',
                    'rm_webhook_calls.itemType',
                    'rm_webhook_calls.eventType',
                    'rm_webhook_calls.eventDate',
                    'rm_webhook_calls.created_at',
                    'rm_webhook_calls.items',
                    'rm_crew.displayname as user_name' // Dit wordt direct 'user_name' in je JSON
                ]);

            return DataTables::of($model)
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->editColumn('eventDate', function ($row) {
                    return $row->eventDate->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($row)
                {
                    $url = route('webhookcall.show',$row->id);
                    return '<a href="'. $url . '" class="btn btn-sm btn-info text-white shadow-sm"><i class="bi bi-search"></i></a>';
                })
                ->rawColumns(['action']) // Zorg dat HTML gerenderd wordt
                ->make(true);
        }
        else
        {
            // Dit is de view die geopend wordt bij de start.
            return view('rentman.webhook.index');
        }


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
