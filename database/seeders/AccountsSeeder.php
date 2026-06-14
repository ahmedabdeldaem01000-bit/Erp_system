<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::insert([
    [
        'code' => '1000',
        'name' => 'Assets',
        'type' => 'asset',
        'is_postable' => false,
    ],
    [
        'code' => '2000',
        'name' => 'Liabilities',
        'type' => 'liability',
        'is_postable' => false,
    ],
    
    [
        'code' => '3000',
        'name' => 'Equity',
        'type' => 'equity',
        'is_postable' => false,
    ],
    [
        'code' => '4000',
        'name' => 'Revenue',
        'type' => 'revenue',
        'is_postable' => false,
    ],
    [
        'code' => '5000',
        'name' => 'Expenses',
        'type' => 'expense',
        'is_postable' => false,
    ],
]);
    }
}
