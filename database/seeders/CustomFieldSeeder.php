<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rentman\CustomField;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            ['id' => 1, 'naam' => 'Load/unload warehouse (h)', 'hoort_bij' => 'Project', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Decimaal getal', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 2, 'naam' => 'Facturatie', 'hoort_bij' => 'Project', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Keuzelijst', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 1, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 6, 'naam' => 'Type Activiteit', 'hoort_bij' => 'Urenregistratie', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Keuzelijst', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 7, 'naam' => 'Approval', 'hoort_bij' => 'Urenregistratie', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Ja/Nee', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 8, 'naam' => 'Factuur ontvangen', 'hoort_bij' => 'Urenregistratie', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Ja/Nee', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 9, 'naam' => 'Verzendmethode', 'hoort_bij' => 'Contact', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Keuzelijst', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 10, 'naam' => 'Euronorm', 'hoort_bij' => 'Voertuig', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Keuzelijst', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 11, 'naam' => 'Tacho (geldig tot)', 'hoort_bij' => 'Voertuig', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Opgemaakte tekst', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 14, 'naam' => 'Avg Maintenance time (min)', 'hoort_bij' => 'Reparatie', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Decimaal getal', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 0],
            ['id' => 15, 'naam' => 'Default Checkzone', 'hoort_bij' => 'Materiaal', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Keuzelijst', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 1, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 1],
            ['id' => 17, 'naam' => 'Logistic Item', 'hoort_bij' => 'Materiaal', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Ja/Nee', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 1],
            ['id' => 19, 'naam' => 'AVG Outbound time (Min)', 'hoort_bij' => 'Materiaal', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Decimaal getal', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 1],
            ['id' => 21, 'naam' => 'AVG Inbound time (Min)', 'hoort_bij' => 'Materiaal', 'type_gekoppeld_item' => null, 'type_invoerveld' => 'Decimaal getal', 'verborgen' => 0, 'vertrouwelijk_veld' => 0, 'gebruiken_in_zoekopdrachten' => 0, 'minimale_zoeklengte' => 5, 'invoerveld_vereist' => 0, 'aanpasbaar_per_project' => 1],
        ];

        foreach ($contacts as $contact) {
            CustomField::updateOrCreate(
                ['id' => $contact['id']],
                [
                    'name'                        => $contact['naam'],
                    'belongs_to'                   => $contact['hoort_bij'],
                    'type'             => $contact['type_invoerveld'],
                    'hidden'                   => (bool) $contact['verborgen'],
                    'private'          => (bool) $contact['vertrouwelijk_veld'],
                    'mandatory'          => (bool) $contact['invoerveld_vereist'],
                    'aanpasbaar_per_project'      => (bool) $contact['aanpasbaar_per_project'],
                ]
            );
        }

        $this->command->info('Contacten succesvol geïmporteerd vanuit de PHP array.');

    }
}
