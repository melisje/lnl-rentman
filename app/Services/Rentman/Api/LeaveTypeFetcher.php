<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Crew;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\LeaveType;
use App\Models\Rentman\TimeRegistration;
use App\Services\Rentman\Api\AbstractRentmanFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LeaveTypeFetcher extends AbstractRentmanFetcher
{
    /**
     * Process the page: e.g., sync with the database.
     */
    public function processPage(string $account, array $items): void
    {
        foreach ($items as $item)
        {

            // map fields on values, for updateCreate function below
            // We add all fields to db that are returned by the API call ($items).
            $fillables = $this->fillables($item);
            // dump($fillables);

            // Laravel-style update or create
            $model = LeaveType::updateOrCreate(
                // Deel 1: De unieke velden om het record te vinden
                [
                    'account' => $account,
                    'rm_id'   => $item['id'],
                ],
                // Deel 2: De velden die ingevuld of bijgewerkt moeten worden
                $fillables
            );

            $model->save(); // Sla het model op (optioneel, want updateOrCreate zou dit al moeten doen)

            // Process custom fields
            $this->processCustomFields($account, $item, $model);
        }
    }

}