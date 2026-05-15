<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $employee = Employee::where('email', $data['email'])->first();

        if (!$employee || !Hash::check($data['password'], $employee->password)) {

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // حذف التوكنات القديمة اختياري
        $employee->tokens()->delete();

        // إنشاء token جديد
        $token = $employee->createToken('employee-token')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'token' => $token,
            'employee' => $employee,
            'roles' => $employee->getRoleNames(),
            'permissions' => $employee->getAllPermissions()->pluck('name'),
        ]);
    }
}
