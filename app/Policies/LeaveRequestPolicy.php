<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;
use Illuminate\Auth\Access\Response;

class LeaveRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->employee_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->employee_id && $leaveRequest->isPending();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->employee_id && $leaveRequest->isPending();
    }

    /**
     * Determine whether the user can approve a leave request.
     */
    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('approve_leave_request');
    }

    /**
     * Determine whether the user can reject a leave request.
     */
    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('reject_leave_request');
    }
}
