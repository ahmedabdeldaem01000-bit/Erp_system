<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Employee extends Authenticatable
{
    use HasApiTokens,HasRoles;
    protected $guard_name = 'api';  
protected $fillable = [
    'name',
    'salary',
    'address',
    'image',
    'phone',
    'gender',
    'hire_date',
    'email',
    'password',
    'department_id',
];
    
}
