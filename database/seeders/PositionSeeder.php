<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Position::create(['name' => 'employee',]);
       Position::create(['name' => 'manager',]);
       Position::create(['name' => 'super manager',]);
    }
}
