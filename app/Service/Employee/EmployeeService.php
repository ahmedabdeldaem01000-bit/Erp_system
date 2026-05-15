<?php

namespace App\Service\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    
    public function store($request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' . $request->image->getClientOriginalName();

            $request->image->storeAs('employees', $imageName, 'public');
        }

        $employee = Employee::create([
            'name' => $request->name,
            'salary' => $request->salary,
            'address' => $request->address,

            'image' => $imageName,

            'phone' => $request->phone,

            'gender' => $request->gender,

            'hire_date' => $request->hire_date,

            'email' => $request->email,

            'password' => Hash::make($request->password),

            'department_id' => $request->department_id,
        ]);

        $employee->assignRole('employee');

        return $employee;
    }

    public function update($request, $id)
{
    $employee = Employee::findOrFail($id);

    $imageName = $employee->image;

    if ($request->hasFile('image')) {

        // delete old image
        if ($employee->image &&
            Storage::disk('public')->exists('employees/' . $employee->image)) {

            Storage::disk('public')->delete('employees/' . $employee->image);
        }

        $imageName = time() . '_' . $request->image->getClientOriginalName();

        $request->image->storeAs('employees', $imageName, 'public');
    }

    $employee->update([

        'name' => $request->name,

        'salary' => $request->salary,

        'address' => $request->address,

        'image' => $imageName,

        'phone' => $request->phone,

        'gender' => $request->gender,

        'hire_date' => $request->hire_date,

        'email' => $request->email,

        'department_id' => $request->department_id,
    ]);

    return $employee;
}
public function show(string $id)
{
    $employee=Employee::findOrFail($id);
    return $employee;
}

public function delete($id)
{
    $employee = Employee::findOrFail($id);

    // delete image
    if ($employee->image &&
        Storage::disk('public')->exists('employees/' . $employee->image)) {

        Storage::disk('public')->delete('employees/' . $employee->image);
    }

    $employee->delete();

    return true;
}
}