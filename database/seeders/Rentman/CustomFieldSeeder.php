<?php

namespace Database\Seeders\Rentman;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            // llstagservice data
            ['account' => 'llstagservice', 'rm_id' => 7, 'name' => 'Approval', 'belongs_to' => 'Urenregistratie', 'type' => 'Ja/Nee', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 23, 'name' => 'AVG Check time (min)', 'belongs_to' => 'Materiaal', 'type' => 'Decimaal getal', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 21, 'name' => 'AVG Inbound time (Min)', 'belongs_to' => 'Materiaal', 'type' => 'Decimaal getal', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 14, 'name' => 'Avg Maintenance time (min)', 'belongs_to' => 'Reparatie', 'type' => 'Decimaal getal', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 19, 'name' => 'AVG Outbound time (Min)', 'belongs_to' => 'Materiaal', 'type' => 'Decimaal getal', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 35, 'name' => 'Check Invoice', 'belongs_to' => 'Inhuur', 'type' => 'Ja/Nee', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 32, 'name' => 'Crew planning', 'belongs_to' => 'Project', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 15, 'name' => 'Default Checkzone', 'belongs_to' => 'Materiaal', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 10, 'name' => 'Euronorm', 'belongs_to' => 'Voertuig', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 34, 'name' => 'Exact Kostenplaats', 'belongs_to' => 'Medewerker', 'type' => 'Tekst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 2, 'name' => 'Facturatie', 'belongs_to' => 'Project', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 8, 'name' => 'Factuur ontvangen', 'belongs_to' => 'Urenregistratie', 'type' => 'Ja/Nee', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 27, 'name' => 'Inbound type', 'belongs_to' => 'Materiaal', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 1, 'name' => 'Load/unload warehouse (h)', 'belongs_to' => 'Project', 'type' => 'Decimaal getal', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 17, 'name' => 'Logistic Item', 'belongs_to' => 'Materiaal', 'type' => 'Ja/Nee', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 33, 'name' => 'Material planning', 'belongs_to' => 'Project', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 30, 'name' => 'Nummerplaat', 'belongs_to' => 'Medewerker', 'type' => 'Tekst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 39, 'name' => 'Polaris', 'belongs_to' => 'Materiaal', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 29, 'name' => 'Rijksregisternummer', 'belongs_to' => 'Medewerker', 'type' => 'Tekst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 31, 'name' => 'Short name', 'belongs_to' => 'Materiaal', 'type' => 'Tekst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 36, 'name' => 'Taal', 'belongs_to' => 'Medewerker', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 11, 'name' => 'Tacho (geldig tot)', 'belongs_to' => 'Voertuig', 'type' => 'Opgemaakte tekst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 6, 'name' => 'Type Activiteit', 'belongs_to' => 'Urenregistratie', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 9, 'name' => 'Verzendmethode', 'belongs_to' => 'Contact', 'type' => 'Keuzelijst', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 38, 'name' => 'Voorschot factuur', 'belongs_to' => 'Project', 'type' => 'Prijs', 'hidden' => 0, 'private' => 1, 'mandatory' => 0],
            ['account' => 'llstagservice', 'rm_id' => 37, 'name' => 'Vrijgave voor warehouse', 'belongs_to' => 'Subproject', 'type' => 'Ja/Nee', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],

            // ledvisions data
            ['account' => 'ledvisions', 'rm_id' => 3, 'name' => 'Productie/Magazijn', 'belongs_to' => 'Time registration', 'type' => 'Drop down list', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 4, 'name' => 'Gefactureerd?', 'belongs_to' => 'Time registration', 'type' => 'Yes/No', 'hidden' => 0, 'private' => 1, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 5, 'name' => 'INFORMATIE OK', 'belongs_to' => 'Equipment', 'type' => 'Yes/No', 'hidden' => 0, 'private' => 1, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 6, 'name' => 'Exemplaren ok', 'belongs_to' => 'Equipment', 'type' => 'Yes/No', 'hidden' => 0, 'private' => 1, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 7, 'name' => 'Ok voor Facturatie ?', 'belongs_to' => 'Project', 'type' => 'Yes/No', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 12, 'name' => 'PM', 'belongs_to' => 'Project', 'type' => 'Drop down list', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 13, 'name' => 'PO-nummer', 'belongs_to' => 'Project', 'type' => 'Text', 'hidden' => 0, 'private' => 0, 'mandatory' => 1],
            ['account' => 'ledvisions', 'rm_id' => 14, 'name' => 'Extra information', 'belongs_to' => 'Project', 'type' => 'Long text', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
            ['account' => 'ledvisions', 'rm_id' => 15, 'name' => 'PO-nummer voor inhuur', 'belongs_to' => 'Subrent', 'type' => 'Text', 'hidden' => 0, 'private' => 0, 'mandatory' => 1],
            ['account' => 'ledvisions', 'rm_id' => 17, 'name' => 'TPM', 'belongs_to' => 'Project', 'type' => 'Drop down list', 'hidden' => 0, 'private' => 0, 'mandatory' => 0],
        ];

        // Gebruik upsert om dubbelingen te voorkomen op basis van account + rm_id
        DB::table('rm_customfields')->upsert(
            $fields,
            ['account', 'rm_id'],
            ['name', 'belongs_to', 'type', 'hidden', 'private', 'mandatory']
        );

        $this->command->info('Rentman Custom Fields successfully seeded!');
    }
}
