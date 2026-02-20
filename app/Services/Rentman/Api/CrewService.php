<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Crew;
use App\Models\Rentman\CustomField;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Project;
use App\Models\Rentman\SubProject;
use Illuminate\Support\Facades\Log;

class CrewService
{
  public function __construct(protected RentmanApiService $rentmanApiService) {}

  /**
   * Sync the crew mamber in the DB with Rentman
   * We'll call the Rentman API to fetch the current crew data
   * If the crew member does not exist in the DB, create it with the Rentman data
   * Otherwise update the existing data in the DB with the fetched data
   * @param string $account The Rentman account name
   * @param int $rm_id The Rentman ID of the crew member to sync
   * @return Crew The synced crew member model
   */
  public function sync_crew_member(string $account, int $rm_id) : Crew|null
  {
    Log::info("@@@ syncing crew member ...");

    // build endpoint url
    $endpoint = "/crew/$rm_id";

    // Calling endpoint
    $crewdata = $this->rentmanApiService->get_rentman_endpoint($account, $endpoint);

    Log::debug("CREW MEMBER data: " . json_encode($crewdata));

    // update or create crew model
    $crew = Crew::updateOrCreate(
      // Deel 1: De unieke velden om het record te vinden
          [
              'account' => $account,
              'rm_id' => $rm_id
          ],
          [
              'account' => $account,
              'rm_id' => $rm_id,
              'created' => $crewdata['created'],
              'modified' => $crewdata['modified'],
              'creator' => $crewdata['creator'],
              'displayname' => $crewdata['displayname'],
              'updateHash' => $crewdata['updateHash'],
              'folder' => $crewdata['folder'],
              'street' => $crewdata['street'],
              'housenumber' => $crewdata['housenumber'],
              'city' => $crewdata['city'],
              'postal_code' => $crewdata['postal_code'],
              'addressline2' => $crewdata['addressline2'],
              'firstname' => $crewdata['firstname'],
              'middle_name' => $crewdata['middle_name'],
              'lastname' => $crewdata['lastname'],
              'email' => $crewdata['email'],
              'active' => $crewdata['active'],
              'tags' => $crewdata['tags'],
              'custom' => json_encode($crewdata['custom']),
          ]
    );

    return $crew;
  }
}