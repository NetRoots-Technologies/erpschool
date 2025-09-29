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
use App\Models\Admin\Department;
use App\Models\Admin\Session;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Student\Students;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeFactor;
use App\Models\Fee\FeeStructure;
use App\Models\Fee\FeeStructureDetail;
use App\Models\Fee\StudentFeeAssignment;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
use App\Models\Fee\FeeDiscount;
use App\Models\Fee\FeeAdjustment;
use App\Models\Fee\FeeAllocation;
use App\Models\Fee\FeeBilling;
use App\Models\Category;
use App\Models\Currency;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Schema;
\DB::table('categories')->truncate();


class CompleteSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Create Categories first (required for departments)
        $categories = [
            ['name' => 'Administration'],
            ['name' => 'Academics'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        // Create Currency
        Currency::firstOrCreate(
            ['code' => 'PKR'],
            [
                'name' => 'Pakistani Rupee',
                'code' => 'PKR',
                'decimal' => '2',
                'decimal_fixed_point' => '2',
                'symbols' => 'Rs.',
                'rate' => '1.00',
                'status' => '1',
                'is_default' => '1',
            ]
        );

        // Create Company
        $company = Company::firstOrCreate(
            ['name' => 'ERP School System'],
            [
                'name' => 'ERP School System',
                'status' => 1,
            ]
        );

        // Create Branches
        $ptchsBranch = Branch::firstOrCreate(
            ['name' => 'PTCHS Campus'],
            [
                'company_id' => $company->id,
                'name' => 'PTCHS Campus',
                'branch_code' => 'PTCHS',
                'address' => 'Lahore, Pakistan',
                'status' => 1,
            ]
        );

        $globalBranch = Branch::firstOrCreate(
            ['name' => 'Global Campus'],
            [
                'company_id' => $company->id,
                'name' => 'Global Campus',
                'branch_code' => 'GLOBAL',
                'address' => 'Lahore, Pakistan',
                'status' => 1,
            ]
        );

        

        // Create All Permissions from PermissionTableSeeder
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

            // Academic Management
            'AcademicSession' => ['AcademicSession-list', 'AcademicSession-create', 'AcademicSession-edit', 'AcademicSession-delete'],
            'SchoolType' => ['SchoolType-list', 'SchoolType-create', 'SchoolType-edit', 'SchoolType-delete'],
            'Class' => ['Class-list', 'Class-create', 'Class-edit', 'Class-delete'],
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

            // Exam
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

            // User Management
            'UserManagement' => [
                'Users-list', 'Users-create', 'Users-edit', 'Users-delete',
                'Roles-list', 'Roles-create', 'Roles-edit', 'Roles-delete',
                'Permissions-list', 'Permissions-create', 'Permissions-edit', 'Permissions-delete',
            ],
        ];

        foreach ($permissions as $mainPermission => $subPermissions) {
            // Create or get the main permission (check if columns exist)
            $mainData = [
                'name' => $mainPermission,
                'guard_name' => 'web',
            ];
            
            // Check if permissions table has 'main' and 'parent_id' columns
            $hasMainColumn = \Schema::hasColumn('permissions', 'main');
            $hasParentColumn = \Schema::hasColumn('permissions', 'parent_id');
            
            if ($hasMainColumn) {
                $mainData['main'] = 1;
            }
            if ($hasParentColumn) {
                $mainData['parent_id'] = 0;
            }
            
            $main = Permission::firstOrCreate($mainData);

            foreach ($subPermissions as $subPermission) {
                // Check if the sub-permission already exists
                $existingPermission = Permission::where([
                    'name' => $subPermission,
                    'guard_name' => 'web',
                ])->first();

                if (!$existingPermission) {
                    // Create the sub-permission only if it doesn't exist
                    $subData = [
                        'name' => $subPermission,
                        'guard_name' => 'web',
                    ];
                    
                    if ($hasMainColumn) {
                        $subData['main'] = 0;
                    }
                    if ($hasParentColumn) {
                        $subData['parent_id'] = $main->id;
                    }
                    
                    Permission::create($subData);
                } else {
                    // Optionally update the parent_id if needed and column exists
                    if ($hasParentColumn && isset($existingPermission->parent_id) && $existingPermission->parent_id != $main->id) {
                        $existingPermission->update(['parent_id' => $main->id]);
                    }
                }
            }
        }

        // Create Admin Role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        
        // Get all created permissions and assign to admin role
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'status' => 1,
                'active' => 1,
                'email_verified_at' => now(),
                'role_id' => json_encode([$adminRole->id]),
                'branch_id' => null,
                'company_id' => $company->id,
            ]
        );

        // Assign Role to Admin and sync all permissions directly
        $admin->assignRole($adminRole);
        $admin->syncPermissions($allPermissions);
        
        // Also give admin super admin permissions directly
        $admin->givePermissionTo($allPermissions);

        // Create Academic Session
        $session = Session::firstOrCreate(
            ['title' => '2024-25'],
            [
                'course_id' => '1',
                'title' => '2024-25',
                'start_date' => '2024-04-01',
                'end_date' => '2025-03-31',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'status' => 1,
            ]
        );

        // Create School Types (required for classes)
        // DB::table('school_types')->insertOrIgnore([
        //     ['id' => 1, 'name' => 'Primary School', 'created_at' => now(), 'updated_at' => now()],
        //     ['id' => 2, 'name' => 'Middle School', 'created_at' => now(), 'updated_at' => now()],
        //     ['id' => 3, 'name' => 'High School', 'created_at' => now(), 'updated_at' => now()],
        // ]);

        // Create Academic Classes
        // $classes = [
        //     ['name' => 'Class 1', 'school_type_id' => 1],
        //     ['name' => 'Class 2', 'school_type_id' => 1],
        //     ['name' => 'Class 3', 'school_type_id' => 1],
        //     ['name' => 'Class 4', 'school_type_id' => 1],
        //     ['name' => 'Class 5', 'school_type_id' => 2],
        //     ['name' => 'Class 6', 'school_type_id' => 2],
        //     ['name' => 'Class 7', 'school_type_id' => 2],
        //     ['name' => 'Class 8', 'school_type_id' => 2],
        //     ['name' => 'Class 9', 'school_type_id' => 3],
        //     ['name' => 'Class 10', 'school_type_id' => 3],
        // ];

        // $createdClasses = [];
        // foreach ($classes as $classData) {
        //     $createdClasses[] = AcademicClass::firstOrCreate(
        //         ['name' => $classData['name']],
        //         [
        //             'name' => $classData['name'],
        //             'school_type_id' => $classData['school_type_id'],
        //             'branch_id' => $ptchsBranch->id,
        //             'session_id' => $session->id,
        //             'company_id' => $company->id,
        //             'status' => 1,
        //         ]
        //     );
        // }

        // Create Active Sessions (required for sections)
        // DB::table('active_sessions')->insertOrIgnore([
        //     [
        //         'id' => 1, 
        //         'session_id' => $session->id,
        //         'company_id' => $company->id,
        //         'branch_id' => $ptchsBranch->id,
        //         'class_id' => 1,
        //         'status' => 1,
        //         'created_at' => now(), 
        //         'updated_at' => now()
        //     ],
        // ]);

        // Create Sections
        // $sections = ['A', 'B', 'C'];
        // $createdSections = [];
        // foreach ($createdClasses as $class) {
        //     foreach ($sections as $sectionName) {
        //         $createdSections[] = Section::firstOrCreate(
        //             ['name' => $sectionName, 'class_id' => $class->id],
        //             [
        //                 'name' => $sectionName,
        //                 'class_id' => $class->id,
        //                 'session_id' => $session->id,
        //                 'branch_id' => $ptchsBranch->id,
        //                 'company_id' => $company->id,
        //                 'active_session_id' => 1,
        //                 'status' => 1,
        //             ]
        //         );
        //     }
        // }

        // Create Fee Categories
        $feeCategoriesData = [
            ['name' => 'Admission Fee', 'type' => 'admission', 'is_mandatory' => 1, 'affects_financials' => 1],
            ['name' => 'Registration Fee', 'type' => 'admission', 'is_mandatory' => 1, 'affects_financials' => 1],
            ['name' => 'Security Deposit', 'type' => 'admission', 'is_mandatory' => 1, 'affects_financials' => 0],
            ['name' => 'Office Charges', 'type' => 'admission', 'is_mandatory' => 1, 'affects_financials' => 1],
            ['name' => 'Books & Stationery', 'type' => 'admission', 'is_mandatory' => 1, 'affects_financials' => 1],
            ['name' => 'ID Card Fee', 'type' => 'admission', 'is_mandatory' => 1, 'affects_financials' => 1],
            ['name' => 'Monthly Tuition Fee', 'type' => 'monthly', 'is_mandatory' => 1, 'affects_financials' => 1],
            ['name' => 'Food Charges', 'type' => 'allocation', 'is_mandatory' => 0, 'affects_financials' => 1],
            ['name' => 'Transport Charges', 'type' => 'allocation', 'is_mandatory' => 0, 'affects_financials' => 1],
            ['name' => 'Annual Charges', 'type' => 'annual', 'is_mandatory' => 1, 'affects_financials' => 1],
        ];

        $feeCategories = [];
        foreach ($feeCategoriesData as $categoryData) {
            $feeCategories[] = FeeCategory::firstOrCreate(
                ['name' => $categoryData['name']],
                array_merge($categoryData, [
                    'description' => $faker->sentence,
                    'is_active' => 1,
                    'company_id' => $company->id,
                    'branch_id' => $ptchsBranch->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ])
            );
        }

        // Create Fee Factors
        $feeFactorsData = [
            ['name' => '12 Months Billing', 'factor_value' => 1.0],
            ['name' => '10 Months (Aug-May)', 'factor_value' => 1.2],
            ['name' => '6 Months Billing', 'factor_value' => 2.0],
            ['name' => 'A-Level/College Installments', 'factor_value' => 2.4],
        ];

        $feeFactors = [];
        foreach ($feeFactorsData as $factorData) {
            $feeFactors[] = FeeFactor::firstOrCreate(
                ['name' => $factorData['name']],
                array_merge($factorData, [
                    'description' => $faker->sentence,
                    'is_active' => 1,
                    'company_id' => $company->id,
                    'branch_id' => $ptchsBranch->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ])
            );
        }

    $createdClasses = DB::table('classes')->get();
    $createdSections = DB::table('sections')->get();


        // Create Fee Structures
        $feeStructures = [];
        foreach ($createdClasses as $class) {
            foreach ($feeFactors as $factor) {
                $structure = FeeStructure::firstOrCreate(
                    ['name' => $class->name . ' - ' . $factor->name . ' Structure'],
                    [
                        'name' => $class->name . ' - ' . $factor->name . ' Structure',
                        'academic_class_id' => $class->id,
                        'fee_factor_id' => $factor->id,
                        'academic_session_id' => $session->id,
                        'is_active' => 1,
                        'company_id' => $company->id,
                        'branch_id' => $ptchsBranch->id,
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]
                );
                $feeStructures[] = $structure;

                // Add Fee Structure Details
                foreach ($feeCategories as $category) {
                    FeeStructureDetail::firstOrCreate(
                        ['fee_structure_id' => $structure->id, 'fee_category_id' => $category->id],
                        [
                            'fee_structure_id' => $structure->id,
                            'fee_category_id' => $category->id,
                            'amount' => $faker->numberBetween(500, 10000),
                            'company_id' => $company->id,
                            'branch_id' => $ptchsBranch->id,
                            'created_by' => $admin->id,
                            'updated_by' => $admin->id,
                        ]
                    );
                }
            }
        }

        // Create 10 Students
        $students = [];
        for ($i = 0; $i < 10; $i++) {
            $randomClass = $faker->randomElement($createdClasses);
            // $randomSection = $faker->randomElement(array_filter($createdSections, fn($s) => $s->class_id == $randomClass->id));
            $randomSection = $faker->randomElement(array_filter(
                $createdSections->all(),
                fn($s) => $s->class_id == $randomClass->id
            ));


            $student = Students::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'student_email' => $faker->unique()->safeEmail,
                'cell_no' => $faker->phoneNumber,
                'student_current_address' => $faker->address,
                'student_dob' => $faker->date(),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'admission_date' => $faker->date(),
                'class_id' => $randomClass->id,
                'section_id' => $randomSection->id,
                'session_id' => $session->id,
                'company_id' => $company->id,
                'branch_id' => $ptchsBranch->id,
                'father_name' => $faker->name('male'),
                'father_cnic' => $faker->numerify('#####-#######-#'),
                'student_id' => 'STD-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            ]);
            $students[] = $student;

            // Assign Fee Structure to Student
            $assignedStructure = $faker->randomElement($feeStructures);
            $totalAmount = $assignedStructure->feeStructureDetails->sum('amount');
            
            $studentFeeAssignment = StudentFeeAssignment::create([
                'student_id' => $student->id,
                'fee_structure_id' => $assignedStructure->id,
                'academic_session_id' => $session->id,
                'total_amount' => $totalAmount,
                'due_date' => $faker->dateTimeBetween('+1 month', '+6 months'),
                'status' => 'pending',
                'company_id' => $company->id,
                'branch_id' => $ptchsBranch->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);

            // Create some Fee Collections for students
            if ($faker->boolean(70)) { // 70% chance to have collections
                $paidAmount = $faker->numberBetween(1000, $totalAmount);
                $feeCollection = FeeCollection::create([
                    'student_id' => $student->id,
                    'academic_session_id' => $session->id,
                    'fee_assignment_id' => $studentFeeAssignment->id,
                    'collection_date' => $faker->dateTimeBetween('-1 month', 'now'),
                    'paid_amount' => $paidAmount,
                    'payment_method' => $faker->randomElement(['Cash', 'Bank Transfer', 'Online']),
                    'remarks' => $faker->sentence,
                    'status' => 'paid',
                    'company_id' => $company->id,
                    'branch_id' => $ptchsBranch->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);

                // Create Fee Collection Details
                foreach ($assignedStructure->feeStructureDetails as $detail) {
                    FeeCollectionDetail::create([
                        'fee_collection_id' => $feeCollection->id,
                        'fee_category_id' => $detail->fee_category_id,
                        'amount' => $faker->numberBetween(100, $detail->amount),
                        'company_id' => $company->id,
                        'branch_id' => $ptchsBranch->id,
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]);
                }

                // Update student fee assignment status
                $studentFeeAssignment->update(['status' => 'partially_paid']);
            }

            // Create some Fee Discounts for students
            if ($faker->boolean(30)) { // 30% chance to have discounts
                FeeDiscount::create([
                    'student_id' => $student->id,
                    'category_id' => $faker->randomElement($feeCategories)->id,
                    'discount_type' => $faker->randomElement(['percentage', 'fixed']),
                    'discount_value' => $faker->numberBetween(5, 50), // 5-50% or 5-5000 fixed
                    'reason' => $faker->sentence,
                    'valid_from' => now()->subMonths(2)->format('Y-m-d'),
                    'valid_to' => now()->addMonths(6)->format('Y-m-d'),
                    'company_id' => $company->id,
                    'branch_id' => $ptchsBranch->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
            }

            // Create some Fee Adjustments for students
            if ($faker->boolean(20)) { // 20% chance to have adjustments
                FeeAdjustment::create([
                    'student_id' => $student->id,
                    'fee_category_id' => $faker->randomElement($feeCategories)->id,
                    'adjustment_type' => $faker->randomElement(['waived', 'refund', 'staff_deduction']),
                    'amount' => $faker->numberBetween(1000, 5000),
                    'reason' => $faker->sentence,
                    'company_id' => $company->id,
                    'branch_id' => $ptchsBranch->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
            }

            // Create some Fee Allocations for students
            if ($faker->boolean(50)) { // 50% chance to have allocations
                $allocationCategories = array_filter($feeCategories, fn($cat) => $cat->type === 'allocation');
                if (!empty($allocationCategories)) {
                    FeeAllocation::create([
                        'student_id' => $student->id,
                        'fee_category_id' => $faker->randomElement($allocationCategories)->id,
                        'amount' => $faker->numberBetween(500, 2000),
                        'is_optional' => $faker->boolean(),
                        'company_id' => $company->id,
                        'branch_id' => $ptchsBranch->id,
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]);
                }
            }

            // Create some Fee Billing records
            if ($faker->boolean(80)) { // 80% chance to have billing records
                $paidAmount = isset($feeCollection) ? $feeCollection->paid_amount : 0;
                FeeBilling::create([
                    'student_id' => $student->id,
                    'academic_session_id' => $session->id,
                    'bill_date' => $faker->dateTimeBetween('-2 months', 'now'),
                    'due_date' => $faker->dateTimeBetween('now', '+1 month'),
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'outstanding_amount' => $totalAmount - $paidAmount,
                    'status' => $faker->randomElement(['generated', 'partially_paid', 'paid']),
                    'challan_number' => $ptchsBranch->branch_code . '-' . $session->title . '-' . date('Ym') . '-' . str_pad($faker->unique()->randomNumber(5), 5, '0', STR_PAD_LEFT),
                    'company_id' => $company->id,
                    'branch_id' => $ptchsBranch->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);
            }
        }

        // FINAL PERMISSIONS FIX - Ensure admin has ALL permissions
        $this->command->info('🔧 Final permissions check...');
        
        // Get admin user
        $adminUser = User::where('email', 'admin@admin.com')->first();
        
        if ($adminUser) {
            // Clear any existing permissions and roles
            $adminUser->permissions()->detach();
            $adminUser->roles()->detach();
            
            // Get admin role
            $adminRole = Role::where('name', 'Admin')->first();
            
            // Get ALL permissions
            $allPermissions = Permission::all();
            
            // Assign role to admin
            if ($adminRole) {
                $adminUser->assignRole($adminRole);
                $adminRole->syncPermissions($allPermissions);
            }
            
            // Give direct permissions to admin user
            $adminUser->givePermissionTo($allPermissions);
            
            $this->command->info('✅ Admin permissions fixed! Total: ' . $allPermissions->count() . ' permissions');
        }

        $this->command->info('🎉 Complete system seeded successfully!');
        $this->command->info('👤 Admin Login: admin@admin.com / 12345678');
        $this->command->info('🎓 Created: ' . count($students) . ' students with complete fee data');
        $this->command->info('💰 Fee Categories: ' . count($feeCategories));
        $this->command->info('📊 Fee Structures: ' . count($feeStructures));
        $this->command->info('🏫 Classes: ' . count($createdClasses));
        $this->command->info('📝 Sections: ' . count($createdSections));
    }
}