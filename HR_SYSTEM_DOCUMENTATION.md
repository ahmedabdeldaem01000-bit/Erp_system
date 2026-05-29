# HR System - Complete Documentation

## 📋 Project Overview

This is a **Production-Ready HR Management System** built with **Laravel 12** following industry best practices and SOLID principles.

### System Components

1. **Leave Management System**
   - Leave Types Management
   - Leave Requests (Create, Read, Update, Delete, Approve, Reject)
   - Automatic day calculation
   - Overlap detection
   - Status tracking (Pending, Approved, Rejected)

2. **Performance Review System**
   - 5-Point Rating System (Quality, Productivity, Communication, Teamwork, Leadership)
   - Auto-calculated Overall Rating
   - Department Statistics
   - Performance Trending
   - Employee Performance Analysis

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/Hr/
│   │   ├── LeaveTypeController.php
│   │   ├── LeaveRequestController.php
│   │   └── PerformanceReviewController.php
│   ├── Requests/
│   │   ├── StoreLeaveTypeRequest.php
│   │   ├── UpdateLeaveTypeRequest.php
│   │   ├── StoreLeaveRequestRequest.php
│   │   ├── UpdateLeaveRequestRequest.php
│   │   ├── StorePerformanceReviewRequest.php
│   │   └── UpdatePerformanceReviewRequest.php
│   ├── Resources/
│   │   ├── LeaveTypeResource.php
│   │   ├── LeaveRequestResource.php
│   │   └── PerformanceReviewResource.php
│
├── Models/
│   ├── LeaveType.php
│   ├── LeaveRequest.php
│   ├── PerformanceReview.php
│   └── Employee.php (updated with relationships)
│
├── Service/Hr/
│   ├── LeaveType/
│   │   └── LeaveTypeService.php
│   ├── LeaveRequest/
│   │   └── LeaveRequestService.php
│   └── PerformanceReview/
│       └── PerformanceReviewService.php
│
├── Policies/
│   ├── LeaveRequestPolicy.php
│   └── PerformanceReviewPolicy.php

database/
├── migrations/
│   ├── 2026_05_22_000001_create_leave_types_table.php
│   ├── 2026_05_22_000002_create_leave_requests_table.php
│   └── 2026_05_22_000003_create_performance_reviews_table.php
├── factories/
│   ├── LeaveTypeFactory.php
│   ├── LeaveRequestFactory.php
│   └── PerformanceReviewFactory.php
└── seeders/
    ├── LeaveTypeSeeder.php
    ├── LeaveRequestSeeder.php
    └── PerformanceReviewSeeder.php

routes/
└── api.php (updated with HR routes)
```

---

## 🗄️ Database Schema

### leave_types
```
id              - Primary Key
name            - Leave type name (unique)
days_per_year   - Number of days allowed per year
is_paid         - Boolean (true = paid, false = unpaid)
timestamps      - created_at, updated_at
soft_deletes    - deleted_at
```

### leave_requests
```
id              - Primary Key
employee_id     - Foreign Key (employees)
leave_type_id   - Foreign Key (leave_types)
start_date      - Leave start date
end_date        - Leave end date
days            - Total days (auto-calculated)
reason          - Leave reason (text)
status          - Enum: pending, approved, rejected
approved_by     - Foreign Key (employees, nullable)
approved_at     - Timestamp (nullable)
rejection_reason - Text (nullable)
timestamps      - created_at, updated_at
soft_deletes    - deleted_at
indexes         - employee_id, status, dates
```

### performance_reviews
```
id                      - Primary Key
employee_id             - Foreign Key (employees)
reviewer_id             - Foreign Key (employees)
review_period           - Period string (e.g., 2024-Q1)
quality_of_work         - Rating 1-10
productivity            - Rating 1-10
communication           - Rating 1-10
teamwork                - Rating 1-10
leadership              - Rating 1-10
overall_rating          - Auto-calculated (decimal)
strengths               - Text (nullable)
areas_for_improvement   - Text (nullable)
goals                   - Text (nullable)
comments                - Text (nullable)
timestamps              - created_at, updated_at
soft_deletes            - deleted_at
indexes                 - employee_id, overall_rating
```

---

## 🔗 Relationships

### Employee Model
```php
// One-to-Many Relationships
leaveRequests()         // Leave requests by this employee
approvedLeaveRequests() // Leave requests approved by this employee
performanceReviews()    // Performance reviews for this employee
conductedReviews()      // Performance reviews conducted by this employee
```

### LeaveType Model
```php
leaveRequests()         // All leave requests using this type
```

### LeaveRequest Model
```php
employee()              // The employee who requested leave
leaveType()             // The type of leave requested
approvedBy()            // The employee who approved/rejected
```

### PerformanceReview Model
```php
employee()              // The employee being reviewed
reviewer()              // The employee conducting the review
```

---

## 🛠️ Service Layer

Each service implements CRUD operations with additional business logic.

### LeaveTypeService
- `getAll(perPage)` - Get paginated leave types
- `search(query, perPage)` - Search by name
- `getById(id)` - Get single leave type
- `create(data)` - Create new
- `update(leaveType, data)` - Update
- `delete(leaveType)` - Soft delete
- `forceDelete(leaveType)` - Hard delete
- `restore(id)` - Restore soft-deleted
- `getPaidLeaveTypes()` - Get paid types
- `getUnpaidLeaveTypes()` - Get unpaid types

### LeaveRequestService
- `getAll(perPage)` - Get all paginated
- `getByEmployee(employeeId, perPage)` - Filter by employee
- `getByStatus(status, perPage)` - Filter by status
- `search(query, perPage)` - Search by employee name
- `getById(id)` - Get single request
- `create(data)` - Create with validation and date calculation
- `update(leaveRequest, data)` - Update (only if pending)
- `approve(leaveRequest, approvedByEmployeeId)` - Approve request
- `reject(leaveRequest, rejectedByEmployeeId, reason)` - Reject request
- `delete(leaveRequest)` - Soft delete
- `getPendingRequests()` - Get pending only
- `getApprovedRequests()` - Get approved only
- `getRejectedRequests()` - Get rejected only
- `getByDateRange(startDate, endDate)` - Filter by date range

**Business Logic:**
- ✅ Automatically calculates days between dates
- ✅ Prevents overlapping leaves for same employee
- ✅ Validates dates and status
- ✅ Uses database transactions for consistency

### PerformanceReviewService
- `getAll(perPage)` - Get all paginated
- `getByEmployee(employeeId, perPage)` - Filter by employee
- `getByReviewer(reviewerId, perPage)` - Filter by reviewer
- `getByPeriod(period, perPage)` - Filter by period
- `search(query, perPage)` - Search by employee name
- `getById(id)` - Get single review
- `create(data)` - Create with rating validation
- `update(review, data)` - Update
- `delete(review)` - Soft delete
- `getHighPerformers(perPage)` - Rating >= 8
- `getAveragePerformers(perPage)` - Rating 5-7.99
- `getLowPerformers(perPage)` - Rating < 5
- `getDepartmentAverageRating(departmentId)` - Dept statistics
- `getTopPerformersInDepartment(departmentId, limit)` - Top N in dept
- `getEmployeeAverageRating(employeeId)` - Employee avg
- `getEmployeeRatingTrend(employeeId)` - Historical trend

**Business Logic:**
- ✅ Auto-calculates overall rating from 5 metrics
- ✅ Validates all ratings are between 1-10
- ✅ Uses eager loading for performance
- ✅ Calculates department statistics

---

## 📝 Validation Rules

### LeaveType
```
name:        required, string, max:255, unique
days_per_year: required, numeric, min:0, max:365
is_paid:     required, boolean
```

### LeaveRequest
```
employee_id:   required, exists:employees
leave_type_id: required, exists:leave_types
start_date:    required, date, after_or_equal:today
end_date:      required, date, after_or_equal:start_date
reason:        required, string, min:10, max:1000
```

### PerformanceReview
```
employee_id:  required, exists:employees
reviewer_id:  required, exists:employees, different:employee_id
review_period: required, string, max:255
ratings:      required, numeric, min:1, max:10
strengths:    nullable, string, max:1000
areas_for_improvement: nullable, string, max:1000
goals:        nullable, string, max:1000
comments:     nullable, string, max:2000
```

---

## 🔒 Authorization (Policies)

### LeaveRequestPolicy
- `viewAny()` - All users
- `view()` - Own request or admin
- `create()` - All users
- `update()` - Own pending request only
- `delete()` - Own pending request only
- `approve()` - Admin or with permission
- `reject()` - Admin or with permission

### PerformanceReviewPolicy
- `viewAny()` - All users
- `view()` - Employee, reviewer, or admin
- `create()` - Admin or with permission
- `update()` - Reviewer or admin
- `delete()` - Admin or with permission
- `restore()` - Admin only
- `forceDelete()` - Admin only

---

## 📡 API Endpoints

### Leave Types (RESTful)
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/leave-types` | List all with pagination |
| POST | `/api/leave-types` | Create new |
| GET | `/api/leave-types/{id}` | Show single |
| PUT | `/api/leave-types/{id}` | Update |
| DELETE | `/api/leave-types/{id}` | Delete |
| GET | `/api/leave-types/paid` | Get paid types |
| GET | `/api/leave-types/unpaid` | Get unpaid types |

**Query Parameters:**
- `per_page` (default: 15)
- `search` - Search by name

### Leave Requests (RESTful)
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/leave-requests` | List all with pagination |
| POST | `/api/leave-requests` | Create new |
| GET | `/api/leave-requests/{id}` | Show single |
| PUT | `/api/leave-requests/{id}` | Update (pending only) |
| DELETE | `/api/leave-requests/{id}` | Delete |
| POST | `/api/leave-requests/{id}/approve` | Approve request |
| POST | `/api/leave-requests/{id}/reject` | Reject request |
| GET | `/api/leave-requests/pending` | Get pending only |
| GET | `/api/leave-requests/employee/{id}` | Get employee's requests |

**Query Parameters:**
- `per_page` (default: 15)
- `status` - Filter: pending, approved, rejected
- `employee_id` - Filter by employee
- `search` - Search by employee name

### Performance Reviews (RESTful)
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/performance-reviews` | List all with pagination |
| POST | `/api/performance-reviews` | Create new |
| GET | `/api/performance-reviews/{id}` | Show single |
| PUT | `/api/performance-reviews/{id}` | Update |
| DELETE | `/api/performance-reviews/{id}` | Delete |
| GET | `/api/performance-reviews/high-performers` | Rating >= 8 |
| GET | `/api/performance-reviews/average-performers` | Rating 5-7.99 |
| GET | `/api/performance-reviews/low-performers` | Rating < 5 |
| GET | `/api/performance-reviews/department-statistics` | Dept stats |
| GET | `/api/performance-reviews/employee/{id}/trend` | Employee trend |

**Query Parameters:**
- `per_page` (default: 15)
- `employee_id` - Filter by employee
- `reviewer_id` - Filter by reviewer
- `review_period` - Filter by period (e.g., 2024-Q1)
- `search` - Search by employee name
- `performance` - Filter: high, average, low

---

## 📊 JSON Response Format

### Success Response
```json
{
  "status": true,
  "message": "Operation completed successfully",
  "data": { ... },
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7
  }
}
```

### Error Response
```json
{
  "status": false,
  "message": "Operation failed",
  "error": "Error description"
}
```

---

## 🚀 Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Run Seeders
```bash
php artisan db:seed --class=LeaveTypeSeeder
php artisan db:seed --class=LeaveRequestSeeder
php artisan db:seed --class=PerformanceReviewSeeder
```

### 3. Or Seed All
```bash
php artisan db:seed
```

### 4. Clear Cache (if needed)
```bash
php artisan optimize:clear
```

---

## 💻 Usage Examples

### Create a Leave Request
```php
$leaveRequest = LeaveRequest::create([
    'employee_id' => 1,
    'leave_type_id' => 1,
    'start_date' => '2026-06-01',
    'end_date' => '2026-06-05',
    'reason' => 'Personal reasons',
]);
```

### Approve a Leave Request
```php
$service = app(LeaveRequestService::class);
$approved = $service->approve($leaveRequest, 2); // Approved by employee 2
```

### Create Performance Review
```php
$review = PerformanceReview::create([
    'employee_id' => 1,
    'reviewer_id' => 2,
    'review_period' => '2024-Q1',
    'quality_of_work' => 8,
    'productivity' => 9,
    'communication' => 7,
    'teamwork' => 8,
    'leadership' => 6,
]);
// overall_rating is auto-calculated as 7.6
```

---

## 🧪 Testing

The system includes factories for testing:

```php
// Create test data
$leaveType = LeaveType::factory()->create();
$leaveRequest = LeaveRequest::factory()->approved()->create();
$review = PerformanceReview::factory()->highPerforming()->create();
```

---

## 🎯 Key Features

✅ **Clean Architecture** - Service layer separates business logic
✅ **Type Safety** - Full type hints and return types
✅ **Validation** - Comprehensive form request validation
✅ **Authorization** - Policies for access control
✅ **Pagination** - All list endpoints are paginated
✅ **Search & Filter** - Advanced filtering capabilities
✅ **Soft Deletes** - Restore deleted records
✅ **Eager Loading** - Optimized database queries
✅ **Transaction Support** - ACID compliance where needed
✅ **RESTful API** - Standard HTTP methods
✅ **Error Handling** - Consistent error responses
✅ **Documentation** - Well-commented code

---

## 🔐 Security Considerations

1. **Authentication** - All endpoints require sanctum token
2. **Authorization** - Policies control access
3. **Validation** - All input is validated
4. **SQL Injection** - Parameterized queries
5. **CSRF** - Handled by Laravel
6. **Rate Limiting** - Can be added per route

---

## 📈 Performance Optimizations

- ✅ Eager loading with `->with()`
- ✅ Database indexes on foreign keys
- ✅ Pagination to limit results
- ✅ Query scopes for reusable queries
- ✅ Soft deletes for data integrity
- ✅ Service caching options

---

## 🤝 Contributing Guidelines

1. Follow Laravel conventions
2. Use type hints
3. Write tests
4. Keep services focused
5. Update documentation

---

## 📞 Support

For issues or questions:
1. Check documentation
2. Review code comments
3. Check validation rules
4. Review policies

---

**Last Updated:** May 22, 2026
**Version:** 1.0.0
**Status:** Production Ready ✅
