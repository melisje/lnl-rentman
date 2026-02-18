<?php

namespace Database\Seeders;

use Database\Seeders\Rentman\CustomFieldSeeder;
use Database\Seeders\Rentman\CustomFieldMappingSeeder;
use Database\Seeders\Rentman\MappingSeeder;
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
                MappingSeeder::class,
                CustomFieldMappingSeeder::class,
            ]
        );

        $this->command->info('Rentman seeding completed successfully!');
    }
}
