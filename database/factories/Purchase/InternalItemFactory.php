<?php

namespace Database\Factories\Purchase;

use Illuminate\Database\Eloquent\Factories\Factory;

class InternalItemFactory extends Factory
{
    public function definition(): array
    {
        $units = ['pcs', 'kg', 'm', 'l', 'set', 'box'];
        $now = now()->format('Y-m-d H:i:s');

        return [
            // Unieke referentie zoals: ART-74291 of ITEM-X9B2
            'reference'   => strtoupper($this->faker->unique()->bothify('??-#####')),
            'description' => $this->faker->sentence(4),
            'base_unit'   => $this->faker->randomElement($units),
            'is_active'   => $this->faker->boolean(95),
            'created_at'  => now()->format('Y-m-d H:i:s'),
            'updated_at'  => now()->format('Y-m-d H:i:s'),
        ];
    }
}