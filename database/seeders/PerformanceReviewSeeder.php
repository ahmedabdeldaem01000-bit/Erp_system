<?php

namespace Database\Seeders;

use App\Models\PerformanceReview;
use Illuminate\Database\Seeder;

class PerformanceReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 40 performance reviews with different performance levels
        PerformanceReview::factory(15)->highPerforming()->create();
        PerformanceReview::factory(15)->averagePerforming()->create();
        PerformanceReview::factory(10)->lowPerforming()->create();
    }
}
