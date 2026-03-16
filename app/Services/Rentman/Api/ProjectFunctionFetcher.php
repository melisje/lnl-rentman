<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectFunction;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\AbstractRentmanFetcher;

class ProjectFunctionFetcher extends AbstractRentmanFetcher
{
    /**
     * Process the page: e.g., sync with the database.
     */
    public function processPage(string $account, array $items): void
    {

        foreach ($items as $item) {

            // map fields on values, for updateCreate function below
            // We add all fields to db that are returned by the API call ($items).
            $fillables = $this->fillables($item);

            // Find the Project's id
            $projectRmId = basename($item['project']);
            $project = Project::where(['account' => $account, 'rm_id' => $projectRmId])->first();
            $fillables['project_id'] = $project ? $project->id : null;

            // Find SubProject's id
            $subprojectRmId = basename($item['subproject']);
            $subproject = SubProject::where(['account' => $account, 'rm_id' => $subprojectRmId])->first();
            $fillables['subproject_id'] = $subproject ? $subproject->id : null;

            // Laravel-style update or create
            $model = ProjectFunction::updateOrCreate(
                // Deel 1: De unieke velden om het record te vinden
                [
                    'account' => $account,
                    'rm_id'   => $item['id'],
                ],
                // Deel 2: De velden die ingevuld of bijgewerkt moeten worden
                $fillables
            );
        }
    }
}