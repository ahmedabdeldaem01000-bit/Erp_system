<?php

namespace App\Service\Role;

use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function index()
    {
        return Role::with('permissions')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        return Role::create([
            'name' => $request->name,
            'guard_name'=>'api'
        ]);
    }

    public function show(string $id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $role->update([
            'name' => $request->name,
        ]);

        return $role;
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
 
        $role->delete();

        return true;
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

    
        $role = Role::findByName($request->role);
        $role->syncPermissions($request->permissions);
    }

    return response()->json([
        'status' => true,
        'message' => 'تم تعيين الدور بنجاح',
        'employee' => $employee->load('roles', 'permissions') 
    ]);
}

 
}