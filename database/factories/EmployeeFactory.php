<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
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
     static $departments;

        $departments ??= Department::pluck('id')->toArray();

        return [
              'name'=>$this->faker->name(),
    'salary' => $this->faker->randomNumber(6),
    'address' => $this->faker->address(),
    'image' => $this->faker->imageUrl(),
    'phone' => $this->faker->phoneNumber(),
    'gender' => $this->faker->randomElement(['male', 'female']),
    'hire_date' => $this->faker->date(),
    'email' => $this->faker->unique()->safeEmail(),
    'password' => bcrypt('password'),
  'department_id' => fake()->randomElement($departments),
        ];
    }
}
