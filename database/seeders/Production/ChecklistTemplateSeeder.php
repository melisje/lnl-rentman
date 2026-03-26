<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use App\Models\Production\ChecklistTemplate;
use App\Models\Production\ChecklistTemplateItem;
use App\Models\Rentman\Project;

class ChecklistTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Maak de hoofd-template aan
        $template = ChecklistTemplate::create([
            'name'       => 'Standaard Productie Checklist',
            'remarks'    => 'Basis template met alle standaard stappen voor productie.',
        ]);

        // 2. De lijst met items die je hebt aangeleverd
        $items = [
            'Actiepunten',
            'Light design',
            'Rentman',
            'Patchprinter',
            'Systeemlijst',
            'Patchlijst',
            'Rentman double check',
            'ELC File',
            'MVR',
            'Bouwplannen',
            'Meeting met Head rigger & System Tech & Crew chief',
            'Transportplanning',
            'Info mail naar crew (Antwoord op de callsheet mail)',
            'Accreditatie - Locatie & Overnachting',
            'Machienerie',
            'Contactgegevens key mensen op productie',
            'Safetybriefing',
            'Risico Analyse(s)',
            'Debrief crew op locatie',
            'klein verslag voor productie meeting',
            'Extra’s verwerken en factuurbedrag checken',
            'Inhuur nakijken',
            'crew administratie: A1',
            'crew administratie: Crew lijst afdrukken',
            'Plannen, patch, materiaallijst afdrukken, …',
            'Double check rigging van de venue',
        ];

        // 3. Items toevoegen met automatische sequence
        foreach ($items as $index => $itemName) {
            $template->items()->create([
                'name'     => $itemName,
                'sequence' => $index + 1, // Start bij 1, 2, 3...
                'remarks'  => null,
            ]);
        }

        $this->command->info('Checklist template aangemaakt met ' . count($items) . ' items.');
    }
}