<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformanceReviewRequest;
use App\Http\Requests\UpdatePerformanceReviewRequest;
use App\Http\Resources\PerformanceReviewResource;
use App\Models\PerformanceReview;
use App\Service\Hr\PerformanceReview\PerformanceReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for managing Performance Reviews.
 *
 * @group HR - Performance Reviews
 */
class PerformanceReviewController extends Controller
{
    /**
     * Inject the PerformanceReviewService dependency.
     */
    public function __construct(private PerformanceReviewService $service)
    {
        // Service is injected
    }

    /**
     * List all performance reviews with pagination and filtering.
     *
     * @queryParam per_page int The number of items per page. Default is 15. Example: 20
     * @queryParam employee_id int Filter by employee ID. Example: 1
     * @queryParam reviewer_id int Filter by reviewer ID. Example: 2
     * @queryParam review_period string Filter by review period. Example: 2024-Q1
     * @queryParam search string Search by employee name. Example: John
     * @queryParam performance string Filter by performance level (high, average, low). Example: high
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $employeeId = $request->query('employee_id');
        $reviewerId = $request->query('reviewer_id');
        $reviewPeriod = $request->query('review_period');
        $search = $request->query('search');
        $performance = $request->query('performance');

        if ($search) {
            $reviews = $this->service->search($search, $perPage);
        } elseif ($performance === 'high') {
            $reviews = $this->service->getHighPerformers($perPage);
        } elseif ($performance === 'average') {
            $reviews = $this->service->getAveragePerformers($perPage);
        } elseif ($performance === 'low') {
            $reviews = $this->service->getLowPerformers($perPage);
        } elseif ($employeeId) {
            $reviews = $this->service->getByEmployee($employeeId, $perPage);
        } elseif ($reviewerId) {
            $reviews = $this->service->getByReviewer($reviewerId, $perPage);
        } elseif ($reviewPeriod) {
            $reviews = $this->service->getByPeriod($reviewPeriod, $perPage);
        } else {
            $reviews = $this->service->getAll($perPage);
        }

        return response()->json([
            'status' => true,
            'message' => 'Performance reviews retrieved successfully',
            'data' => PerformanceReviewResource::collection($reviews),
            'pagination' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
        ]);
    }

    /**
     * Create a new performance review.
     *
     * @bodyParam employee_id int required The ID of the employee. Example: 1
     * @bodyParam reviewer_id int required The ID of the reviewer. Example: 2
     * @bodyParam review_period string required The review period. Example: 2024-Q1
     * @bodyParam quality_of_work int required Rating 1-10. Example: 8
     * @bodyParam productivity int required Rating 1-10. Example: 9
     * @bodyParam communication int required Rating 1-10. Example: 7
     * @bodyParam teamwork int required Rating 1-10. Example: 8
     * @bodyParam leadership int required Rating 1-10. Example: 6
     * @bodyParam strengths string Strengths description. Example: Good at problem-solving
     * @bodyParam areas_for_improvement string Areas for improvement. Example: Time management
     * @bodyParam goals string Goals. Example: Improve communication skills
     * @bodyParam comments string Additional comments. Example: Keep up the good work
     *
     * @return JsonResponse
     */
    public function store(StorePerformanceReviewRequest $request): JsonResponse
    {
        try {
            $review = $this->service->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Performance review created successfully',
                'data' => new PerformanceReviewResource($review->load(['employee', 'reviewer'])),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create performance review',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Retrieve a specific performance review.
     *
     * @urlParam performance_review int required The ID of the performance review. Example: 1
     *
     * @return JsonResponse
     */
    public function show(PerformanceReview $performanceReview): JsonResponse
    {
        $performanceReview->load(['employee', 'reviewer']);

        return response()->json([
            'status' => true,
            'message' => 'Performance review retrieved successfully',
            'data' => new PerformanceReviewResource($performanceReview),
        ]);
    }

    /**
     * Update a performance review.
     *
     * @urlParam performance_review int required The ID of the performance review. Example: 1
     * @bodyParam quality_of_work int Rating 1-10. Example: 8
     * @bodyParam productivity int Rating 1-10. Example: 9
     * @bodyParam communication int Rating 1-10. Example: 8
     * @bodyParam teamwork int Rating 1-10. Example: 8
     * @bodyParam leadership int Rating 1-10. Example: 7
     * @bodyParam strengths string Strengths description.
     * @bodyParam areas_for_improvement string Areas for improvement.
     * @bodyParam goals string Goals.
     * @bodyParam comments string Additional comments.
     *
     * @return JsonResponse
     */
    public function update(UpdatePerformanceReviewRequest $request, PerformanceReview $performanceReview): JsonResponse
    {
        try {
            $updated = $this->service->update($performanceReview, $request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Performance review updated successfully',
                'data' => new PerformanceReviewResource($updated->load(['employee', 'reviewer'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update performance review',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a performance review (soft delete).
     *
     * @urlParam performance_review int required The ID of the performance review. Example: 1
     *
     * @return JsonResponse
     */
    public function destroy(PerformanceReview $performanceReview): JsonResponse
    {
        try {
            $this->service->delete($performanceReview);

            return response()->json([
                'status' => true,
                'message' => 'Performance review deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete performance review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get high performers.
     *
     * @return JsonResponse
     */
    public function highPerformers(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $reviews = $this->service->getHighPerformers($perPage);

        return response()->json([
            'status' => true,
            'message' => 'High performers retrieved successfully',
            'data' => PerformanceReviewResource::collection($reviews),
            'pagination' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
        ]);
    }

    /**
     * Get average performers.
     *
     * @return JsonResponse
     */
    public function averagePerformers(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $reviews = $this->service->getAveragePerformers($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Average performers retrieved successfully',
            'data' => PerformanceReviewResource::collection($reviews),
            'pagination' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
        ]);
    }

    /**
     * Get low performers.
     *
     * @return JsonResponse
     */
    public function lowPerformers(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $reviews = $this->service->getLowPerformers($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Low performers retrieved successfully',
            'data' => PerformanceReviewResource::collection($reviews),
            'pagination' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ],
        ]);
    }

    /**
     * Get department statistics.
     *
     * @queryParam department_id int required The department ID. Example: 1
     *
     * @return JsonResponse
     */
    public function departmentStatistics(Request $request): JsonResponse
    {
        $request->validate([
            'department_id' => ['required', 'numeric', 'exists:departments,id'],
        ]);

        $departmentId = $request->query('department_id');
        $avgRating = $this->service->getDepartmentAverageRating($departmentId);
        $topPerformers = $this->service->getTopPerformersInDepartment($departmentId);

        return response()->json([
            'status' => true,
            'message' => 'Department statistics retrieved successfully',
            'data' => [
                'department_id' => $departmentId,
                'average_rating' => $avgRating,
                'top_performers' => PerformanceReviewResource::collection($topPerformers),
            ],
        ]);
    }

    /**
     * Get employee performance trend.
     *
     * @urlParam employee_id int required The ID of the employee. Example: 1
     *
     * @return JsonResponse
     */
    public function employeeTrend(int $employeeId): JsonResponse
    {
        $trend = $this->service->getEmployeeRatingTrend($employeeId);
        $avgRating = $this->service->getEmployeeAverageRating($employeeId);

        return response()->json([
            'status' => true,
            'message' => 'Employee performance trend retrieved successfully',
            'data' => [
                'employee_id' => $employeeId,
                'average_rating' => $avgRating,
                'trend' => $trend,
            ],
        ]);
    }
}
