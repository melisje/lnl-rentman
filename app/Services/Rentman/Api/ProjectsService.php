<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\CustomField;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Project;
use App\Models\Rentman\SubProject;
use Illuminate\Support\Facades\Log;

class ProjectsService
{
  public function __construct(protected RentmanApiService $rentmanApiService, protected CrewService $crewService)
  {

  }

  /**
   * Sync the project in the DB with Rentman
   * We'll call the Rentman API to fetch the current project data
   * If the project does not exist in the DB, create it with the Rentman data
   * Otherwise update the existing data in the DB with the fetched data
   */
  public function sync_projects($account, string $ref)
  {
    Log::info("@@@ syncing project...");
    // build endpoint url
    $endpoint = $ref;

    // Calling endpoint
    $data = $this->rentmanApiService->get_rentman_endpoint($account, $endpoint);

    Log::debug("PROJECT data: " . json_encode($data));

    $project = Project::updateOrCreate(
      // Deel 1: De unieke velden om het record te vinden
      [
        'account' => $account,
        'rm_id'   => $data['id'],
      ],
      // Deel 2: De velden die ingevuld of bijgewerkt moeten worden
      [
        'created'               => $data['created'],
        'modified'              => $data['modified'],
        'creator'               => $data['creator'],
        'updateHash'            => $data['updateHash'],
        'displayname'           => $data['displayname'],
        'name'                  => $data['name'],
        'reference'             => $data['reference'],
        'number'                => $data['number'],
        'planperiod_start'      => $data['planperiod_start'],
        'planperiod_end'        => $data['planperiod_end'],
        'usageperiod_start'     => $data['usageperiod_start'],
        'usageperiod_end'       => $data['usageperiod_end'],
        'equipment_period_from' => $data['equipment_period_from'],
        'equipment_period_to'   => $data['equipment_period_to'],
        'account_manager'       => $data['account_manager'],
        'customer'              => $data['customer'],
        'cust_contact'          => $data['cust_contact'],
        'loc_contact'           => $data['loc_contact'],
        'project_total_price'   => $data['project_total_price'],
        'custom'                => json_encode($data['custom']),
      ]
    );

    // Find the values for the custom_fields that are  defined in the rm_customfield_mappings table.
    $custom = $data['custom'] ?? [];  // retrieved custom fields from Rentman API response

  // find the project manager in the custom fields and store the reference to the project manager in the project model
    $project_manager_reference = $this->get_projectmanager_refrence($account, $project);
    $project->project_manager = $project_manager_reference;
    Log::info("+-+-+- Project manager reference for project $project->rm_id on account $account: $project_manager_reference");


    // Now we need to fetch also the subprojects for this project.
    // Beside the subproject info, they are also needed to
    // 'calculate' the status of the project. If all
    // statusses of the subprojects are the same,
    // the project status is calculated as the
    // same status, if the subprojects'
    // statuses differs, the project
    // status will be 'Varies'.

    $this->sync_subprojects($account, $project);

    // Calculate the status of the project and save it in the status field
    $project->status = $project->calculated_status;
    $project->save();
    Log::info("~~> Project status for project $project->rm_id calculated on account $account: $project->status");

    // Return the project model (with the updated status and custom field values) to the caller
    return $project;
  }

  /**
   * Sync the subprojects linked to the given $project to the DB
   */
  public function sync_subprojects(string $account, Project $project)
  {
    $subprojects = $this->rentmanApiService->get_subprojects($account, $project->rm_id);

    // loop through the subprojects
    foreach ($subprojects as $subproject) {
      Log::debug("SUBPROJECT data: " . json_encode($subproject));

      $model = SubProject::updateOrCreate(
        [
          'account' => $account,
          'rm_id' => $subproject['id'],
        ],
        [
          'projects_id' => $project->id,
          'created' => $subproject['created'],
          'modified' => $subproject['modified'],
          'creator' => $subproject['creator'],
          'displayname' => $subproject['displayname'],
          'project' => $subproject['project'],
          'order' => $subproject['order'],
          'name' => $subproject['name'],
          'status' => $subproject['status'],
          'is_template' => $subproject['is_template'],
          'location' => $subproject['location'],
          'loc_contact' => $subproject['loc_contact'],
          'insurance_rate' => $subproject['insurance_rate'],
          'discount_rental' => $subproject['discount_rental'],
          'discount_sale' => $subproject['discount_sale'],
          'discount_crew' => $subproject['discount_crew'],
          'discount_transport' => $subproject['discount_transport'],
          'discount_additional_costs' => $subproject['discount_additional_costs'],
          'discount_subproject' => $subproject['discount_subproject'],
          'discount_fixed' => $subproject['discount_fixed'],
          'discount_fixed_amount' => $subproject['discount_fixed_amount'],
          'fixed_price' => $subproject['fixed_price'],
          'in_planning' => $subproject['in_planning'],
          'in_financial' => $subproject['in_financial'],
          'asset_location_from' => $subproject['asset_location_from'],
          'already_invoiced' => $subproject['already_invoiced'],
          'usageperiod_start' => $subproject['usageperiod_start'],
          'usageperiod_end' => $subproject['usageperiod_end'],
          'planperiod_start' => $subproject['planperiod_start'],
          'planperiod_end' => $subproject['planperiod_end'],
          'weight' => $subproject['weight'],
          'power' => $subproject['power'],
          'current' => $subproject['current'],
          'purchasecosts' => $subproject['purchasecosts'],
          'volume' => $subproject['volume'],
          'equipment_period_from' => $subproject['equipment_period_from'],
          'equipment_period_to' => $subproject['equipment_period_to'],
          'updateHash' => $subproject['updateHash'],
          'custom' => json_encode($subproject['custom']),
        ]
      );
      Log::info("~~~ SubProject model $model->rm_id created or updated: id=$model->id");
    }
  }

  /**
   * Find and delete the subproject from the DB
   * @param string $account The Rentman account where the project belongs to
   * @param string $rm_id The Rentman id of the Project to be deleted
   */
  public function delete_project($account, $rm_id)
  {
    Log::info("@@@ Deleting project $rm_id +++");

    // find project in DB and delete it
    Project::where(['rm_id' => $rm_id, 'account' => $account])
      ->delete();
  }

  /**
   * Find and delete the subproject from the DB
   * @param string $account The Rentman account where the subproject belongs to
   * @param string $rm_id The Rentman id of the Subproject to be deleted
   */
  public function delete_subproject($account, $rm_id)
  {
    Log::info("@@@ Deleting subproject $rm_id +++");

    // Find the subproject in DB and delete it
    SubProject::where(['rm_id' => $rm_id, 'account' => $account])
      ->delete();
  }

  /**
   * Find the custom field mappings for the given account and map
   * the values from the $custom array to the project model
   * step 1: find the customfields for this account
   * step 2: fetch the values van $custom
   * step 3: store the values in the project model and save it
   *
   */
  public function process_customfields(string $account, array $custom, Project $project)
  {
    // step 1: find the customfields for this account
    $customfield_mappings = CustomFieldMapping::where('account', $account)->get();

    // step 2: fetch the values van $custom and store them in the project model
    foreach ($customfield_mappings as $mapping) {
      $custom_field_name = $mapping->rm_customfield_name;
      $project_field_name = $mapping->project_field_name;

      // check if the custom field value exists in the $custom array
      if (isset($custom[$custom_field_name])) {
        // store the value in the project model
        $project->$project_field_name = $custom[$custom_field_name];
        Log::info("Mapped custom field '$custom_field_name' to project field '$project_field_name' with value: " . $custom[$custom_field_name]);
      } else {
        Log::warning("Custom field '$custom_field_name' not found in Rentman API response for project ID {$project->rm_id} on account '$account'.");
      }
    }

    // step 3: save the project model with the updated custom field values
    $project->save();
  }

  public function get_projectmanager_refrence(string $account, Project $project) : string|null
  {
    $custom = json_decode($project->custom); // $custom is now an object (stdclass) where the properties are the customfield names and the values are the customfield values

    // first we find the id of the customfield that belongs to project_manager in the given account.
    // We can find this in the rm_customfield_mappings table where we have the mapping between the
    // customfield_id (the id of the custom field in Rentman), the account and the
    // project_field_name. Once we have the customfield_id, we can find the
    // corresponding value in the $custom object and return it as project
    // manager reference.
    $custom_field_mapping = CustomFieldMapping::where('account', $account)
      ->where('customfield_id', 'project_manager')
      ->with('customField')
      ->first();
    $custom_name = $custom_field_mapping ? $custom_field_mapping->custom_name : null; // will be like custom_xx where xx is the rm_id of the custom field in Rentman

    // fetch the value from the customfields as received from the Project
    // endpoint in the Rentman API response. The $custom_name is the name
    // of the property in the $custom object that contains the value for
    // the project manager custom field.
    $pm_value = data_get($custom, $custom_name);

    // For account ledvisions is a simple dropdown list with mappings to fixed names
    // We now know the received value as stored in Rentman. Now we map this on
    // real references to crew members in our local database. For account
    // llstageservice the project manager custom field is based on real
    // crew member values, so we can directly use the value as reference
    // to find the corresponding crew member in our local database.
    // For account ledvisions they do not use real crew members
    // references as value for the pm custom field. They should
    // better change this like this is done in llstageservice,
    // but for now we need to work with a fixed mapping of
    // the dropdown values to the crew members in our
    // local database.
    switch ($account) {
      case 'llstageservice':
        // For account llstageservice the project manager custom field is based on real crew member values
        // Make sure the crew member is synced to the local database
        $crew = $this->crewService->sync_crew_member($account, $pm_value);
        $reference = "/crew/" . $crew->rm_id; // we can use the displayname of the crew member as project manager name
        break;

      case 'ledvisions':
        // ledvision does not use real crew members references as value for the reference custom field.
        // They should better change this like this is done in llstageservice, but for now we
        // need to work with a fixed mapping of the dropdown values to the crew members in
        // our local database.
        $reference = $pm_value; // this is a simple string value that we can use as is
        switch ($pm_value) {
          case 0:
            $reference = '/crew/33'; // 'Nicolas Pairon';
            break;
          case 1:
            $reference = '/crew/303';  //'Jasper Vanhees';
            break;
          case 2:
            $reference = '/crew/294';  //Constantin (Costy) Astancai';
            break;
          default:
            $reference = null; // 'Unknown';
        }
        break;
    }


    return $reference;
  }
}