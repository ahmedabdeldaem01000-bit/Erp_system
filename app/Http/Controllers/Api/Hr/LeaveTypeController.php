<?php

namespace App\Http\Controllers\Api\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveTypeRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Http\Resources\LeaveTypeResource;
use App\Models\LeaveType;
use App\Service\Hr\LeaveType\LeaveTypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Controller for managing Leave Types.
 *
 * @group HR - Leave Types
 */
class LeaveTypeController extends Controller
{
    /**
     * Inject the LeaveTypeService dependency.
     */
    public function __construct(private LeaveTypeService $service)
    {
        // Service is injected
    }

    /**
     * List all leave types with pagination.
     *
     * @queryParam per_page int The number of items per page. Default is 15. Example: 20
     * @queryParam search string Search leave types by name. Example: Annual
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);
        $search = $request->query('search');

        $leaveTypes = $search
            ? $this->service->search($search, $perPage)
            : $this->service->getAll($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Leave types retrieved successfully',
            'data' => LeaveTypeResource::collection($leaveTypes),
            'pagination' => [
                'total' => $leaveTypes->total(),
                'per_page' => $leaveTypes->perPage(),
                'current_page' => $leaveTypes->currentPage(),
                'last_page' => $leaveTypes->lastPage(),
            ],
        ]);
    }

    /**
     * Create a new leave type.
     *
     * @bodyParam name string required The name of the leave type. Example: Annual Leave
     * @bodyParam days_per_year int required Number of days per year. Example: 21
     * @bodyParam is_paid bool required Whether the leave is paid. Example: true
     *
     * @return JsonResponse
     */
    public function store(StoreLeaveTypeRequest $request): JsonResponse
    {
        try {
            $leaveType = $this->service->create($request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Leave type created successfully',
                'data' => new LeaveTypeResource($leaveType),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create leave type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retrieve a specific leave type.
     *
     * @urlParam leave_type int required The ID of the leave type. Example: 1
     *
     * @return JsonResponse
     */
    public function show(LeaveType $leaveType): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => 'Leave type retrieved successfully',
            'data' => new LeaveTypeResource($leaveType),
        ]);
    }

    /**
     * Update a leave type.
     *
     * @urlParam leave_type int required The ID of the leave type. Example: 1
     * @bodyParam name string The name of the leave type. Example: Annual Leave
     * @bodyParam days_per_year int Number of days per year. Example: 30
     * @bodyParam is_paid bool Whether the leave is paid. Example: true
     *
     * @return JsonResponse
     */
    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType): JsonResponse
    {
        try {
            $updated = $this->service->update($leaveType, $request->validated());

            return response()->json([
                'status' => true,
                'message' => 'Leave type updated successfully',
                'data' => new LeaveTypeResource($updated),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update leave type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a leave type (soft delete).
     *
     * @urlParam leave_type int required The ID of the leave type. Example: 1
     *
     * @return JsonResponse
     */
    public function destroy(LeaveType $leaveType): JsonResponse
    {
        try {
            $this->service->delete($leaveType);

            return response()->json([
                'status' => true,
                'message' => 'Leave type deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete leave type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all paid leave types.
     *
     * @return JsonResponse
     */
    public function paidLeaveTypes(): JsonResponse
    {
        $leaveTypes = $this->service->getPaidLeaveTypes();

        return response()->json([
            'status' => true,
            'message' => 'Paid leave types retrieved successfully',
            'data' => LeaveTypeResource::collection($leaveTypes),
        ]);
    }

    /**
     * Get all unpaid leave types.
     *
     * @return JsonResponse
     */
    public function unpaidLeaveTypes(): JsonResponse
    {
        $leaveTypes = $this->service->getUnpaidLeaveTypes();

        return response()->json([
            'status' => true,
            'message' => 'Unpaid leave types retrieved successfully',
            'data' => LeaveTypeResource::collection($leaveTypes),
        ]);
    }
}
