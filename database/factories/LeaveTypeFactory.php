<?php

namespace Database\Factories;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveType>
 */
class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $leaveTypes = [
            'Annual Leave',
            'Sick Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Unpaid Leave',
            'Casual Leave',
            'Bereavement Leave',
            'Study Leave',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($leaveTypes),
            'days_per_year' => $this->faker->numberBetween(5, 30),
            'is_paid' => $this->faker->boolean(80),
        ];
    }

    /**
     * Indicate that the leave type is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_paid' => true,
        ]);
    }

    /**
     * Indicate that the leave type is unpaid.
     */
    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_paid' => false,
        ]);
    }
}
