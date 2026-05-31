<?php

namespace Database\Factories;

use App\Models\purchases;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchases>
 */
class PurchasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total' => $this->faker->randomFloat(2, 10, 1000),
            'supplier_id' => \App\Models\Supplier::factory(),
        ];
    }
}
