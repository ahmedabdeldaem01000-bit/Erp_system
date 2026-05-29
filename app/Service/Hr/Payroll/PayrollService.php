<?php

namespace App\Service\Hr\Payroll;

use App\Models\Payroll;
use Illuminate\Database\Eloquent\Collection;

class PayrollService
{
    /**
     * Display all payrolls.
     */
    public function index(): Collection
    {
        return Payroll::latest()->get();
    }

    /**
     * Store new payroll.
     */
    public function store(array $data): Payroll
    {
        $data['net_salary'] = $this->calculateNetSalary($data);

        return Payroll::create([
            'employee_id'  => $data['employee_id'],
            'month'        => $data['month'],
            'year'         => $data['year'],
            'basic_salary' => $data['basic_salary'],
            'allowances'   => $data['allowances'] ?? 0,
            'deductions'   => $data['deductions'] ?? 0,
            'bonus'        => $data['bonus'] ?? 0,
            'net_salary'   => $data['net_salary'],
            'status'       => $data['status'],
            'paid_at'      => $data['paid_at'] ?? null,
        ]);
    }

    /**
     * Update payroll.
     */
    public function update(array $data, int|string $id): Payroll
    {
        $payroll = Payroll::findOrFail($id);

        $updatedData = [
            'employee_id'  => $data['employee_id']  ?? $payroll->employee_id,
            'month'        => $data['month']        ?? $payroll->month,
            'year'         => $data['year']         ?? $payroll->year,
            'basic_salary' => $data['basic_salary'] ?? $payroll->basic_salary,
            'allowances'   => $data['allowances']   ?? $payroll->allowances,
            'deductions'   => $data['deductions']   ?? $payroll->deductions,
            'bonus'        => $data['bonus']        ?? $payroll->bonus,
            'status'       => $data['status']       ?? $payroll->status,
            'paid_at'      => $data['paid_at']      ?? $payroll->paid_at,
        ];

        $updatedData['net_salary'] = $this->calculateNetSalary($updatedData);

        $payroll->update($updatedData);

        return $payroll->fresh();
    }

    /**
     * Show single payroll.
     */
    public function show(int|string $id): Payroll
    {
        return Payroll::findOrFail($id);
    }

    /**
     * Delete payroll.
     */
    public function delete(int|string $id): bool
    {
        $payroll = Payroll::findOrFail($id);

        return $payroll->delete();
    }

    /**
     * Calculate net salary.
     */
    private function calculateNetSalary(array $data): float|int
    {
        return (
            $data['basic_salary']
            + ($data['allowances'] ?? 0)
            + ($data['bonus'] ?? 0)
        ) - ($data['deductions'] ?? 0);
    }
}