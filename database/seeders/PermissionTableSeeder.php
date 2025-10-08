<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

                // Main
            'Dashboard' => ['Dashboard-list'],

            // Academic
            'Company' => ['Company-list', 'Company-create', 'Company-edit', 'Company-delete'],
            'Branches' => ['Branches-list', 'Branches-create', 'Branches-edit', 'Branches-delete'],
            'Category' => ['Category-list', 'Category-create', 'Category-edit', 'Category-delete'],
            'Departments' => ['Departments-list', 'Departments-create', 'Departments-edit', 'Departments-delete'],
            'FinancialYears' => ['FinancialYears-list', 'FinancialYears-create', 'FinancialYears-edit', 'FinancialYears-delete'],
            'Designations' => ['Designations-list', 'Designations-create', 'Designations-edit', 'Designations-delete'],
            'SignatoryAuthorities' => ['SignatoryAuthorities-list', 'SignatoryAuthorities-create'],

            // Academic Management
            'AcademicSession' => ['AcademicSession-list', 'AcademicSession-create', 'AcademicSession-edit', 'AcademicSession-delete'],
            'SchoolType' => ['SchoolType-list', 'SchoolType-create', 'SchoolType-edit', 'SchoolType-delete'],
            'Class' => ['Class-list', 'Class-create', 'Class-edit', 'Class-delete'],
            'ActiveSessions' => ['ActiveSessions-list', 'ActiveSessions-create', 'ActiveSessions-edit', 'ActiveSessions-delete'],
            'Section' => ['Section-list', 'Section-create', 'Section-edit', 'Section-delete'],
            'Subjects' => ['Subjects-list', 'Subjects-create', 'Subjects-edit', 'Subjects-delete'],

            'AcademicManagement' => [
                'PreAdmissionForm-list', 'PreAdmissionForm-create', 'PreAdmissionForm-edit', 'PreAdmissionForm-delete',
                'Students-list', 'Students-create', 'Students-edit', 'Students-delete',
                'StudentSiblingsReport-list',
                'StudentClassPromotion-list',
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
                'AttendanceDashboard-list',
                'AttendanceReport-list',
            ],

            'Payroll' => [
                'Payroll-list',
                'Payroll-approve',
                'Payroll-slip',
                'Payroll-report',
            ],

            'SalaryTaxLab' => ['SalaryTaxLab-list', 'SalaryTaxLab-create', 'SalaryTaxLab-edit', 'SalaryTaxLab-delete'],
            'Overtime' => ['Overtime-list', 'Overtime-create', 'Overtime-edit', 'Overtime-delete'],

            // HR Reports
            'Reports' => [
                'OvertimeReport-list',
                'EOBIReport-list',
                'PFReport-list',
                'SSReport-list',
            ],

            // Fee
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

            // // Exam
            // 'Exam' => [
            //     'ExamTerms-list', 'ExamTerms-create', 'ExamTerms-edit', 'ExamTerms-delete',
            //     'TestTypes-list', 'TestTypes-create', 'TestTypes-edit', 'TestTypes-delete',
            //     'ExamDetails-list', 'ExamDetails-create', 'ExamDetails-edit', 'ExamDetails-delete',
            //     'Components-list', 'Components-create', 'Components-edit', 'Components-delete',
            //     'SubComponents-list', 'SubComponents-create', 'SubComponents-edit', 'SubComponents-delete',
            //     'SubComponent-list', 'SubComponent-create', 'SubComponent-edit', 'SubComponent-delete',
            //     'ClassSubjects-list', 'ClassSubjects-create', 'ClassSubjects-edit', 'ClassSubjects-delete',
            //     'SkillEvaluationsKey-list', 'SkillEvaluationsKey-create', 'SkillEvaluationsKey-edit', 'SkillEvaluationsKey-delete',
            //     'AcademicEvaluationsKey-list', 'AcademicEvaluationsKey-create', 'AcademicEvaluationsKey-edit', 'AcademicEvaluationsKey-delete',
            //     'SkillGroups-list', 'SkillGroups-create', 'SkillGroups-edit', 'SkillGroups-delete',
            //     'SkillTypes-list', 'SkillTypes-create', 'SkillTypes-edit', 'SkillTypes-delete',
            //     'ExamSchedules-list', 'ExamSchedules-create', 'ExamSchedules-edit', 'ExamSchedules-delete',
            //     'MarksInput-list', 'MarksInput-create', 'MarksInput-edit', 'MarksInput-delete',
            // ],

              // Exam Permissions
            'Exam' => [
                'ExamTerms-list',
                'ExamTerms-create',
                'ExamTerms-edit',
                'ExamTerms-delete',
                'TestTypes-list',
                'TestTypes-create',
                'TestTypes-edit',
                'TestTypes-delete',
                'ExamDetails-list',
                'ExamDetails-create',
                'ExamDetails-edit',
                'ExamDetails-delete',
                'Components-list',
                'Components-create',
                'Components-edit',
                'Components-delete',
                'SubComponents-list',
                'SubComponents-create',
                'SubComponents-edit',
                'SubComponents-delete',
                'Skills-list',
                'Skills-create',
                'Skills-edit',
                'Skills-delete',
                'SkillEvaluationsKey-list',
                'SkillEvaluationsKey-create',
                'SkillEvaluationsKey-edit',
                'SkillEvaluationsKey-delete',
                'SkillEvaluation-list',
                'SkillEvaluation-create',
                'SkillEvaluation-edit',
                'SkillEvaluation-delete',
                'Behaviours-list',
                'Behaviours-create',
                'Behaviours-edit',
                'Behaviours-delete',
                'EffortLevels-list',
                'EffortLevels-create',
                'EffortLevels-edit',
                'EffortLevels-delete',
                'GradingPolicies-list',
                'GradingPolicies-create',
                'GradingPolicies-edit',
                'GradingPolicies-delete',
                'AcademicEvaluationsKey-list',
                'AcademicEvaluationsKey-create',
                'AcademicEvaluationsKey-edit',
                'AcademicEvaluationsKey-delete',
                'SkillGroups-list',
                'SkillGroups-create',
                'SkillGroups-edit',
                'SkillGroups-delete',
                'SkillTypes-list',
                'SkillTypes-create',
                'SkillTypes-edit',
                'SkillTypes-delete',
                'ExamSchedules-list',
                'ExamSchedules-create',
                'ExamSchedules-edit',
                'ExamSchedules-delete',
                'MarksInput-list',
                'MarksInput-create',
                'MarksInput-edit',
                'MarksInput-delete',
            ],

            // Accounts Management
            'Accounts' => [
                'ChartsOfAccounts-list', 'ChartsOfAccounts-create', 'ChartsOfAccounts-edit', 'ChartsOfAccounts-delete',
                'ReportCenter-list', 'ReportCenter-create', 'ReportCenter-edit', 'ReportCenter-delete',
                'JournalEntry-list', 'JournalEntry-create', 'JournalEntry-edit', 'JournalEntry-delete',
            ],

            'AccountReports' => [
                'TrialBalance-list',
                'BalanceSheet-list',
                'ProfitLoss-list',
                'ChartOfAccounts-list',
            ],

            'Assets' => [
                'AssetType-list', 'AssetType-create', 'AssetType-edit', 'AssetType-delete',
                'Assets-list', 'Assets-create', 'Assets-edit', 'Assets-delete',
                'AssetsBulk-list', 'AssetsBulk-create', 'AssetsBulk-edit', 'AssetsBulk-delete',
            ],

            // Funds
            'EmployeeWelfare' => ['EmployeeWelfare-list'],
            'EOBI' => ['EOBI-list'],
            'ProfitFunds' => ['ProfitFunds-list', 'ProfitFunds-create', 'ProfitFunds-edit', 'ProfitFunds-delete'],
            'SocialSecurity' => ['SocialSecurity-list', 'SocialSecurity-create', 'SocialSecurity-edit', 'SocialSecurity-delete'],

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
                'GRN-list',
                'CafeInventory-list',
                'Products-create', 'Products-edit', 'Products-delete', 'Products-list',
                'CompletedProducts-list',
                'StudentMeal-list',
                'StudentMealAssigned-list',
                'StaffMeal-list',
                'StaffMealAssigned-list',
            ],

            'StationeryInventory' => [
                'Items-create', 'Items-edit', 'Items-delete', 'Items-list',
                'Suppliers-create', 'Suppliers-edit', 'Suppliers-delete', 'Suppliers-list',
                'Requisitions-create', 'Requisitions-edit', 'Requisitions-delete', 'Requisitions-list',
                'RequisitionApproval-list',
                'Quotes-create', 'Quotes-edit', 'Quotes-delete', 'Quotes-list',
                'PurchaseOrders-create', 'PurchaseOrders-edit', 'PurchaseOrders-delete', 'PurchaseOrders-list',
                'GRN-list',
                'StoreInventory-list',
                'Bundles-list',
            ],

            'POS' => [
                'POSFood-list', 'POSFood-create', 'POSFood-edit', 'POSFood-delete',
                'POSUniform-list', 'POSUniform-create', 'POSUniform-edit', 'POSUniform-delete',
            ],

            // User Management
                'UserManagement' => [
                    'Users-list', 'Users-create', 'Users-edit', 'Users-delete',
                    'Roles-list', 'Roles-create', 'Roles-edit', 'Roles-delete',
                    'Permissions-list', 'Permissions-create', 'Permissions-edit', 'Permissions-delete',
            ],
                'students'=> ['students','students-list', 'students-create', 'students-edit', 'students-delete'],
                    'pos' => [
                        'inventory-pos-view',
                    ],

                    'type' => [
                        'manage types' , 'create types' , 'edit types' , 'delete types',
                    ],
                    'BudgetCategory' => ['BudgetCategory-list', 'BudgetCategory-create', 'BudgetCategory-edit', 'BudgetCategory-delete'],
                    'maintainer' => ['manage maintainer' , 'create maintainer' , 'edit maintainer' , 'delete maintainer'],
                    'supplementory' => ['supplementory create' , 'supplementory edit' , 'supplementory list' , 'supplementory list' , 'supplementory request'],
                    'expense' => ['expense create' , 'expense edit' , 'expense list' , 'expense delete' ],
                    'maintenance-request' => ['maintenance-request create', 'maintenance-request edit' , 'maintenance-request list' , 'maintenance-request delete' , '' ]
                ];

        DB::transaction(function () use ($permissions) {
            foreach ($permissions as $group => $subs) {
                // Create or update the "group" permission (main = 1)
                $main = Permission::updateOrCreate(
                    ['name' => $group, 'guard_name' => 'web'], // match only unique fields
                    ['main' => 1, 'parent_id' => 0]
                );

                // De-duplicate sub-permissions within the array (safety)
                $subs = array_values(array_unique(array_filter($subs)));

                foreach ($subs as $perm) {
                    // Create or update each sub-permission (main = 0, parent points to group)
                    Permission::updateOrCreate(
                        ['name' => $perm, 'guard_name' => 'web'],
                        ['main' => 0, 'parent_id' => $main->id]
                    );
                }
            }
        });

    }
}
