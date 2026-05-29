<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
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
            'date' => $this->faker->date(),
            'check_in' => $this->faker->time(),
            'check_out' => $this->faker->time(),
            'status' => $this->faker->randomElement(['present', 'absent', 'late', 'half-day']),
            'notes' => $this->faker->text()
        ];
    }
}
