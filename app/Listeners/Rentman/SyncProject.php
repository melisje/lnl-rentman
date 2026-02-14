<?php

namespace App\Listeners\Rentman;

use App\Events\Rentman\WebhookReceived;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SyncProject implements ShouldQueue // make it async!
{
    /**
     * Create the event listener.
     * @param RentmanApiService inject the service and make it
     * available in this class as $this->rentmanApiService
     */
    public function __construct(protected RentmanApiService $rentmanApiService)
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
            Log::info("SyncProject listener activated ... ");

            // since the items is an array of all affected
            // items we loop through the list
            foreach ($items as $item)
            {
                // check the eventType and act accordingly
                switch($eventType)
                {
                    case 'create':
                        Log::info('SyncProject listener - create');
                        $this->sync_projects($account, $item);
                        break;
                    case 'update':
                        Log::info('SyncProject listener - update');
                        $this->sync_projects($account,$item);
                        break;
                    case 'delete':
                        Log::info('SyncProject listener - delete');
                        $this->delete_project($account,$item);
                        break;
                    default:
                        Log::info("SyncProject listener - unknown eventType: $eventType");
                        break;
                }
            }
            // all done
            Log::info("SyncProject listener finished ... ");
        }
    }

    /**
     * Sync the project in the DB with Rentman
     * We'll call the Rentman API to fetch the current project data
     * If the project does not exist in the DB, create it with the Rentman data
     * Otherwise update the existing data in the DB with the fetched data
     */
    public function sync_projects($account, $item)
    {
        Log::info("+++ syncing project +++");
        // build endpoint url
        $endpoint = $item->ref;

        // Calling endpoint
        $data = $this->rentmanApiService->get_rentman_endpoint($account, $endpoint);

        // $project = $this->rentmanApiService->sync_project($account,$item->id);
        // Log::info("PROJECT: $itemt->id");
        // Log::info("PROJECT: $item->ref");

        Log::info("PROJECT data: " . json_encode($data));

        // update or insert the project data in the DB
        $project = Project::upsert(
            [
                'account' => $account,
                'rm_id' => $data['id'],
                'created' => $data['created'],
                'modified' => $data['modified'],
                'creator' => $data['creator'],
                'updateHash' => $data['updateHash'],
                'displayname' => $data['displayname'],
                'name' => $data['name'],
                'reference' => $data['reference'],
                'number' => $data['number'],
                'planperiod_start' => $data['planperiod_start'],
                'planperiod_end' => $data['planperiod_end'],
                'usageperiod_start' => $data['usageperiod_start'],
                'usageperiod_end' => $data['usageperiod_end'],
                'equipment_period_from' => $data['equipment_period_from'],
                'equipment_period_to' => $data['equipment_period_to'],
                'account_manager' => $data['account_manager'],
                'customer' => $data['customer'],
                'cust_contact' => $data['cust_contact'],
                'loc_contact' => $data['loc_contact'],
                'project_total_price' => $data['project_total_price'],
                'custom' => json_encode($data['custom']),
            ],
            [
                'account' => $account,
                'rm_id' => $data['id'],
            ]
        );
    }

    public function delete_project($account, $item)
    {
        Log::info("+++ deleting project $item +++");

        // find project in DB and delete it
        Project::where(['rm_id' => $item, 'account'=>$account])
            ->delete();
    }
}
