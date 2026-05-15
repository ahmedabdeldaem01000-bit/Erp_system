<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Service\Role\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
protected $roleService;

public function __construct(RoleService $roleService)
{
    $this->roleService = $roleService;
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->roleService->index();
        return response()->json([
            'message' => 'successfully',
            'roles' => $roles

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
public function store(Request $request)
{
    $role = $this->roleService->store($request);

    return response()->json([
        'message' => 'Role Created Successfully',
        'role' => $role
    ]);
}

    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $role = $this->roleService->show($id);

    return response()->json([
        'role' => $role
    ]);
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
public function update(Request $request, string $id)
{
    $role = $this->roleService->update($request, $id);

    return response()->json([
        'message' => 'Role Updated',
        'role' => $role
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
   $data= $this->roleService->destroy($id);

    return response()->json([
        'message' => 'Role Deleted Successfully',
        'data'=>$data
    ]);
}


public function assignRole(Request $request, string $id)
{ 
    $request->validate([
        'role' => 'required|exists:roles,name',  
        'permissions' => 'nullable|array',       
        'permissions.*' => 'exists:permissions,name'
    ]);
 
    $employee = Employee::findOrFail($id);
 
    $employee->syncRoles([$request->role]);
 
    if ($request->has('permissions')) {
$role= Role::where('name', $request->role)
                                          ->where('guard_name', 'api')
                                          ->first();
    // dd($role);
        // $role = Role::findByName($request->role);
        
        $role->syncPermissions($request->permissions);
    }

    return response()->json([
        'status' => true,
        'message' => 'success',
        'employee' => $employee->load('roles', 'permissions') 
    ]);
}
}
