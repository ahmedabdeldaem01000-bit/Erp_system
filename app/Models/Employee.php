<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory;
    
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
    
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);   
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class); 
    }

    /**
     * Get all leave requests for this employee.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get all leave requests approved by this employee.
     */
    public function approvedLeaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }

    /**
     * Get all performance reviews for this employee.
     */
    public function performanceReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class);
    }

    /**
     * Get all performance reviews conducted by this employee.
     */
    public function conductedReviews(): HasMany
    {
        return $this->hasMany(PerformanceReview::class, 'reviewer_id');
    }

    /**
     * Get the department this employee belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
