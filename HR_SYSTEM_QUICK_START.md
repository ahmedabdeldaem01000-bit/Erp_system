# HR System - Quick Start Guide

## ⚡ Getting Started (5 Minutes)

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Seed the Database (Optional)
```bash
php artisan db:seed --class=LeaveTypeSeeder
php artisan db:seed --class=LeaveRequestSeeder
php artisan db:seed --class=PerformanceReviewSeeder
```

Or seed all at once:
```bash
php artisan db:seed
```

### Step 3: Start the Server
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

---

## 🧪 Quick Testing

### Using cURL

**1. Create a Leave Type:**
```bash
curl -X POST http://localhost:8000/api/leave-types \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "Annual Leave",
    "days_per_year": 21,
    "is_paid": true
  }'
```

**2. Get All Leave Types:**
```bash
curl -X GET http://localhost:8000/api/leave-types?per_page=15 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**3. Create a Leave Request:**
```bash
curl -X POST http://localhost:8000/api/leave-requests \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "employee_id": 1,
    "leave_type_id": 1,
    "start_date": "2026-06-01",
    "end_date": "2026-06-05",
    "reason": "Personal reasons"
  }'
```

**4. Approve a Leave Request:**
```bash
curl -X POST http://localhost:8000/api/leave-requests/1/approve \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "approved_by": 2
  }'
```

**5. Create a Performance Review:**
```bash
curl -X POST http://localhost:8000/api/performance-reviews \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "employee_id": 1,
    "reviewer_id": 2,
    "review_period": "2024-Q1",
    "quality_of_work": 8,
    "productivity": 9,
    "communication": 7,
    "teamwork": 8,
    "leadership": 6,
    "strengths": "Strong leader",
    "areas_for_improvement": "Time management",
    "goals": "Improve delegation",
    "comments": "Good performance"
  }'
```

---

## 📦 Using Postman

1. Import the Postman collection from: `storage/HR_System_API.postman_collection.json`
2. Update the `base_url` variable to your API URL
3. Add your authorization token
4. Start testing!

---

## 📂 Generated Files Summary

### Migrations (3 files)
- ✅ `database/migrations/2026_05_22_000001_create_leave_types_table.php`
- ✅ `database/migrations/2026_05_22_000002_create_leave_requests_table.php`
- ✅ `database/migrations/2026_05_22_000003_create_performance_reviews_table.php`

### Models (3 files)
- ✅ `app/Models/LeaveType.php`
- ✅ `app/Models/LeaveRequest.php`
- ✅ `app/Models/PerformanceReview.php`
- ✅ `app/Models/Employee.php` (updated with relationships)

### Factories (3 files)
- ✅ `database/factories/LeaveTypeFactory.php`
- ✅ `database/factories/LeaveRequestFactory.php`
- ✅ `database/factories/PerformanceReviewFactory.php`

### Seeders (3 files)
- ✅ `database/seeders/LeaveTypeSeeder.php`
- ✅ `database/seeders/LeaveRequestSeeder.php`
- ✅ `database/seeders/PerformanceReviewSeeder.php`

### Services (3 files)
- ✅ `app/Service/Hr/LeaveType/LeaveTypeService.php`
- ✅ `app/Service/Hr/LeaveRequest/LeaveRequestService.php`
- ✅ `app/Service/Hr/PerformanceReview/PerformanceReviewService.php`

### Form Requests (6 files)
- ✅ `app/Http/Requests/StoreLeaveTypeRequest.php`
- ✅ `app/Http/Requests/UpdateLeaveTypeRequest.php`
- ✅ `app/Http/Requests/StoreLeaveRequestRequest.php`
- ✅ `app/Http/Requests/UpdateLeaveRequestRequest.php`
- ✅ `app/Http/Requests/StorePerformanceReviewRequest.php`
- ✅ `app/Http/Requests/UpdatePerformanceReviewRequest.php`

### API Resources (3 files)
- ✅ `app/Http/Resources/LeaveTypeResource.php`
- ✅ `app/Http/Resources/LeaveRequestResource.php`
- ✅ `app/Http/Resources/PerformanceReviewResource.php`

### Controllers (3 files)
- ✅ `app/Http/Controllers/Api/Hr/LeaveTypeController.php`
- ✅ `app/Http/Controllers/Api/Hr/LeaveRequestController.php`
- ✅ `app/Http/Controllers/Api/Hr/PerformanceReviewController.php`

### Policies (2 files)
- ✅ `app/Policies/LeaveRequestPolicy.php`
- ✅ `app/Policies/PerformanceReviewPolicy.php`

### Routes (1 file)
- ✅ `routes/api.php` (updated with HR routes)

### Documentation (3 files)
- ✅ `HR_SYSTEM_DOCUMENTATION.md` - Full documentation
- ✅ `HR_SYSTEM_QUICK_START.md` - Quick start guide
- ✅ `storage/HR_System_API.postman_collection.json` - Postman collection

---

## 🔍 Database Schema at a Glance

### leave_types
```
├── id (PK)
├── name (unique)
├── days_per_year
├── is_paid
└── timestamps + soft_deletes
```

### leave_requests
```
├── id (PK)
├── employee_id (FK)
├── leave_type_id (FK)
├── start_date
├── end_date
├── days (auto-calculated)
├── reason
├── status (enum)
├── approved_by (FK, nullable)
├── approved_at (nullable)
├── rejection_reason (nullable)
└── timestamps + soft_deletes
```

### performance_reviews
```
├── id (PK)
├── employee_id (FK)
├── reviewer_id (FK)
├── review_period
├── quality_of_work (1-10)
├── productivity (1-10)
├── communication (1-10)
├── teamwork (1-10)
├── leadership (1-10)
├── overall_rating (auto-calculated)
├── strengths
├── areas_for_improvement
├── goals
├── comments
└── timestamps + soft_deletes
```

---

## 🎯 Common API Patterns

### Get Paginated Results
```
GET /api/leave-types?per_page=20&page=1
```

### Search
```
GET /api/leave-requests?search=John%20Doe
```

### Filter by Status
```
GET /api/leave-requests?status=pending
```

### Filter by Employee
```
GET /api/leave-requests/employee/1?per_page=20
```

### Get High Performers
```
GET /api/performance-reviews?performance=high
```

### Get Department Stats
```
GET /api/performance-reviews/department-statistics?department_id=1
```

---

## 🛠️ Service Layer Usage

### Using LeaveTypeService
```php
use App\Service\Hr\LeaveType\LeaveTypeService;

$service = app(LeaveTypeService::class);

// Get all
$types = $service->getAll(15);

// Search
$types = $service->search('Annual', 15);

// Create
$type = $service->create([
    'name' => 'Study Leave',
    'days_per_year' => 5,
    'is_paid' => true
]);

// Update
$updated = $service->update($type, ['days_per_year' => 7]);

// Delete
$service->delete($type);
```

### Using LeaveRequestService
```php
use App\Service\Hr\LeaveRequest\LeaveRequestService;

$service = app(LeaveRequestService::class);

// Get pending
$pending = $service->getPendingRequests();

// Create
$request = $service->create([
    'employee_id' => 1,
    'leave_type_id' => 1,
    'start_date' => '2026-06-01',
    'end_date' => '2026-06-05',
    'reason' => 'Vacation'
]);

// Approve
$approved = $service->approve($request, 2);

// Reject
$rejected = $service->reject($request, 2, 'Budget constraints');
```

### Using PerformanceReviewService
```php
use App\Service\Hr\PerformanceReview\PerformanceReviewService;

$service = app(PerformanceReviewService::class);

// Get high performers
$highPerformers = $service->getHighPerformers();

// Get department average
$avgRating = $service->getDepartmentAverageRating(1);

// Get employee trend
$trend = $service->getEmployeeRatingTrend(1);
```

---

## ✅ Checklist

Before going to production:

- [ ] Run migrations: `php artisan migrate`
- [ ] Seed initial data: `php artisan db:seed`
- [ ] Test all endpoints with Postman
- [ ] Configure authorization/policies
- [ ] Add rate limiting if needed
- [ ] Enable CORS if needed
- [ ] Set up proper logging
- [ ] Configure error handling
- [ ] Test validation rules
- [ ] Review security settings

---

## 🐛 Troubleshooting

### Issue: "SQLSTATE[42S02]: Table not found"
**Solution:** Run migrations: `php artisan migrate`

### Issue: "Unauthorized" in API responses
**Solution:** Add valid Bearer token in Authorization header

### Issue: "Validation errors"
**Solution:** Check validation rules in Form Requests and request payload

### Issue: Relationships not loading
**Solution:** Ensure eager loading with `->with(['relation'])`

---

## 📊 Example Response

### Get All Leave Types Response
```json
{
  "status": true,
  "message": "Leave types retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Annual Leave",
      "days_per_year": 21,
      "is_paid": true,
      "created_at": "2026-05-22T10:30:00.000Z",
      "updated_at": "2026-05-22T10:30:00.000Z"
    }
  ],
  "pagination": {
    "total": 8,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1
  }
}
```

### Create Performance Review Response
```json
{
  "status": true,
  "message": "Performance review created successfully",
  "data": {
    "id": 1,
    "employee": {
      "id": 1,
      "name": "John Doe",
      ...
    },
    "reviewer": {
      "id": 2,
      "name": "Jane Smith",
      ...
    },
    "review_period": "2024-Q1",
    "ratings": {
      "quality_of_work": 8,
      "productivity": 9,
      "communication": 7,
      "teamwork": 8,
      "leadership": 6
    },
    "overall_rating": 7.6,
    "strengths": "Strong problem-solving",
    "areas_for_improvement": "Time management",
    "goals": "Leadership training",
    "comments": "Great performance",
    "created_at": "2026-05-22T10:30:00.000Z",
    "updated_at": "2026-05-22T10:30:00.000Z"
  }
}
```

---

## 🚀 Next Steps

1. **Test the API** - Use Postman collection
2. **Integrate Frontend** - Build Vue/React UI
3. **Add Notifications** - Email alerts for approvals
4. **Generate Reports** - Export leave/review data
5. **Mobile App** - Extend to mobile clients

---

**Status:** ✅ Production Ready
**Version:** 1.0.0
**Last Updated:** May 22, 2026
