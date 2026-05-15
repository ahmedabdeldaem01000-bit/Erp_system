<?php

namespace App\Service\Department;
use App\Models\Department;

class DepartmentService
{

public function index(){
    $department=Department::get();
    return $department;
}
    public function store($request)
    {
     

        $department = Department::create([
            'name' => $request->name,
            'position_id' => $request->position_id,
        ]);

        return $department;
    }

    public function update($request, $id)
    {
        $department = Department::findOrFail($id);
        
        
        
        
        $department->update([
            'name' => $request->name,
            'position_id' => $request->position_id,
            ]);
            
            return $department;
            }
            
           public function show(string $id)
{
    $department=Department::findOrFail($id);
    return $department;
}

public function delete($id)
{
    $department = Department::findOrFail($id);

 

    $department->delete();

    return true;
}
}