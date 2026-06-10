<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $positions;
        static $departments;

        $departments ??= Department::pluck('id')->toArray();
        $positions ??= Position::pluck('id')->toArray();

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'salary' => $this->faker->randomNumber(6),
            'address' => $this->faker->address(),
            'image' => $this->faker->imageUrl(),
            'phone' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'hire_date' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'department_id' => fake()->randomElement($departments),
            'position_id' => fake()->randomElement($positions),
        ];
    }
}
