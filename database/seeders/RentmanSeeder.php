<?php

namespace Database\Seeders;

use Database\Seeders\Production\ChecklistTemplateSeeder;
use Database\Seeders\Rentman\CustomFieldSeeder;
use Database\Seeders\Rentman\CustomFieldMappingSeeder;
use Database\Seeders\Rentman\StatusSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(
            [
                RoleSeeder::class,
                CustomFieldSeeder::class,
                CustomFieldMappingSeeder::class,
                StatusSeeder::class,
                ChecklistTemplateSeeder::class,
            ]
        );

        $this->command->info('Rentman seeding completed successfully!');
    }
}
