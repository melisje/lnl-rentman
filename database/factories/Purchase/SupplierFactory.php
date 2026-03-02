<?php

namespace Database\Factories\Purchase;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PoSupplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // We gebruiken de Belgische locale voor realistische data
        $fakerBE = \Faker\Factory::create('nl_BE');

        return [
            'name'       => $fakerBE->company(),
            // Belgisch BTW-nummer formaat: BE + 10 cijfers (vaak beginnend met 0)
            'vat_number' => 'BE0' . $fakerBE->numberBetween(100000000, 999999999),
            'email'      => $fakerBE->companyEmail(),
            'phone'      => $fakerBE->phoneNumber(),
            'address'    => "{$fakerBE->streetAddress()}, {$fakerBE->postcode()} {$fakerBE->city()}",
            'is_active'  => $fakerBE->boolean(90), // 90% kans op true
        ];
    }
}
