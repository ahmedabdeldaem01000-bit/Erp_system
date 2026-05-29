<?php

namespace Database\Factories;

use App\Models\PerformanceReview;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerformanceReview>
 */
class PerformanceReviewFactory extends Factory
{
    protected $model = PerformanceReview::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qualityOfWork = $this->faker->numberBetween(1, 10);
        $productivity = $this->faker->numberBetween(1, 10);
        $communication = $this->faker->numberBetween(1, 10);
        $teamwork = $this->faker->numberBetween(1, 10);
        $leadership = $this->faker->numberBetween(1, 10);

        $overallRating = ($qualityOfWork + $productivity + $communication + $teamwork + $leadership) / 5;

        return [
            'employee_id' => Employee::factory(),
            'reviewer_id' => Employee::factory(),
            'review_period' => $this->faker->randomElement(['2024-Q1', '2024-Q2', '2024-Q3', '2024-Q4']),
            'quality_of_work' => $qualityOfWork,
            'productivity' => $productivity,
            'communication' => $communication,
            'teamwork' => $teamwork,
            'leadership' => $leadership,
            'overall_rating' => round($overallRating, 2),
            'strengths' => $this->faker->paragraph(2),
            'areas_for_improvement' => $this->faker->paragraph(2),
            'goals' => $this->faker->paragraph(2),
            'comments' => $this->faker->paragraph(3),
        ];
    }

    /**
     * Indicate a high-performing employee.
     */
    public function highPerforming(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality_of_work' => $this->faker->numberBetween(8, 10),
            'productivity' => $this->faker->numberBetween(8, 10),
            'communication' => $this->faker->numberBetween(8, 10),
            'teamwork' => $this->faker->numberBetween(8, 10),
            'leadership' => $this->faker->numberBetween(8, 10),
        ]);
    }

    /**
     * Indicate an average-performing employee.
     */
    public function averagePerforming(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality_of_work' => $this->faker->numberBetween(5, 7),
            'productivity' => $this->faker->numberBetween(5, 7),
            'communication' => $this->faker->numberBetween(5, 7),
            'teamwork' => $this->faker->numberBetween(5, 7),
            'leadership' => $this->faker->numberBetween(5, 7),
        ]);
    }

    /**
     * Indicate a low-performing employee.
     */
    public function lowPerforming(): static
    {
        return $this->state(fn (array $attributes) => [
            'quality_of_work' => $this->faker->numberBetween(1, 4),
            'productivity' => $this->faker->numberBetween(1, 4),
            'communication' => $this->faker->numberBetween(1, 4),
            'teamwork' => $this->faker->numberBetween(1, 4),
            'leadership' => $this->faker->numberBetween(1, 4),
        ]);
    }
}
