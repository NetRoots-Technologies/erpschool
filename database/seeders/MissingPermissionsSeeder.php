<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MissingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guard = 'web';
        $permissions = [

            'Fleet' => [
                'fleet-dashboard',
                'vahicals-list',
                'vahicals-create',
                'vahicals-edit',
                'vahicals-delete',
                'vahicals-view',
                'drivers-list',
                'drivers-create',
                'drivers-edit',
                'drivers-delete',
                'drivers-view',
                'routes-list',
                'routes-create',
                'routes-edit',
                'routes-delete',
                "routes-view",
                'Fleet-maintenance-list',
                'Fleet-maintenance-create',
                'Fleet-maintenance-edit',
                'Fleet-maintenance-delete',
                'Fleet-maintenance-view',
                'Fule-record-list',
                'Fule-record-create',
                'Fule-record-edit',
                'Fule-record-delete',
                'Fule-record-view',
                'Fleet-expense-list',
                'Fleet-expense-create',
                'Fleet-expense-edit',
                'Fleet-expense-delete',
                'Fleet-expense-view',
                'students-transport-list',
                'students-transport-create',
                'students-transport-edit',
                'students-transport-delete',
                'students-transport-view',
            ],

            'Leave Settings' => [
                'Quota list' , "Quota edit",
                "Holiday list", "Holiday create","Holiday edit","Holiday delete",
                "Leave Request List" ,  "Leave Request create" ,  "Leave Request view" ,  "Leave Request approved" ,
                'Manage Leave List' , "Manage Leave view",
            ],

            'SignatoryAuthorities' => [
                "SignatoryAuthorities-edit" , "SignatoryAuthorities-delete"
            ],

            "StudentManagement" => [
                'ViewStudents-create', 'ViewStudents-edit' , "ViewStudents-delete",
                "Timetable-list", "Timetable-create", "Timetable-edit", "Timetable-delete",
            ],

            "Exam"=>[
                'class-subjects-list', 'class-subjects-create', 'class-subjects-edit', 'class subjects-delete',
                'Student Exam Report'
            ],

            "supplementory" => [
                "supplementory delete"  , "supplementory request approved" , "supplementory request reject"  , 'supplementory report'
            ],

            "Employees" => [
                'Employees-Bank-Details'
            ],

            "CafeInventory" => [
                'PurchaseOrders-print' , "PurchaseOrders-view" , "PurchaseOrders-pdf",
                'inventory-list',
                'inventory-create',
                'inventory-edit',
                'inventory-view',
                'inventory-delete',
            ],

           'Fee Management' => [
                'fee-dashboard',
                'fee-Categories-list',
                'fee-Categories-create',
                'fee-Categories-edit',
                'fee-Categories-delete',
                'fee-Categories-view',
                'fee-structures-list',
                'fee-structures-create',
                'fee-structures-edit',
                'fee-structures-delete',
                'fee-structures-view',
                'fee-collections-list',
                'fee-collections-create',
                'fee-collections-edit',
                "fee-collections-view",
                'pay-challan',
                'fee-discount-list',
                'fee-discount-create',
                'fee-discount-edit',
                'fee-discount-delete',
                'fee-billing-list',
                'fee-billing-create',
                'fee-billing-print',
                'fee-report-list',
                'fee-report-outstanding',
                'fee-report-ledger',
                'fee-report-income',
            ],


            'FinancialYears' => [
                'FinancialYears-list',
                'FinancialYears-create',
                'FinancialYears-edit',
                'FinancialYears-delete',
                'FinancialYears-view',
            ],

              'Assets' => [
                'AssetType-list', 'AssetType-create', 'AssetType-edit', 'AssetType-delete',
                'Assets-list', 'Assets-create', 'Assets-edit', 'Assets-delete',
                'AssetsBulk-list', 'AssetsBulk-create', 'AssetsBulk-edit', 'AssetsBulk-delete',
            ],
           


        ];

        foreach ($permissions as $groupName => $items) {
            $parent = Permission::firstOrCreate(
                ['name' => $groupName, 'guard_name' => $guard],
                [
                    'main' => 1,
                    'parent_id' => 0,
                ]
            );

            
            foreach ($items as $permName) {
                Permission::firstOrCreate(
                    ['name' => $permName, 'guard_name' => $guard],
                    [
                        'main' => 0,
                        'parent_id' => $parent->id, 
                    ]
                );
            }
        }

        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => $guard],
            []
        );

        $allPermissionNames = Permission::where('guard_name', $guard)->pluck('name')->all();
        $superAdmin->syncPermissions($allPermissionNames);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
