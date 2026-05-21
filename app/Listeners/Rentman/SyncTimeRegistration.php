<?php

namespace App\Listeners\Rentman;

use App\Events\Rentman\WebhookReceived;
use App\Models\Rentman\Project;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\ProjectsService;
use App\Services\Rentman\Api\RentmanApiService;
use App\Services\Rentman\Api\TimeRegistrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SyncTimeRegistration implements ShouldQueue // make it async!
{
    /**
     * Create the event listener.
     * @param RentmanApiService inject the service and make it
     * available in this class as $this->rentmanApiService
     */
    public function __construct(protected RentmanApiService $rentmanApiService, protected TimeRegistrationService $timeRegistrationService)
    {
    }

    /**
     * Handle the event.
     * @param WebhookReceived event
     * The webhookcall object that is connected to the event contains
     * all the info needed:
     * + what endpoint to be called
     * + the item(s) that is/are infected
     * + the action that needs to be processed (created, updated, deleted)
     */
    public function handle(WebhookReceived $event): void
    {
        $payload = json_decode($event->webhookcall['payload']);
        $account = $payload->account;
        $itemType = $payload->itemType; // we are only interessted in Projects
        $eventType = $payload->eventType;  // create, update, delete
        $items = $payload->items; // the affected items in array

        // Check if this is a project related webhookcall
        if ($itemType === 'TimeRegistration')
        {
            Log::info("@@ SyncTimeRegistration listener activated ... ");

            // since the items is an array of all affected
            // items we loop through the list
            foreach ($items as $item)
            {
                // check the eventType and act accordingly
                switch($eventType)
                {
                    case 'create':
                    case 'update':
                        Log::info('@@ SyncTimeRegistration listener - create/update');
                        $this->timeRegistrationService->sync_timeregistration($account,$item->ref);
                        break;
                    case 'delete':
                        Log::info('@@ SyncTimeRegistration listener - delete');
                        $this->timeRegistrationService->delete_timeregistration($account,$item);
                        break;
                    default:
                        Log::warning("?? SyncTimeRegistration listener - unknown eventType: $eventType");
                        break;
                }
            }
            // all done
            Log::info("@@SyncTimeregistration listener finished ... ");
        }
    }
}
