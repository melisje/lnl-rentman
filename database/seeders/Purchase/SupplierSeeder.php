<?php

namespace Database\Seeders\Purchase;

use App\Models\Purchase\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Genereer exact 100 leveranciers
        Supplier::factory()->count(100)->create();
    }
}
