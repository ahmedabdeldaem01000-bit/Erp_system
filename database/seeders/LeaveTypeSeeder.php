<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'days_per_year' => 21,
                'is_paid' => true,
            ],
            [
                'name' => 'Sick Leave',
                'days_per_year' => 10,
                'is_paid' => true,
            ],
            [
                'name' => 'Maternity Leave',
                'days_per_year' => 90,
                'is_paid' => true,
            ],
            [
                'name' => 'Paternity Leave',
                'days_per_year' => 10,
                'is_paid' => true,
            ],
            [
                'name' => 'Unpaid Leave',
                'days_per_year' => 0,
                'is_paid' => false,
            ],
            [
                'name' => 'Casual Leave',
                'days_per_year' => 5,
                'is_paid' => true,
            ],
            [
                'name' => 'Bereavement Leave',
                'days_per_year' => 5,
                'is_paid' => true,
            ],
            [
                'name' => 'Study Leave',
                'days_per_year' => 5,
                'is_paid' => true,
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::firstOrCreate(
                ['name' => $leaveType['name']],
                $leaveType
            );
        }
    }
}
