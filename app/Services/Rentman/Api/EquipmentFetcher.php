<?php

namespace App\Services\Rentman\Api;

use App\Models\Rentman\CustomFieldMapping;
use App\Models\Rentman\Equipment;
use App\Services\Rentman\Api\AbstractRentmanFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class EquipmentFetcher extends AbstractRentmanFetcher
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

            // Laravel-style update or create
            $equipment = Equipment::updateOrCreate(
                // Deel 1: De unieke velden om het record te vinden
                [
                    'account' => $account,
                    'rm_id'   => $item['id'],
                ],
                // Deel 2: De velden die ingevuld of bijgewerkt moeten worden
                $fillables
            );

            // Process custom fields separately (omdat ze allemaal samen in een
            // json string worden opgeslagen in custom field
            $custom = $equipment->custom ?? [];

            // Als het een string is (JSON), zet het om naar een array
            if (is_string($custom)) {
                $custom = json_decode($custom, true) ?? [];
            }
            // Loop door de custom fields uit de json string en map ze naar
            // echte veldnamen in de database
            foreach ($custom as $key => $value)
            {
                // The key of the custom field has format custom_xx with xx the
                // rm_id of the custom field. We need to get the corresponding
                // customfield_id from our mapping table, to know which
                // veldnaam in our database we moeten updaten.
                $rm_id = (int) str_replace('custom_', '', $key);

                // Find the field_name in our database for this rm_id, via de
                // mapping table.
                $fieldName = CustomFieldMapping::where('rm_id', $rm_id)
                    ->where('account', $account)
                    ->value('customfield_id') ?? $key;

                // Translate the dropdown indices to their corresponding values
                // See Rentman configuration for the corresponding account.

                switch($account)
                {
                    case 'ledvisions':
                        break;
                    case 'llstageservice':
                        $value = $this->getDropDownValueLL($fieldName, $value);
                        break;
                }


                Log::info("Processing custom field: {$fieldName} => {$value}");

                // Controlleer of het customfield bestaat in de database (als veldnaam), zo niet, sla het dan op in de custom json kolom
                if (Schema::hasColumn('rm_equipment', $fieldName)) {
                    // De kolom bestaat, je kunt hier je logica uitvoeren
                    $equipment->$fieldName = $value; // Dynamisch veld toevoegen aan het model (zorg dat je deze velden ook in $fillable of $guarded hebt staan)
                }


            }

            $equipment->save(); // Sla het model op nadat de custom fields zijn toegevoegd

            // dd($equipment);
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