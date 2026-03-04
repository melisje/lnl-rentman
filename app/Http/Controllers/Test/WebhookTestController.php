<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Status;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\ProjectsService;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Js;
use Illuminate\Support\Facades\Log;

class WebhookTestController extends Controller
{
    public function __construct(protected RentmanApiService $rmapiserv, protected ProjectsService $projectservice)
    {

    }

    // Toon de view met het formulier
    public function showForm()
    {
        return view('test.webhook-test'); // Zorg dat je view hier staat
    }

    // Verwerk de geposte JSON
    public function handleTestWebhook(Request $request)
    {
        $rawPayload = $request->input('payload');
        // dump($rawPayload);

        // Valideer of het veld niet leeg is
        if (!$rawPayload) {
            return back()->with('error', 'De payload is leeg!');
        }

        // Decodeer de JSON
        $payload = json_decode($rawPayload, false);

        // Check op JSON fouten
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()
                ->withInput() // Houdt de foute JSON in het tekstveld
                ->with('error', 'Ongeldige JSON: ' . json_last_error_msg());
        }

        // --- HIER komt je logica ---
        // Bijvoorbeeld: Geef de data door aan je eigenlijke WebhookController
        // app(WebhookController::class)->process($data);

        // dd($payload);
        // dump($payload);

        $account = $payload->account;
        // $itemType = $payload->itemType; // we are only interessted in Projects
        // $eventType = $payload->eventType;  // create, update, delete
        $items = $payload->items; // the affected items in array

        foreach($items as $item)
        {
            // check if status is changed and if notification should be sent
            $this->projectservice->check_subproject_status_change($account,$item->id);
        }

        return view('test.webhook-test')
            // ->withInput()
            ->with('success', 'Webhook succesvol gesimuleerd!')
            ->with('payload',$payload)
            ->with('rawPayload',$rawPayload)
            // ->with('account',$account)
            // ->with('itemType',$itemType)
            // ->with('eventType',$eventType)
            // ->with('items',$items)
            ;
    }
}