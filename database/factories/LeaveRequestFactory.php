<?php

namespace Database\Factories;

use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 10) . ' days');
        
        $days = $endDate->diff($startDate)->days + 1;

        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'days' => $days,
            'reason' => $this->faker->paragraph(3),
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
        ];
    }

    /**
     * Indicate that the leave request is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => Employee::factory(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Indicate that the leave request is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_by' => Employee::factory(),
            'approved_at' => now(),
            'rejection_reason' => $this->faker->paragraph(),
        ]);
    }

    /**
     * Indicate that the leave request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => null,
        ]);
    }
}
