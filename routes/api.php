<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
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



});


