<?php

namespace App\Listeners\Rentman;

use App\Events\Rentman\WebhookReceived;
use App\Models\Rentman\Project;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\ProjectsService;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SyncProject implements ShouldQueue // make it async!
{
    /**
     * Create the event listener.
     * @param RentmanApiService inject the service and make it
     * available in this class as $this->rentmanApiService
     */
    public function __construct(protected RentmanApiService $rentmanApiService, protected ProjectsService $projectService)
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
        if ($itemType === 'Project')
        {
            Log::info("@@ SyncProject listener activated ... ");

            // since the items is an array of all affected
            // items we loop through the list
            foreach ($items as $item)
            {
                // check the eventType and act accordingly
                switch($eventType)
                {
                    case 'create':
                    case 'update':
                        Log::info('@@ SyncProject listener - create/update');
                        $this->projectService->sync_projects($account,$item->ref);
                        break;
                    case 'delete':
                        Log::info('@@ SyncProject listener - delete');
                        $this->projectService->delete_project($account,$item);
                        break;
                    default:
                        Log::warning("?? SyncProject listener - unknown eventType: $eventType");
                        break;
                }
            }
            // all done
            Log::info("@@SyncProject listener finished ... ");
        }
    }
}
