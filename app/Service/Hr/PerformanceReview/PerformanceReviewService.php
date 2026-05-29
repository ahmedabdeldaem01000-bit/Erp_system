<?php

namespace App\Service\Hr\PerformanceReview;

use App\Models\PerformanceReview;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Service for handling Performance Review operations.
 */
class PerformanceReviewService
{
    /**
     * Get all performance reviews with pagination.
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get performance reviews by employee.
     */
    public function getByEmployee(int $employeeId, int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->byEmployee($employeeId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get performance reviews by reviewer.
     */
    public function getByReviewer(int $reviewerId, int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->byReviewer($reviewerId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get performance reviews by period.
     */
    public function getByPeriod(string $period, int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->byPeriod($period)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Search performance reviews by employee name.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->join('employees as e', 'performance_reviews.employee_id', '=', 'e.id')
            ->where('e.name', 'like', "%{$query}%")
            ->select('performance_reviews.*')
            ->latest('performance_reviews.created_at')
            ->paginate($perPage);
    }

    /**
     * Get performance review by ID.
     */
    public function getById(int $id): ?PerformanceReview
    {
        return PerformanceReview::with(['employee', 'reviewer'])->find($id);
    }

    /**
     * Create a new performance review.
     */
    public function create(array $data): PerformanceReview
    {
        return DB::transaction(function () use ($data) {
            // Validate ratings
            $ratingFields = ['quality_of_work', 'productivity', 'communication', 'teamwork', 'leadership'];
            foreach ($ratingFields as $field) {
                if (!isset($data[$field]) || $data[$field] < 1 || $data[$field] > 10) {
                    throw new Exception("Invalid rating for {$field}. Must be between 1 and 10.");
                }
            }

            $review = PerformanceReview::create($data);
            return $review->fresh();
        });
    }

    /**
     * Update performance review.
     */
    public function update(PerformanceReview $review, array $data): PerformanceReview
    {
        return DB::transaction(function () use ($review, $data) {
            // Validate ratings if provided
            $ratingFields = ['quality_of_work', 'productivity', 'communication', 'teamwork', 'leadership'];
            foreach ($ratingFields as $field) {
                if (isset($data[$field]) && ($data[$field] < 1 || $data[$field] > 10)) {
                    throw new Exception("Invalid rating for {$field}. Must be between 1 and 10.");
                }
            }

            $review->update($data);
            return $review->fresh();
        });
    }

    /**
     * Delete performance review.
     */
    public function delete(PerformanceReview $review): bool
    {
        return (bool) $review->delete();
    }

    /**
     * Force delete performance review.
     */
    public function forceDelete(PerformanceReview $review): bool
    {
        return (bool) $review->forceDelete();
    }

    /**
     * Restore soft-deleted performance review.
     */
    public function restore(int $id): bool
    {
        return (bool) PerformanceReview::onlyTrashed()
            ->where('id', $id)
            ->restore();
    }

    /**
     * Get high performers.
     */
    public function getHighPerformers(int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->where('overall_rating', '>=', 8)
            ->orderByRating('desc')
            ->paginate($perPage);
    }

    /**
     * Get average performers.
     */
    public function getAveragePerformers(int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->whereBetween('overall_rating', [5, 7.99])
            ->orderByRating('desc')
            ->paginate($perPage);
    }

    /**
     * Get low performers.
     */
    public function getLowPerformers(int $perPage = 15): LengthAwarePaginator
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->where('overall_rating', '<', 5)
            ->orderByRating('asc')
            ->paginate($perPage);
    }

    /**
     * Get department average rating.
     */
    public function getDepartmentAverageRating(int $departmentId): float
    {
        return PerformanceReview::join('employees', 'performance_reviews.employee_id', '=', 'employees.id')
            ->where('employees.department_id', $departmentId)
            ->avg('performance_reviews.overall_rating') ?? 0;
    }

    /**
     * Get top performers in department.
     */
    public function getTopPerformersInDepartment(int $departmentId, int $limit = 5): Collection
    {
        return PerformanceReview::with(['employee', 'reviewer'])
            ->join('employees', 'performance_reviews.employee_id', '=', 'employees.id')
            ->where('employees.department_id', $departmentId)
            ->select('performance_reviews.*')
            ->orderByRating('desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get employee average rating.
     */
    public function getEmployeeAverageRating(int $employeeId): float
    {
        return PerformanceReview::byEmployee($employeeId)
            ->avg('overall_rating') ?? 0;
    }

    /**
     * Get employee rating trend.
     */
    public function getEmployeeRatingTrend(int $employeeId): Collection
    {
        return PerformanceReview::byEmployee($employeeId)
            ->select('review_period', 'overall_rating')
            ->latest('review_period')
            ->get();
    }
}
