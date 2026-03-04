<?php

namespace App\Listeners\Rentman;

use App\Events\Rentman\WebhookReceived;
use App\Models\Rentman\Project;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Services\Rentman\Api\ProjectsService;

class SyncSubProject implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected RentmanApiService $rentmanApiService,
        protected ProjectsService $projectService)
    {
        //
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
        if ($itemType === 'Subproject')
        {
            Log::info("@@ SyncSubProject listener activated ... ");

            // if a subproject is created or updated, it will always have a
            // parent Project. We loop through the list of subprojects and
            // remember the parents (should normally be 1 per webhookcall)

            $parentIds = collect($payload->items)
                ->pluck('parent.id') // Gebruik 'dot-notation' om diep in de array te grijpen
                ->unique()           // Verwijder dubbele ID's (in dit geval blijft er één keer 729 over)
                ->values()           // Reset de array keys
                ->all()             // Zet de collectie weer om naar een simpele PHP array
            ;

            // since the items is an array of all affected
            // items we loop through the list
            switch ($eventType)
            {
                case 'create':
                case 'update':
                    Log::info("@@ SyncSubProject listener - create/update");

                    foreach( $items as $item)
                    {
                        // check if status is changed and if notification should be sent
                        $this->projectService->check_subproject_status_change($account, $item->id);
                    }

                    // Sync the parent projects
                    $this->sync_parent_project($account, $parentIds);
                    break;
                case 'delete':
                    Log::info("@@ SyncSubProject listener - delete");
                    // loop through the items to be deleted (should be 1, in an array)

                    foreach($items as $rm_id)
                    {
                        // Since the webhookcall does not provide the partent
                        // project id and we need to update this, we will
                        // fetch the subproject from the db and find
                        // the parentid there.
                        $subproject = SubProject::firstWhere(
                            [
                                'account' => $account,
                                'rm_id' => $rm_id
                            ]
                        );

                        // if the subproject is found, fetch the parent projects rm_id value
                        $parentids = $subproject ? [$subproject->parent_project->rm_id] : [];

                        // delete the subproject
                        $this->projectService->delete_subproject($account,$rm_id);
                    }
                    // Sync the parent project and update the project status
                    $this->sync_parent_project($account,$parentids);
                    break;
                default:
                    Log::warning("?? SyncSubProject listener - unknown eventType: $eventType");
                    break;
            }
            // all done
            Log::info("@@ SyncSubProject listener finished ... ");
        }
    }

    public function sync_parent_project(string $account,array $parentIds)
    {
        foreach ($parentIds as $parentid) {
            // build the endpoint reference for the parent project
            $ref = "/projects/$parentid";

            // sync the parent project and all its subprojects
            $this->projectService->sync_projects($account, $ref);
        }
    }
}
