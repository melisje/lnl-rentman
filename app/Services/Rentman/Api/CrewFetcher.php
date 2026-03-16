<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Crew;
use App\Models\Rentman\Project;
use App\Services\Rentman\Api\AbstractRentmanFetcher;
use Illuminate\Database\Eloquent\Model;

class CrewFetcher extends AbstractRentmanFetcher
{
    /**
     * Process the page: e.g., sync with the database.
     */
    public function processPage(string $account, array $items,?array $fields=[]): void
    {
        foreach ($items as $item) {

            // map fields on values, for updateCreate function below
            // We add all fields to db that are returned by the API call ($items).
            $fillables = $this->fillables($item);

            // Laravel-style update or create
            $crew = Crew::updateOrCreate(
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