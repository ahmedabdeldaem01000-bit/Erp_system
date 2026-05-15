<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "Hr"=>fake()->word(),
            "sales"=>fake()->word(),
            "accounting"=>fake()->word(),
            "purchase"=>fake()->word(),
            "product"=>fake()->word(),

        ];
    }
}
