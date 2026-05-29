<?php

namespace Database\Factories;

use App\Models\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payroll>
 */
class PayrollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'month' => $this->faker->month(),
            'year' => $this->faker->year(),
            'basic_salary' => $this->faker->randomFloat(2, 1000, 10000),
            'allowances' => $this->faker->randomFloat(2, 0, 1000),
            'deductions' => $this->faker->randomFloat(2, 0, 1000),
            'bonus' => $this->faker->randomFloat(2, 0, 1000),
            'net_salary' => $this->faker->randomFloat(2, 1000, 10000),
            'status' => $this->faker->randomElement(['draft', 'processed', 'paid']),
            'paid_at' => $this->faker->optional()->date(),
        ];
    }
}
