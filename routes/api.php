<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Api\Hr\LeaveTypeController;
use App\Http\Controllers\Api\Hr\LeaveRequestController;
use App\Http\Controllers\Api\Hr\PerformanceReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['can:publish articles']], function () {



});

Route::post('login', [AuthController::class, 'login']);

// Route::post('roles', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {


    Route::post('/employees/{id}/assign-role', [RoleController::class, 'assignRole']);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permission', PermissionController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('positions', PositionController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);



    // HR Routes

    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('payrolls', PayrollController::class);

    // Leave Types
    Route::get('leave-types/paid', [LeaveTypeController::class, 'paidLeaveTypes']);
    Route::get('leave-types/unpaid', [LeaveTypeController::class, 'unpaidLeaveTypes']);
 
    Route::apiResource('leave-types', LeaveTypeController::class);

    // Leave Requests

    Route::get('leave-requests/pending', [LeaveRequestController::class, 'pending']);
    Route::get('leave-requests/employee/{employeeId}', [LeaveRequestController::class, 'employeeRequests']);
    Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);
    Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject']);
    Route::apiResource('leave-requests', LeaveRequestController::class);
    // Performance Reviews 

    Route::get('performance-reviews/high-performers', [PerformanceReviewController::class, 'highPerformers']);
    Route::get('performance-reviews/average-performers', [PerformanceReviewController::class, 'averagePerformers']);
    Route::get('performance-reviews/low-performers', [PerformanceReviewController::class, 'lowPerformers']);
    Route::get('performance-reviews/department-statistics', [PerformanceReviewController::class, 'departmentStatistics']);
    Route::get('performance-reviews/employee/{employeeId}/trend', [PerformanceReviewController::class, 'employeeTrend']);
    Route::apiResource('performance-reviews', PerformanceReviewController::class);
    Route::apiResource('purchases', PurchaseController::class);

    
});


