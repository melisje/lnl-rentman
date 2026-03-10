<?php

use App\Http\Controllers\Test\TestController;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\CrewService;
use App\Services\Rentman\Api\ProjectsService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test\WebhookTestController;



Route::get('/test/{account}/projects/{rm_id}', function(ProjectsService $projectsService, CrewService $crewService, $account, $rm_id)
{
  $project = $projectsService->sync_projects($account, "/projects/$rm_id");
  // $reference =  $projectsService->get_projectmanager_refrence($account, $project);

  dump($project);

  return "PM='$project->project_manager'";
});



// De pagina met het Bootstrap formulier
Route::get('/test/webhook-tester', [WebhookTestController::class, 'showForm'])
  ->name('test.webhook.form');

// De route die de JSON verwerkt
Route::post('/test/webhook-tester', [WebhookTestController::class, 'handleTestWebhook'])
  ->name('test.webhook.submit');


Route::get('/test/sync-crew', [TestController::class, 'showTestForm'])->name('test.projectcrew.form');
Route::post('/test/sync-crew', [TestController::class, 'runSync'])->name('test.projectcrew.run');

