<?php

namespace App\Service\Permission;

use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
 
class PermissionService
{
    public function index()
    {
        return Permission::get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        return Permission::create([
            'name' => $request->name,
            'guard_name' =>'api'
        ]);
    }

    public function show(string $id)
    {
        return Permission::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $permission->update([
            'name' => $request->name,
        ]);

        return $permission;
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        return true;
    }

 
}