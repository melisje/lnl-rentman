<?php

namespace App\Services\Rentman\Api;

use App\Mail\ProjectDeletedMail;
use App\Models\Rentman\Account;
use App\Models\Rentman\CustomField;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Project;
use App\Models\Rentman\Status;
use App\Models\Rentman\SubProject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

    // find project in DB
    $projects = Project::where(['rm_id' => $rm_id, 'account' => $account])->get();

    // Send email notification
    foreach($projects as $project)
    {
        // send notification if email is set for this account
        $this->send_project_update_notificaction($account, $project);

        // Delete project
        $project->delete();
    }

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
    $subprojects = SubProject::where(['rm_id' => $rm_id, 'account' => $account])
      ->with('parent_project')
      ->get();

    // dump($rm_id);
    // dump($subprojects);

    foreach ($subprojects as $subproject)
    {
      // send notification if email is set for this account
      $this->send_project_update_notificaction($account, $subproject);

      // delete subproject
      $subproject->delete();
    }
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

  /**
   * Find the Rentman crew id for the project manager of the given project and
   * return the reference to the crew member to be stored in the project model.
   * @param string $account The Rentman account where the project belongs to
   * @param Project $project The project for which we want to find the project manager reference
   * @return string|int|null The reference to the crew member to be stored in the project model, or null if no project manager reference is found
   */
  public function get_projectmanager_refrence(string $account, Project $project) : string| int| null
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

    // if account=ledvisions, the value in the customfield is the index in the dropdown list of the project manager.
    // We have to manually map this index to the corresponding crew member's rentman id.
    switch ($account) {
      case 'llstageservice':
        Log::info("Project manager custom field value for project $project->rm_id on account $account: $pm_value (this should be a reference to a Rentman crew member)");
        break;
      case 'ledvisions':
        Log::info("Project manager custom field value for project $project->rm_id on account $account: $pm_value (this is an index in the dropdown list of the project manager, we need to map this to a crew member reference)");
        switch ($pm_value) {
          case 0:
            $pm_value = 33; // 'Nicolas Pairon';
            break;
          case 1:
            $pm_value = 303; //'Jasper Vanhees';
            break;
          case 2:
            $pm_value = 294;  //Constantin (Costy) Astancai';
            break;
          default:
            $pm_value = null; // 'Unknown';
        }
        break;
    }

      // Make sure the crew member is synced to the local database
      if ($pm_value)
      {
        $crew = $this->crewService->sync_crew_member($account, $pm_value);
        $crew_id = $crew ? $crew->rm_id : null;
        $reference = '/crew/' . $crew_id;
      } else
      {
        $reference = null;
      }

    return $reference;
  }



  /**
   * Check if the update_project_email property is set for the given account.
   * If so, send a notification to this email about the updated project.
   */
  public function send_project_update_notificaction($account, Project|SubProject $item)
  {
    // Check if project updates should be notifified by email.
    // For this we have the field project_update_email in the rm_accounts table
    $account = Account::find($account);
    $to_email = $account->project_update_email;

    if ($to_email) {
      Log::info("~~~> Sending notification email to $to_email");
      Mail::to($to_email)->send(new ProjectDeletedMail($item));
    }
  }


  /**
   * Check if the status of a subproject is changed and a notification should be sent.
   * @param $account - the account that should be used
   * @param $item - the subproject
   */
  public function check_subproject_status_change($account, $rm_id)
  {
    //  find the subproject in the db
    $local_subproject = SubProject::firstWhere([
      'account' => $account,
      'rm_id' => $rm_id
    ]);

    // old status
    $old_status = basename($local_subproject->status); // /statuses/6 -> 6

    // Fetch the subproject from Rentman and find the new status
    $rentman_subproject = (object) $this->rentmanApiService->get_subproject($account, $rm_id);
    $new_status = basename($rentman_subproject->status);  // /statuses/6 -> 6

    // Check if the status is changed
    if ($old_status != $new_status)
    {
      //Find the Status object for the fetched subproject
      $status = Status::firstWhere([
        'account' => $account,
        'rm_id' => $new_status
      ]);

      Log::info("~~~> The new status of subproject $local_subproject->name is " . __($status->name));

      // We mark the statuses in the DB that should send a notification
      if ($status->notify)
      {
        $this->send_project_update_notificaction($account, $local_subproject);
      }
    }
  }

}