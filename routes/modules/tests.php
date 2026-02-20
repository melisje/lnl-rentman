<?php

use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\CrewService;
use App\Services\Rentman\Api\ProjectsService;
use Illuminate\Support\Facades\Route;



Route::get('/test/{account}/projects/{rm_id}', function(ProjectsService $projectsService, CrewService $crewService, $account, $rm_id)
{
  $project = $projectsService->sync_projects($account, "/projects/$rm_id");
  // $reference =  $projectsService->get_projectmanager_refrence($account, $project);



  return "PM='$project->project_manager'";
});


