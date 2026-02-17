<?php

namespace Database\Seeders;

use Database\Seeders\Rentman\CustomFieldSeeder;
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
            ]
        );

        $this->command->info('Rentman seeding completed successfully!');
    }
}
