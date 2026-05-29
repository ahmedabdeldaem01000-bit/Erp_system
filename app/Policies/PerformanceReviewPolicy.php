<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PerformanceReview;
use Illuminate\Auth\Access\Response;

class PerformanceReviewPolicy
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
    public function view(User $user, PerformanceReview $review): bool
    {
        return $user->id === $review->employee_id || 
               $user->id === $review->reviewer_id || 
               $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('create_performance_review');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PerformanceReview $review): bool
    {
        return $user->id === $review->reviewer_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PerformanceReview $review): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('delete_performance_review');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PerformanceReview $review): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PerformanceReview $review): bool
    {
        return $user->hasRole('admin');
    }
}
