<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create(); serves
        $role = Role::create(['name' => 'admin']);
        //  $role1 = Role::create(['name' => 'writer']);
        // $role1->givePermissionTo('edit articles');
        // $role1->givePermissionTo('delete articles');
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PositionSeeder::class,
            DepartmentSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            EmployeeSeeder::class,
            AttendanceSeeder::class,        
            PayrollSeeder::class,
            SupplierSeeder::class,
            PurchasesSeeder::class,
            PurchaseItemSeeder::class,
            OrderSeeder::class,
            AccountsSeeder::class,
            OrderItemSeeder::class,

        ]);
        $employee = Employee::create([
            'first_name' => 'ahmed',
            'last_name' => 'ahmed',
            'salary' => 4000,
            'address' => "text",
            'image' => "s",
            'phone' => "01155998574",
            'gender' => "male",
            'hire_date' => '2026-01-01',
            'email' => "ahmed@email.com",
            'password' => bcrypt("password"),
            'department_id' => 1,
            'position_id' => 1,
        ]);
        Employee::create([
            'first_name' => 'nora',
            'last_name' => 'nora',
            'salary' => 4000,
            'address' => "text",
            'image' => "s",
            'phone' => "01055998574",
            'gender' => "female",
            'hire_date' => '2026-01-01',
            'email' => "nora@email.com",
            'password' => bcrypt("password"),
            'department_id' => 1,
            'position_id' => 1,
        ]);


        $employee->assignRole($role);




        // 1. إنشاء الـ Role للـ guard اللي اسمه api
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'api']
        );

        // 2. إنشاء شوية صلاحيات تجريبية
        $permissions = ['create', 'delete', 'edit', 'view'];
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'api']
            );
        }

        // 3. ربط الصلاحيات بالـ Role
        $adminRole->syncPermissions($permissions);

        // 4. تعيين الـ Role لأول موظف عندك (للتجربة)
        $employee1 = \App\Models\Employee::first();
        if ($employee1) {
            $employee1->syncRoles(['admin']);
        }

    }


}
