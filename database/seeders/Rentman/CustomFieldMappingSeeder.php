<?php

namespace Database\Seeders\Rentman;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomFieldMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            // llstagservice data
            ['account' => 'llstageservice', 'rm_id' => 40, 'mapping_id' => 'project_manager'],

            // ledvisions data
            ['account' => 'ledvisions', 'rm_id' => 12, 'mapping_id' => 'project_manager'],
        ];

        // Gebruik upsert om dubbelingen te voorkomen op basis van account + rm_id
        DB::table('rm_customfield_mappings')->upsert(
            $fields,
            ['account', 'rm_id', 'mapping_id'], // Unieke sleutel om te controleren op bestaande records
            ['mapping_id'] // Velden die moeten worden bijgewerkt als er een bestaande record is
        );

        $this->command->info('Rentman CustomFields mappings successfully seeded!');
    }
}
