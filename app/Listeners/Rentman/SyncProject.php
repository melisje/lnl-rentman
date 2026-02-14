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

    // /**
    //  * Sync the project in the DB with Rentman
    //  * We'll call the Rentman API to fetch the current project data
    //  * If the project does not exist in the DB, create it with the Rentman data
    //  * Otherwise update the existing data in the DB with the fetched data
    //  */
    // public function sync_projects($account, $item)
    // {
    //     Log::info("@@@ syncing project...");
    //     // build endpoint url
    //     $endpoint = $item->ref;

    //     // Calling endpoint
    //     $data = $this->rentmanApiService->get_rentman_endpoint($account, $endpoint);

    //     Log::debug("PROJECT data: " . json_encode($data));

    //     $project = Project::updateOrCreate(
    //         // Deel 1: De unieke velden om het record te vinden
    //         [
    //             'account' => $account,
    //             'rm_id'   => $data['id'],
    //         ],
    //         // Deel 2: De velden die ingevuld of bijgewerkt moeten worden
    //         [
    //             'created'               => $data['created'],
    //             'modified'              => $data['modified'],
    //             'creator'               => $data['creator'],
    //             'updateHash'            => $data['updateHash'],
    //             'displayname'           => $data['displayname'],
    //             'name'                  => $data['name'],
    //             'reference'             => $data['reference'],
    //             'number'                => $data['number'],
    //             'planperiod_start'      => $data['planperiod_start'],
    //             'planperiod_end'        => $data['planperiod_end'],
    //             'usageperiod_start'     => $data['usageperiod_start'],
    //             'usageperiod_end'       => $data['usageperiod_end'],
    //             'equipment_period_from' => $data['equipment_period_from'],
    //             'equipment_period_to'   => $data['equipment_period_to'],
    //             'account_manager'       => $data['account_manager'],
    //             'customer'              => $data['customer'],
    //             'cust_contact'          => $data['cust_contact'],
    //             'loc_contact'           => $data['loc_contact'],
    //             'project_total_price'   => $data['project_total_price'],
    //             'custom'                => json_encode($data['custom']),
    //         ]
    //     );

    //     // Now we need to fetch also the subprojects for this project.
    //     // Beside the subproject info, they are also needed to
    //     // 'calculate' the status of the project. If all
    //     // statusses of the subprojects are the same,
    //     // the project status is calculated as the
    //     // same status, if the subprojects'
    //     // statuses differs, the project
    //     // status will be 'Varies'.

    //     $this->sync_subprojects($account, $project);
    // }

    // /**
    //  * Sync the subprojects linked to the given $project to the DB
    //  */
    // public function sync_subprojects(string $account, Project $project)
    // {
    //     $subprojects = $this->rentmanApiService->get_subprojects($account, $project->rm_id);

    //     // loop through the subprojects
    //     foreach($subprojects as $subproject)
    //     {
    //         Log::debug("SUBPROJECT data: ". json_encode($subproject));

    //         $model = SubProject::updateOrCreate(
    //             [
    //                 'account' => $account,
    //                 'rm_id' => $subproject['id'],
    //             ],
    //             [
    //                 'projects_id' => $project->id,
    //                 'created' => $subproject['created'],
    //                 'modified' => $subproject['modified'],
    //                 'creator' => $subproject['creator'],
    //                 'displayname' => $subproject['displayname'],
    //                 'project' => $subproject['project'],
    //                 'order' => $subproject['order'],
    //                 'name' => $subproject['name'],
    //                 'status' => $subproject['status'],
    //                 'is_template' => $subproject['is_template'],
    //                 'location' => $subproject['location'],
    //                 'loc_contact' => $subproject['loc_contact'],
    //                 'insurance_rate' => $subproject['insurance_rate'],
    //                 'discount_rental' => $subproject['discount_rental'],
    //                 'discount_sale' => $subproject['discount_sale'],
    //                 'discount_crew' => $subproject['discount_crew'],
    //                 'discount_transport' => $subproject['discount_transport'],
    //                 'discount_additional_costs' => $subproject['discount_additional_costs'],
    //                 'discount_subproject' => $subproject['discount_subproject'],
    //                 'discount_fixed' => $subproject['discount_fixed'],
    //                 'discount_fixed_amount' => $subproject['discount_fixed_amount'],
    //                 'fixed_price' => $subproject['fixed_price'],
    //                 'in_planning' => $subproject['in_planning'],
    //                 'in_financial' => $subproject['in_financial'],
    //                 'asset_location_from' => $subproject['asset_location_from'],
    //                 'already_invoiced' => $subproject['already_invoiced'],
    //                 'usageperiod_start' => $subproject['usageperiod_start'],
    //                 'usageperiod_end' => $subproject['usageperiod_end'],
    //                 'planperiod_start' => $subproject['planperiod_start'],
    //                 'planperiod_end' => $subproject['planperiod_end'],
    //                 'weight' => $subproject['weight'],
    //                 'power' => $subproject['power'],
    //                 'current' => $subproject['current'],
    //                 'purchasecosts' => $subproject['purchasecosts'],
    //                 'volume' => $subproject['volume'],
    //                 'equipment_period_from' => $subproject['equipment_period_from'],
    //                 'equipment_period_to' => $subproject['equipment_period_to'],
    //                 'updateHash' => $subproject['updateHash'],
    //                 'custom' => json_encode($subproject['custom']),
    //             ]
    //         );
    //         Log::info("~~~ SubProject model $model->rm_id created or updated: id=$model->id");
    //     }
    // }

    // public function delete_project($account, $item)
    // {
    //     Log::info("@@@ Deleting project $item +++");

    //     // find project in DB and delete it
    //     Project::where(['rm_id' => $item, 'account'=>$account])
    //         ->delete();
    // }
}
