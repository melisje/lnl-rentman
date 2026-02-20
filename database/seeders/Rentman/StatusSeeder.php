<?php

namespace Database\Seeders\Rentman;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            ['account'  => 'llstageservice', 'name' => 'Gevarieerd', 'rm_id' => 'gevarieerd'],
            ['account'  => 'ledvisions', 'name' => 'Gevarieerd', 'rm_id' => 'gevarieerd'],
        ];

        // Gebruik upsert om dubbelingen te voorkomen op basis van id
        DB::table('rm_statuses')->upsert(
            $fields,
            ['account', 'rm_id'], // Unieke sleutel om te controleren op bestaande records
        );

        $this->command->info('Rentman Mappings successfully seeded!');
    }
}
