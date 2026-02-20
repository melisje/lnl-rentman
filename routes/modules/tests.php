<?php

use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\CrewService;
use App\Services\Rentman\Api\ProjectsService;
use Illuminate\Support\Facades\Route;



Route::get('/test/{account}/projects/{rm_id}', function(ProjectsService $projectsService, CrewService $crewService, $account, $rm_id)
{
  $project = $projectsService->sync_projects($account, "/projects/$rm_id");
  $custom = json_decode($project->custom);

  // find project_manager value in the custom fields
  $custom_field_mapping = CustomFieldMapping::where('account', $account)
    ->where('customfield_id', 'project_manager')
    ->with('customField')
    ->first();
  $custom_name = $custom_field_mapping ? $custom_field_mapping->custom_name : null;
  $pm_value = data_get($custom, $custom_name);

  // For account llstageservice the project manager custom field is based on real crew member values
  // For account ledvisions is a simple dropdown list with mappings to fixed names

  switch ($account)
  {
    case 'llstageservice':
      //  make sure the crew member is synced to the local database
      $crew = $crewService->sync_crew_member($account, $pm_value);
      $pm = "/crew/".$crew->rm_id; // we can use the displayname of the crew member as project manager name
      break;

    case 'ledvisions':
      // ledvision does not use real crew members references as value for the pm custom field.
      // They should better change this like this is done in llstageservice, but for now we
      // need to work with a fixed mapping of the dropdown values to the crew members in
      // our local database.
      $pm = $pm_value; // this is a simple string value that we can use as is
      switch ($pm_value)
      {
        case 0:
          $pm = '/crew/33'; // 'Nicolas Pairon';
          break;
        case 1:
          $pm = '/crew/303';  //'Jasper Vanhees';
          break;
        case 2:
          $pm = '/crew/294';  //Constantin (Costy) Astancai';
          break;
        default:
          $pm = null; // 'Unknown';
      }
      break;
  }


  return "PM='$pm'";
});


