<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\Crew;
use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\LeaveType;
use App\Models\Rentman\TimeRegistration;
use App\Services\Rentman\Api\AbstractRentmanFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TimeRegistrationFetcher extends AbstractRentmanFetcher
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
            $model = TimeRegistration::updateOrCreate(
                // Deel 1: De unieke velden om het record te vinden
                [
                    'account' => $account,
                    'rm_id'   => $item['id'],
                ],
                // Deel 2: De velden die ingevuld of bijgewerkt moeten worden
                $fillables
            );

            // Find crewmember_id of this time registration
            $model->crewmember_id = Crew::where('account', $account)
                ->where('rm_id', basename($item['crewmember']) ?? null)
                ->value('id');

            // Find crewmember_id of this time registration
            $model->leavetype_id = LeaveType::where('account', $account)
                ->where('rm_id', basename($item['leavetype']) ?? null)
                ->value('id');

            // // Find equipment_id of this serial
            // $model->equipment_id = Equipment::where('account', $account)
            //     ->where('rm_id', basename($item['equipment']) ?? null)
            //     ->value('id');


            //     // Find stocklocation_id of this serial
            // $model->asset_location_id = StockLocation::where('account', $account)
            //     ->where('rm_id', basename($item['asset_location']) ?? null)
            //     ->value('id');

            $model->save(); // Sla het model op nadat de custom fields zijn toegevoegd

            // Process custom fields
            $this->processCustomFields($account, $item, $model);
        }
    }

    private function getDropDownValueLL($fieldName, $value)
    {
        // Translate the dropdown indices to their corresponding values
        // See Rentman configuration for the corresponding account.
        switch ($fieldName) {
            case 'polaris':
                switch ($value) {
                    case 1:
                        return 'bulk';
                    case 0:
                        return 'serialized';
                    default:
                        return $value; // do nothing, just use the value as is
                }
                break;

            case 'default_checkzone':
                switch ($value) {
                    case 0:
                        return 'sound';
                    case 1:
                        return 'light';
                    case 2:
                        return 'rigging';
                    case 3:
                        return 'cables';
                    case 4:
                        return 'na';
                    case 5:
                        return 'video';
                    default:
                        return $value; // do nothing, just use the value as is
                }

            case 'inbound_type':
                switch ($value) {
                    case 0:
                        return '2-way';
                    case 1:
                        return '3-way';
                    case 2:
                        return 'na';
                    default:
                        return $value; // do nothing, just use the value as is
                }

            default:
                return $value; // do nothing, just use the value as is
        }
    }
}