<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Models\LeaveRequest;
use App\Service\Hr\LeaveRequest\LeaveRequestService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for managing Leave Requests.
 *
 * @group HR - Leave Requests
 */
class LeaveRequestController extends Controller
{
    /**
     * Inject the LeaveRequestService dependency.
     */
    public function __construct(private LeaveRequestService $service)
    {
        // Service is injected
    }

    /**
     * List all leave requests with pagination and filtering.
     *
     * @queryParam per_page int The number of items per page. Default is 15. Example: 20
     * @queryParam status string Filter by status (pending, approved, rejected). Example: pending
     * @queryParam employee_id int Filter by employee ID. Example: 1
     * @queryParam search string Search by employee name. Example: John
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $status = $request->query('status');
        $employeeId = $request->query('employee_id');
        $search = $request->query('search');

        if ($search) {
            $leaveRequests = $this->service->search($search, $perPage);
        } elseif ($employeeId) {
            $leaveRequests = $this->service->getByEmployee($employeeId, $perPage);
        } elseif ($status) {
            $leaveRequests = $this->service->getByStatus($status, $perPage);
        } else {
            $leaveRequests = $this->service->getAll($perPage);
        }

        return response()->json([
            'status' => true,
            'message' => 'Leave requests retrieved successfully',
            'data' => LeaveRequestResource::collection($leaveRequests),
            'pagination' => [
                'total' => $leaveRequests->total(),
                'per_page' => $leaveRequests->perPage(),
                'current_page' => $leaveRequests->currentPage(),
                'last_page' => $leaveRequests->lastPage(),
            ],
        ]);
    }

    /**
     * Create a new leave request.
     *
     * @bodyParam employee_id int required The ID of the employee. Example: 1
     * @bodyParam leave_type_id int required The ID of the leave type. Example: 1
     * @bodyParam start_date date required The start date (YYYY-MM-DD). Example: 2026-06-01
     * @bodyParam end_date date required The end date (YYYY-MM-DD). Example: 2026-06-05
     * @bodyParam reason string required The reason for the leave. Example: Personal reasons
     *
     * @return JsonResponse
     */
    public function store(StoreLeaveRequestRequest $request): JsonResponse
    {
        try {
            $leaveRequest = $this->service->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Leave request created successfully',
                'data' => new LeaveRequestResource($leaveRequest->load(['employee', 'leaveType', 'approvedBy'])),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create leave request',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Retrieve a specific leave request.
     *
     * @urlParam leave_request int required The ID of the leave request. Example: 1
     *
     * @return JsonResponse
     */
    public function show(LeaveRequest $leaveRequest): JsonResponse
    {
        $leaveRequest->load(['employee', 'leaveType', 'approvedBy']);

        return response()->json([
            'status' => true,
            'message' => 'Leave request retrieved successfully',
            'data' => new LeaveRequestResource($leaveRequest),
        ]);
    }

    /**
     * Update a leave request (only if pending).
     *
     * @urlParam leave_request int required The ID of the leave request. Example: 1
     * @bodyParam employee_id int The ID of the employee. Example: 1
     * @bodyParam leave_type_id int The ID of the leave type. Example: 1
     * @bodyParam start_date date The start date (YYYY-MM-DD). Example: 2026-06-01
     * @bodyParam end_date date The end date (YYYY-MM-DD). Example: 2026-06-05
     * @bodyParam reason string The reason for the leave. Example: Updated reason
     *
     * @return JsonResponse
     */
    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest): JsonResponse
    {
        try {
            $updated = $this->service->update($leaveRequest, $request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Leave request updated successfully',
                'data' => new LeaveRequestResource($updated->load(['employee', 'leaveType', 'approvedBy'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update leave request',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete a leave request (soft delete).
     *
     * @urlParam leave_request int required The ID of the leave request. Example: 1
     *
     * @return JsonResponse
     */
    public function destroy(LeaveRequest $leaveRequest): JsonResponse
    {
        try {
            $this->service->delete($leaveRequest);

            return response()->json([
                'status' => true,
                'message' => 'Leave request deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete leave request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve a leave request.
     *
     * @urlParam leave_request int required The ID of the leave request. Example: 1
     * @bodyParam approved_by int required The ID of the approver. Example: 2
     *
     * @return JsonResponse
     */
    public function approve(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        try {
            $request->validate([
                'approved_by' => ['required', 'numeric', 'exists:employees,id'],
            ]);

            $approved = $this->service->approve($leaveRequest, $request->approved_by);

            return response()->json([
                'status' => true,
                'message' => 'Leave request approved successfully',
                'data' => new LeaveRequestResource($approved->load(['employee', 'leaveType', 'approvedBy'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to approve leave request',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reject a leave request.
     *
     * @urlParam leave_request int required The ID of the leave request. Example: 1
     * @bodyParam rejected_by int required The ID of the person rejecting. Example: 2
     * @bodyParam rejection_reason string required The reason for rejection. Example: Budget constraints
     *
     * @return JsonResponse
     */
    public function reject(Request $request, LeaveRequest $leaveRequest): JsonResponse
    {
        try {
            $request->validate([
                'rejected_by' => ['required', 'numeric', 'exists:employees,id'],
                'rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
            ]);

            $rejected = $this->service->reject($leaveRequest, $request->rejected_by, $request->rejection_reason);

            return response()->json([
                'status' => true,
                'message' => 'Leave request rejected successfully',
                'data' => new LeaveRequestResource($rejected->load(['employee', 'leaveType', 'approvedBy'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to reject leave request',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get pending leave requests.
     *
     * @return JsonResponse
     */
    public function pending(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $leaveRequests = $this->service->getPendingRequests($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Pending leave requests retrieved successfully',
            'data' => LeaveRequestResource::collection($leaveRequests),
            'pagination' => [
                'total' => $leaveRequests->total(),
                'per_page' => $leaveRequests->perPage(),
                'current_page' => $leaveRequests->currentPage(),
                'last_page' => $leaveRequests->lastPage(),
            ],
        ]);
    }

    /**
     * Get employee leave requests.
     *
     * @urlParam employee_id int required The ID of the employee. Example: 1
     *
     * @return JsonResponse
     */
    public function employeeRequests(Request $request, int $employeeId): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $leaveRequests = $this->service->getByEmployee($employeeId, $perPage);

        return response()->json([
            'status' => true,
            'message' => 'Employee leave requests retrieved successfully',
            'data' => LeaveRequestResource::collection($leaveRequests),
            'pagination' => [
                'total' => $leaveRequests->total(),
                'per_page' => $leaveRequests->perPage(),
                'current_page' => $leaveRequests->currentPage(),
                'last_page' => $leaveRequests->lastPage(),
            ],
        ]);
    }
}
