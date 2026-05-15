<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Service\Employee\EmployeeService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


        protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }
public function index()
{
    // بنستخدم with عشان نجيب الأدوار والصلاحيات لكل موظف في خبطة واحدة للداتابيز (Performance)
    $employees = Employee::with(['roles', 'permissions'])->get();

    // لو عاوز شكل البيانات يكون منظم (Mapping)
    $data = $employees->map(function ($employee) {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'email' => $employee->email,
            'roles' => $employee->getRoleNames(), // بجيب أسماء الأدوار بس
            'permissions' => $employee->getAllPermissions()->pluck('name'), // بجيب أسماء الصلاحيات
        ];
    });

    return response()->json([
        'message' => 'Employees retrieved successfully',
        'employees' => $data,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreEmployeeRequest $request)
    {
        $employee = $this->employeeService->store($request);

        return response()->json([
            'message' => 'Employee created successfully',
            'employee' => new EmployeeResource($employee),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee=$this->employeeService->show($id);
           return response()->json([
            'message' => 'successfully',
            'employee' => new EmployeeResource($employee),
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(UpdateEmployeeRequest $request, string $id)
{
    $employee = $this->employeeService->update($request, $id);

    return response()->json([
        'message' => 'Employee updated successfully',
        'employee' => new EmployeeResource($employee),
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $this->employeeService->delete($id);

    return response()->json([
        'message' => 'Employee deleted successfully',
    ]);
}
}
