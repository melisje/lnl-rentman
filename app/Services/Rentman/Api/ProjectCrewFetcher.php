<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Crew;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectCrew;
use App\Models\Rentman\ProjectFunction;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\AbstractRentmanFetcher;

class ProjectCrewFetcher extends AbstractRentmanFetcher
{
    /**
     * Process the page: e.g., sync with the database.
     */
    public function processPage(string $account, array $items): void
    {

        foreach ($items as $item) {

            // dump($items);

            // map fields on values, for updateCreate function below
            // We add all fields to db that are returned by the API call ($items).
            $fillables = $this->fillables($item);

            // find crew_id
            $crewRmId = basename($item['crewmember']);
            $crew = Crew::where(['account' => $account, 'rm_id' => $crewRmId])->first();
            $fillables['crew_id'] = $crew ? $crew->id : null;

            /*
             * The hierarchy is: Project > Subproject > ProjectFunction > ProjectCrew
             * Let's find the ProjectFunction id
             */
            $rmId = basename($item['function']);
            $projfunc = ProjectFunction::where(['account' => $account, 'rm_id' => $rmId])->first();
            $fillables['function_id'] = $projfunc ? $projfunc->id : null;

            // Laravel-style update or create
            $model = ProjectCrew::updateOrCreate(
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