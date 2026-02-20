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
            ['id'  => 'project_manager', 'name' => 'Project Manager', 'item_type' => 'Project'],
        ];

        // Gebruik upsert om dubbelingen te voorkomen op basis van id
        DB::table('rm_customfields')->upsert(
            $fields,
            ['id'],
        );

        $this->command->info('Rentman Mappings successfully seeded!');
    }
}
