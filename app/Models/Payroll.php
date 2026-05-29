<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    /** @use HasFactory<\Database\Factories\PayrollFactory> */
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'bonus',
        'net_salary',
        'status',
        'paid_at',
    ];

        protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'bonus' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
