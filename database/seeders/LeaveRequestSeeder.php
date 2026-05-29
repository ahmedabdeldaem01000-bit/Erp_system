<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 leave requests with mixed statuses
        LeaveRequest::factory(30)->pending()->create();
        LeaveRequest::factory(12)->approved()->create();
        LeaveRequest::factory(8)->rejected()->create();
    }
}
