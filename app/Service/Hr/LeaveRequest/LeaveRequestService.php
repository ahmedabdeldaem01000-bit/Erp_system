<?php

namespace App\Service\Hr\LeaveRequest;

use App\Models\LeaveRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Service for handling Leave Request operations.
 */
class LeaveRequestService
{
    /**
     * Get all leave requests with pagination.
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approvedBy'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get leave requests by employee.
     */
    public function getByEmployee(int $employeeId, int $perPage = 15): LengthAwarePaginator
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approvedBy'])
            ->where('employee_id', $employeeId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get leave requests by status.
     */
    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approvedBy'])
            ->byStatus($status)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Search leave requests by employee name.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approvedBy'])
            ->join('employees', 'leave_requests.employee_id', '=', 'employees.id')
            ->where('employees.name', 'like', "%{$query}%")
            ->select('leave_requests.*')
            ->latest('leave_requests.created_at')
            ->paginate($perPage);
    }

    /**
     * Get leave request by ID.
     */
    public function getById(int $id): ?LeaveRequest
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approvedBy'])->find($id);
    }

    /**
     * Create a new leave request.
     */
    public function create(array $data): LeaveRequest
    {
        return DB::transaction(function () use ($data) {
            // Calculate days between dates
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);
            $days = $endDate->diffInDays($startDate) + 1;

            $data['days'] = $days;

            // Check for overlapping leaves
            if ($this->hasOverlappingLeaves($data['employee_id'], $startDate, $endDate)) {
                throw new Exception('Employee has overlapping leave requests during this period.');
            }

            return LeaveRequest::create($data);
        });
    }

    /**
     * Update leave request.
     */
    public function update(LeaveRequest $leaveRequest, array $data): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $data) {
            // Prevent updating approved or rejected requests
            if (!$leaveRequest->isPending()) {
                throw new Exception('Cannot update leave request that is not in pending status.');
            }

            // Recalculate days if dates are provided
            if (isset($data['start_date'], $data['end_date'])) {
                $startDate = Carbon::parse($data['start_date']);
                $endDate = Carbon::parse($data['end_date']);
                $data['days'] = $endDate->diffInDays($startDate) + 1;

                // Check for overlapping leaves (excluding current request)
                if ($this->hasOverlappingLeaves($leaveRequest->employee_id, $startDate, $endDate, $leaveRequest->id)) {
                    throw new Exception('Employee has overlapping leave requests during this period.');
                }
            }

            $leaveRequest->update($data);
            return $leaveRequest->fresh();
        });
    }

    /**
     * Approve a leave request.
     */
    public function approve(LeaveRequest $leaveRequest, int $approvedByEmployeeId, string $notes = null): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $approvedByEmployeeId, $notes) {
            if (!$leaveRequest->isPending()) {
                throw new Exception('Only pending leave requests can be approved.');
            }

            $leaveRequest->update([
                'status' => 'approved',
                'approved_by' => $approvedByEmployeeId,
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            return $leaveRequest->fresh();
        });
    }

    /**
     * Reject a leave request.
     */
    public function reject(LeaveRequest $leaveRequest, int $rejectedByEmployeeId, string $rejectionReason): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $rejectedByEmployeeId, $rejectionReason) {
            if (!$leaveRequest->isPending()) {
                throw new Exception('Only pending leave requests can be rejected.');
            }

            $leaveRequest->update([
                'status' => 'rejected',
                'approved_by' => $rejectedByEmployeeId,
                'approved_at' => now(),
                'rejection_reason' => $rejectionReason,
            ]);

            return $leaveRequest->fresh();
        });
    }

    /**
     * Delete leave request.
     */
    public function delete(LeaveRequest $leaveRequest): bool
    {
        return (bool) $leaveRequest->delete();
    }

    /**
     * Force delete leave request.
     */
    public function forceDelete(LeaveRequest $leaveRequest): bool
    {
        return (bool) $leaveRequest->forceDelete();
    }

    /**
     * Restore soft-deleted leave request.
     */
    public function restore(int $id): bool
    {
        return (bool) LeaveRequest::onlyTrashed()
            ->where('id', $id)
            ->restore();
    }

    /**
     * Check if employee has overlapping leave requests.
     */
    private function hasOverlappingLeaves(int $employeeId, Carbon $startDate, Carbon $endDate, int $excludeRequestId = null): bool
    {
        $query = LeaveRequest::where('employee_id', $employeeId)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeRequestId) {
            $query->where('id', '!=', $excludeRequestId);
        }

        return $query->exists();
    }

    /**
     * Get pending leave requests.
     */
    public function getPendingRequests(int $perPage = 15): LengthAwarePaginator
    {
        return $this->getByStatus('pending', $perPage);
    }

    /**
     * Get approved leave requests.
     */
    public function getApprovedRequests(int $perPage = 15): LengthAwarePaginator
    {
        return $this->getByStatus('approved', $perPage);
    }

    /**
     * Get rejected leave requests.
     */
    public function getRejectedRequests(int $perPage = 15): LengthAwarePaginator
    {
        return $this->getByStatus('rejected', $perPage);
    }

    /**
     * Get leave requests for date range.
     */
    public function getByDateRange(Carbon $startDate, Carbon $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return LeaveRequest::with(['employee', 'leaveType', 'approvedBy'])
            ->byDateRange($startDate, $endDate)
            ->latest()
            ->paginate($perPage);
    }
}
