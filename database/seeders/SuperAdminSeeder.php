<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This is the ONLY seeder for users, roles, and permissions.
     * It creates ONE Super Admin user with ALL permissions.
     *
     * @return void
     */
    public function run()
    {
        echo "🔥 COMPLETE PERMISSION SYSTEM RESET\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        // Step 1: Clean existing users and roles (except system ones)
        $this->cleanExistingData();

        // Step 2: Ensure permissions exist
        $this->ensurePermissionsExist();

        // Step 3: Create Super Admin role with ALL permissions
        $this->createSuperAdminRole();

        // Step 4: Create the Super Admin user
        $this->createSuperAdminUser();

        echo "\n🎉 SUPER ADMIN SYSTEM READY!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ Super Admin: superadmin@admin.com\n";
        echo "✅ Password: 12345678\n";
        echo "✅ Has ALL " . Permission::count() . " permissions\n";
        echo "✅ Complete system access granted!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    }

    /**
     * Clean existing users and roles
     */
    private function cleanExistingData()
    {
        echo "🧹 Cleaning existing users and roles...\n";

        try {
            // Delete all users except the one we're about to create
            User::where('email', '!=', 'superadmin@admin.com')->delete();

            // Delete all role assignments
            DB::table('model_has_roles')->delete();
            DB::table('model_has_permissions')->delete();

            // Delete all roles except Super Admin (if it exists)
            Role::where('name', '!=', 'Super Admin')->delete();

            echo "✅ Cleaned existing data\n";
        } catch (\Exception $e) {
            echo "⚠️ Error cleaning data: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Ensure all permissions exist in the database
     */
    private function ensurePermissionsExist()
    {
        echo "🔐 Ensuring all permissions exist...\n";

        $permissions = [
            // Core System
            'Dashboard' => ['Dashboard-list'],

            // User Management
            'UserManagement' => [
                'Users-list', 'Users-create', 'Users-edit', 'Users-delete',
                'Roles-list', 'Roles-create', 'Roles-edit', 'Roles-delete',
                'Permissions-list', 'Permissions-create', 'Permissions-edit', 'Permissions-delete',
            ],

            // Academic Management
            'AcademicManagement' => [
                'PreAdmissionForm-list', 'PreAdmissionForm-create', 'PreAdmissionForm-edit', 'PreAdmissionForm-delete',
                'Students-list', 'Students-create', 'Students-edit', 'Students-delete',
                'StudentSiblingsReport-list', 'StudentClassPromotion-list',
            ],

            'AttendanceManagement' => [
                'StudentAttendance-list',
                'AttendanceReport-list', 'AttendanceReport-create', 'AttendanceReport-edit', 'AttendanceReport-delete',
            ],

            'StudentManagement' => [
                'AssignClassSection-list', 'AssignClassSection-create', 'AssignClassSection-edit', 'AssignClassSection-delete',
                'ViewStudents-list',
                'SubjectType-list', 'SubjectType-create', 'SubjectType-edit', 'SubjectType-delete',
                'ClassTimetable-list', 'ClassTimetable-create', 'ClassTimetable-edit', 'ClassTimetable-delete',
                'AssignTimetable-list', 'AssignTimetable-create', 'AssignTimetable-edit', 'AssignTimetable-delete',
                'Teachers-list', 'Teachers-create', 'Teachers-edit', 'Teachers-delete',
            ],

            // HR Management
            'Employees' => ['Employees-list', 'Employees-create', 'Employees-edit', 'Employees-delete'],
            'WorkShift' => ['WorkShift-list', 'WorkShift-create', 'WorkShift-edit', 'WorkShift-delete'],
            'Attendance' => [
                'EmployeeAttendance-list', 'EmployeeAttendance-create', 'EmployeeAttendance-edit', 'EmployeeAttendance-delete',
                'AttendanceDashboard-list', 'AttendanceReport-list',
            ],
            'Payroll' => ['Payroll-list', 'Payroll-approve', 'Payroll-slip', 'Payroll-report'],
            'Overtime' => ['Overtime-list', 'Overtime-create', 'Overtime-edit', 'Overtime-delete'],

            // Fee Management
            'Fee' => [
                'FeeSections-list', 'FeeSections-create', 'FeeSections-edit', 'FeeSections-delete',
                'AccountHead-list', 'AccountHead-create', 'AccountHead-edit', 'AccountHead-delete',
                'FeeHeads-list', 'FeeHeads-create', 'FeeHeads-edit', 'FeeHeads-delete',
                'FeeCategory-list', 'FeeCategory-create', 'FeeCategory-edit', 'FeeCategory-delete',
                'FeeStructure-list', 'FeeStructure-create', 'FeeStructure-edit', 'FeeStructure-delete',
                'FeeCollection-list', 'FeeCollection-create', 'FeeCollection-edit', 'FeeCollection-delete',
                'FeeDiscount-list', 'FeeDiscount-create', 'FeeDiscount-edit', 'FeeDiscount-delete',
                'FeeTerm-list', 'FeeTerm-create', 'FeeTerm-edit', 'FeeTerm-delete',
                'StudentRegularFee-list', 'StudentRegularFee-create', 'StudentRegularFee-edit', 'StudentRegularFee-delete',
                'BillGeneration-list', 'BillGeneration-create', 'BillGeneration-edit', 'BillGeneration-delete',
                'Banks-list', 'Banks-create', 'Banks-edit', 'Banks-delete',
                'BanksBranches-list', 'BanksBranches-create', 'BanksBranches-edit', 'BanksBranches-delete',
                'BanksAccounts-list', 'BanksAccounts-create', 'BanksAccounts-edit', 'BanksAccounts-delete',
                'BanksFile-list',
            ],

            // Exam Management
            'Exam' => [
                'ExamTerms-list', 'ExamTerms-create', 'ExamTerms-edit', 'ExamTerms-delete',
                'TestTypes-list', 'TestTypes-create', 'TestTypes-edit', 'TestTypes-delete',
                'ExamDetails-list', 'ExamDetails-create', 'ExamDetails-edit', 'ExamDetails-delete',
                'Components-list', 'Components-create', 'Components-edit', 'Components-delete',
                'SubComponents-list', 'SubComponents-create', 'SubComponents-edit', 'SubComponents-delete',
                'Skills-list', 'Skills-create', 'Skills-edit', 'Skills-delete',
                'SkillEvaluationsKey-list', 'SkillEvaluationsKey-create', 'SkillEvaluationsKey-edit', 'SkillEvaluationsKey-delete',
                'SkillEvaluation-list', 'SkillEvaluation-create', 'SkillEvaluation-edit', 'SkillEvaluation-delete',
                'Behaviours-list', 'Behaviours-create', 'Behaviours-edit', 'Behaviours-delete',
                'EffortLevels-list', 'EffortLevels-create', 'EffortLevels-edit', 'EffortLevels-delete',
                'GradingPolicies-list', 'GradingPolicies-create', 'GradingPolicies-edit', 'GradingPolicies-delete',
                'AcademicEvaluationsKey-list', 'AcademicEvaluationsKey-create', 'AcademicEvaluationsKey-edit', 'AcademicEvaluationsKey-delete',
                'SkillGroups-list', 'SkillGroups-create', 'SkillGroups-edit', 'SkillGroups-delete',
                'SkillTypes-list', 'SkillTypes-create', 'SkillTypes-edit', 'SkillTypes-delete',
                'ExamSchedules-list', 'ExamSchedules-create', 'ExamSchedules-edit', 'ExamSchedules-delete',
                'MarksInput-list', 'MarksInput-create', 'MarksInput-edit', 'MarksInput-delete',
            ],

            // Accounts Management
            'Accounts' => [
                'ChartsOfAccounts-list', 'ChartsOfAccounts-create', 'ChartsOfAccounts-edit', 'ChartsOfAccounts-delete',
                'ReportCenter-list', 'ReportCenter-create', 'ReportCenter-edit', 'ReportCenter-delete',
                'JournalEntry-list', 'JournalEntry-create', 'JournalEntry-edit', 'JournalEntry-delete',
            ],

            'AccountReports' => [
                'TrialBalance-list', 'BalanceSheet-list', 'ProfitLoss-list', 'ChartOfAccounts-list',
            ],

            'Assets' => [
                'AssetType-list', 'AssetType-create', 'AssetType-edit', 'AssetType-delete',
                'Assets-list', 'Assets-create', 'Assets-edit', 'Assets-delete',
                'AssetsBulk-list', 'AssetsBulk-create', 'AssetsBulk-edit', 'AssetsBulk-delete',
            ],

            // Administrative
            'Company' => ['Company-list', 'Company-create', 'Company-edit', 'Company-delete'],
            'Branches' => ['Branches-list', 'Branches-create', 'Branches-edit', 'Branches-delete'],
            'Category' => ['Category-list', 'Category-create', 'Category-edit', 'Category-delete'],
            'Departments' => ['Departments-list', 'Departments-create', 'Departments-edit', 'Departments-delete'],
            'Designations' => ['Designations-list', 'Designations-create', 'Designations-edit', 'Designations-delete'],
            'SignatoryAuthorities' => ['SignatoryAuthorities-list', 'SignatoryAuthorities-create'],

            // Academic Structure
            'AcademicSession' => ['AcademicSession-list', 'AcademicSession-create', 'AcademicSession-edit', 'AcademicSession-delete'],
            'SchoolType' => ['SchoolType-list', 'SchoolType-create', 'SchoolType-edit', 'SchoolType-delete'],
            'Class' => ['Class-list', 'Class-create', 'Class-edit', 'Class-delete'],
            'ActiveSessions' => ['ActiveSessions-list', 'ActiveSessions-create', 'ActiveSessions-edit', 'ActiveSessions-delete'],
            'Section' => ['Section-list', 'Section-create', 'Section-edit', 'Section-delete'],
            'Subjects' => ['Subjects-list', 'Subjects-create', 'Subjects-edit', 'Subjects-delete'],

            // HR Reports & Funds
            'Reports' => ['OvertimeReport-list', 'EOBIReport-list', 'PFReport-list', 'SSReport-list'],
            'EmployeeWelfare' => ['EmployeeWelfare-list'],
            'EOBI' => ['EOBI-list'],
            'ProfitFunds' => ['ProfitFunds-list', 'ProfitFunds-create', 'ProfitFunds-edit', 'ProfitFunds-delete'],
            'SocialSecurity' => ['SocialSecurity-list', 'SocialSecurity-create', 'SocialSecurity-edit', 'SocialSecurity-delete'],
            'SalaryTaxLab' => ['SalaryTaxLab-list', 'SalaryTaxLab-create', 'SalaryTaxLab-edit', 'SalaryTaxLab-delete'],

            // Inventory Management
            'Budget' => ['Budget-create', 'Budget-edit', 'Budget-delete', 'Budget-list'],
            'InventoryCategory' => ['InventoryCategory-create', 'InventoryCategory-edit', 'InventoryCategory-delete', 'InventoryCategory-list'],
            'Vendor' => ['Vendor-create', 'Vendor-edit', 'Vendor-delete', 'Vendor-list'],
            'VendorCategory' => ['VendorCategory-create', 'VendorCategory-edit', 'VendorCategory-delete', 'VendorCategory-list'],

            'CafeInventory' => [
                'RawMaterialItems-create', 'RawMaterialItems-edit', 'RawMaterialItems-delete', 'RawMaterialItems-list',
                'Supplier-create', 'Supplier-edit', 'Supplier-delete', 'Supplier-list',
                'Requisitions-create', 'Requisitions-edit', 'Requisitions-delete', 'Requisitions-list',
                'RequisitionApproval-create', 'RequisitionApproval-edit', 'RequisitionApproval-delete', 'RequisitionApproval-list',
                'Quotes-create', 'Quotes-edit', 'Quotes-delete', 'Quotes-list',
                'PurchaseOrders-create', 'PurchaseOrders-edit', 'PurchaseOrders-delete', 'PurchaseOrders-list',
                'GRN-list', 'CafeInventory-list',
                'Products-create', 'Products-edit', 'Products-delete', 'Products-list',
                'CompletedProducts-list',
                'StudentMeal-list', 'StudentMealAssigned-list',
                'StaffMeal-list', 'StaffMealAssigned-list',
            ],

            'StationeryInventory' => [
                'Items-create', 'Items-edit', 'Items-delete', 'Items-list',
                'Suppliers-create', 'Suppliers-edit', 'Suppliers-delete', 'Suppliers-list',
                'StoreInventory-list', 'Bundles-list',
            ],

            'POS' => [
                'POSFood-list', 'POSFood-create', 'POSFood-edit', 'POSFood-delete',
                'POSUniform-list', 'POSUniform-create', 'POSUniform-edit', 'POSUniform-delete',
            ],

            // Special Permissions
            'students' => ['students', 'students-list', 'students-create', 'students-edit', 'students-delete'],
            'pos' => ['inventory-pos-view'],
            'type' => ['manage types', 'create types', 'edit types', 'delete types'],
            'BudgetCategory' => ['BudgetCategory-list', 'BudgetCategory-create', 'BudgetCategory-edit', 'BudgetCategory-delete'],
            'maintainer' => ['manage maintainer', 'create maintainer', 'edit maintainer', 'delete maintainer'],
            'supplementory' => ['supplementory create', 'supplementory edit', 'supplementory list', 'supplementory request'],
            'expense' => ['expense create', 'expense edit', 'expense list', 'expense delete'],
            'maintenance-request' => ['maintenance-request create', 'maintenance-request edit', 'maintenance-request list', 'maintenance-request delete'],
        ];

        $createdCount = 0;
        $existingCount = 0;

        foreach ($permissions as $group => $subs) {
            // Create or update the "group" permission (main = 1)
            $main = Permission::updateOrCreate(
                ['name' => $group, 'guard_name' => 'web'],
                ['main' => 1, 'parent_id' => 0]
            );

            if ($main->wasRecentlyCreated) {
                $createdCount++;
            } else {
                $existingCount++;
            }

            // Create or update sub-permissions
            foreach ($subs as $perm) {
                $sub = Permission::updateOrCreate(
                    ['name' => $perm, 'guard_name' => 'web'],
                    ['main' => 0, 'parent_id' => $main->id]
                );

                if ($sub->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $existingCount++;
                }
            }
        }

        echo "✅ Permissions: {$createdCount} created, {$existingCount} existing\n";
    }

    /**
     * Create Super Admin role with ALL permissions
     */
    private function createSuperAdminRole()
    {
        echo "👑 Creating Super Admin role...\n";

        // Create Super Admin role
        $role = Role::updateOrCreate(['name' => 'Super Admin']);

        // Get all permissions
        $allPermissions = Permission::all();

        if ($allPermissions->count() > 0) {
            // Assign ALL permissions to Super Admin role
            $role->syncPermissions($allPermissions);

            echo "✅ Super Admin role has " . $allPermissions->count() . " permissions\n";
        } else {
            echo "⚠️ No permissions found to assign to Super Admin role\n";
        }
    }

    /**
     * Create the Super Admin user
     */
    private function createSuperAdminUser()
    {
        echo "👤 Creating Super Admin user...\n";

        // Handle existing Super Admin user with foreign key constraints
        // Use raw queries to bypass foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Delete user and all related records
            DB::table('users')->where('email', 'superadmin@admin.com')->delete();
            DB::table('model_has_roles')->where('model_type', User::class)->delete();
            DB::table('model_has_permissions')->where('model_type', User::class)->delete();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Create the Super Admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'active' => 1,
        ]);

        // Assign Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $user->assignRole($superAdminRole);
            echo "✅ Super Admin user created and assigned role\n";
        } else {
            echo "⚠️ Super Admin role not found, user created without role\n";
        }
    }
}
