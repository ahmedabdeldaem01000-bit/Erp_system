<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Department::create(['name' => 'hr','position_id'=>'1']);
       Department::create(['name' => 'sales','position_id'=>'2']);
       Department::create(['name' => 'accounting','position_id'=>'3']);
        
    }
}
