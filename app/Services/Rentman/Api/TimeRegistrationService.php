<?php

namespace App\Services\Rentman\Api;

use App\Mail\ProjectDeletedMail;
use App\Models\Rentman\ProjectCrew;
use App\Models\Rentman\Account;
use App\Models\Rentman\Crew;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\LeaveType;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectFunction;
use App\Models\Rentman\Status;
use App\Models\Rentman\SubProject;
use App\Models\Rentman\TimeRegistration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class TimeRegistrationService
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
  public function sync_timeregistration($account, string $ref)
  {
    Log::info("@@@ syncing timeregistration...");
    // build endpoint url
    $endpoint = $ref;

    // Calling endpoint
    $data = $this->rentmanApiService->get_rentman_endpoint($account, $endpoint);

    Log::debug("TIME REGISTRATION data: " . json_encode($data));

    $fillables = (new TimeRegistration)->getFillable();

    $fillableData = Arr::only($data, $fillables); // Deel 2: De data om in te vullen of bij te werken
    // dump($fillableData);

    $model = TimeRegistration::updateOrCreate(
      // Deel 1: De unieke velden om het record te vinden
      [
        'account' => $account,
        'rm_id'   => $data['id'],
      ],
      $fillableData // Deel 2: De data om in te vullen of bij te werken
    );

    // Process custom fields
    $this->processCustomFields($account, $data, $model);

    // Find crewmember_id of this time registration
    $model->crewmember_id = Crew::where('account', $account)
      ->where('rm_id', basename($data['crewmember']) ?? null)
      ->value('id');

    // Find crewmember_id of this time registration
    $model->leavetype_id = LeaveType::where('account', $account)
      ->where('rm_id', basename($data['leavetype']) ?? null)
      ->value('id');

    $model->save(); // Sla het model op nadat de custom fields zijn toegevoegd

    dump($model);
    return $model;
  }

  /**
   * Find and delete the subproject from the DB
   * @param string $account The Rentman account where the project belongs to
   * @param string $rm_id The Rentman id of the Project to be deleted
   */
  public function delete_timeregistration($account, $rm_id)
  {
    Log::info("@@@ Deleting timeregistration $rm_id +++");

    // find project in DB
    $models = TimeRegistration::where(['account' => $account, 'rm_id' => $rm_id])->get();

    // Send email notification
    foreach ($models as $model) {

      // Delete project
      $model->delete();
    }
  }

  /**
   * Process custom fields for a given item and model.
   */
  public function processCustomFields(string $account, array $item, $model): void
  {
    // List model attributes
    $fields = $model->getAttributes();

    // process custom fields
    $customFields = $item['custom'] ?? [];

    // Loop through custom fields and find the mapping for each field,
    // then save the value in the corresponding model fields.
    foreach ($customFields as $key => $value) {
      // Find the custom field mapping for this account
      $cf_rm_id = (int)Str::afterLast($key, '_'); // Assuming the key is something like "custom_field_123", we extract "123" as the RMID
      $custom_field_mapping = CustomFieldMapping::withoutGlobalScope(AccountScope::class)
        ->where('account', $account)
        ->where('rm_id', $cf_rm_id)->value('customfield_id');

      // Check if the mapping exists and if the corresponding field is fillable in the model
      if (array_key_exists($custom_field_mapping, $fields)) {
        $model->$custom_field_mapping = $value;
      }

      if (array_key_exists($key, $fields)) {
        // Het attribuut is aanwezig in de huidige instantie
        $model->$key = $value;
      }

      $model->save();

      // dump("$model->displayname: $cf_rm_id, $custom_field_mapping: $value");
    }
  }
}