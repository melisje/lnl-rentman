<?php

namespace Database\Seeders\Rentman;

use App\Models\Rentman\Application;
use App\Models\Rentman\ProjectType;
use App\Models\Rentman\ProjectTypeApplicationMapping;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTypeApplicationMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mappings = [
            'project_dashboard' =>
            [
                'llstageservice' => [104,107],
                'ledvisions' => [104]
            ],

            'warehouse_dashboard' =>
            [
                'llstageservice' => [102,104,105,106,107,107,109,111,112],
                'ledvisions' => []
            ],
        ];

        // dump($mappings);

        foreach ($mappings as $appName => $accounts)
        {
            // Make sure application exists
            $appId = Application::updateOrCreate
            (
                ['name' => $appName],      // De unieke waarde om op te zoeken
                ['remarks' => 'seeded']    // De waarde die geüpdatet of gezet moet worden
            );

            foreach ($accounts as $account => $projectRmIds)
            {
                $fields = [];

                // Find the project type ids for account llstageservice with rentman ids 104 and 107
                foreach ($projectRmIds as $rmId)
                {
                    $projectTypeId = ProjectType::where('rm_id', $rmId)
                        ->where('account', $account)
                        ->value('id');
                    $fields[] = [ 'account' => $account, 'project_type_id' => $projectTypeId, 'application_id' => $appId->id];
                }

                // Gebruik upsert om dubbelingen te voorkomen op basis van id
                DB::table('rm_project_type_application_mappings')->upsert(
                    $fields,
                    ['account', 'project_type_id', 'application_id'], // Unieke combinatie van deze drie velden
                    ['updated_at'] // Geen update, alleen insert als er geen match is
                );
            }
        }

        $this->command->info('Rentman Project Type Application Mappings successfully seeded!');    }
}
