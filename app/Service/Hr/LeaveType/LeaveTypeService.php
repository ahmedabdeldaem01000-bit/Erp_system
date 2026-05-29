<?php

namespace App\Service\Hr\LeaveType;

use App\Models\LeaveType;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service for handling Leave Type operations.
 */
class LeaveTypeService
{
    /**
     * Get all leave types with pagination.
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return LeaveType::latest()
            ->paginate($perPage);
    }

    /**
     * Search leave types by name.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return LeaveType::where('name', 'like', "%{$query}%")
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get leave type by ID.
     */
    public function getById(int $id): ?LeaveType
    {
        return LeaveType::find($id);
    }

    /**
     * Create a new leave type.
     */
    public function create(array $data): LeaveType
    {
        return LeaveType::create($data);
    }

    /**
     * Update leave type.
     */
    public function update(LeaveType $leaveType, array $data): LeaveType
    {
        $leaveType->update($data);
        return $leaveType->fresh();
    }

    /**
     * Delete leave type.
     */
    public function delete(LeaveType $leaveType): bool
    {
        return (bool) $leaveType->delete();
    }

    /**
     * Force delete leave type.
     */
    public function forceDelete(LeaveType $leaveType): bool
    {
        return (bool) $leaveType->forceDelete();
    }

    /**
     * Restore soft-deleted leave type.
     */
    public function restore(int $id): bool
    {
        return (bool) LeaveType::onlyTrashed()
            ->where('id', $id)
            ->restore();
    }

    /**
     * Get paid leave types.
     */
    public function getPaidLeaveTypes(): Collection
    {
        return LeaveType::where('is_paid', true)
            ->latest()
            ->get();
    }

    /**
     * Get unpaid leave types.
     */
    public function getUnpaidLeaveTypes(): Collection
    {
        return LeaveType::where('is_paid', false)
            ->latest()
            ->get();
    }
}
