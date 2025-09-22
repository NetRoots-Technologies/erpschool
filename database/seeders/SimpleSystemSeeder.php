<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;

class SimpleSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Company
        $company = Company::create([
            'name' => 'ERP School System',
            'status' => 1,
        ]);

        // Create Branch
        $branch = Branch::create([
            'name' => 'Main Campus',
            'company_id' => $company->id,
            'status' => 1,
        ]);

        // Create Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@erpschool.com',
            'password' => Hash::make('password'),
            'status' => 1,
            'email_verified_at' => now(),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
        ]);

        // Create Permissions
        $permissions = [
            'dashboard_access',
            'fee_management_access',
            'fee_categories_manage',
            'fee_structures_manage',
            'fee_collections_manage',
            'fee_discounts_manage',
            'fee_billing_manage',
            'fee_reports_access',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Admin Role
        $adminRole = Role::create(['name' => 'Super Admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Assign Role to Admin
        $admin->assignRole($adminRole);

        $this->command->info('✅ Simple system seeded successfully!');
        $this->command->info('👤 Admin User: admin@erpschool.com');
        $this->command->info('🔑 Password: password');
    }
}
