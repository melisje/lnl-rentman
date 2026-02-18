<?php

namespace Database\Seeders\Rentman;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            ['id'  => 'project_manager', 'display_name' => 'Project Manager'],
        ];

        // Gebruik upsert om dubbelingen te voorkomen op basis van id
        DB::table('rm_mappings')->upsert(
            $fields,
            ['id'],
        );

        $this->command->info('Rentman Mappings successfully seeded!');
    }
}
