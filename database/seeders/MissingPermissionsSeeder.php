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
            ]

            // Fee Management
            // 'Fee' => [
            //     'FeeSections-list', 'FeeSections-create', 'FeeSections-edit', 'FeeSections-delete',
            //     'AccountHead-list', 'AccountHead-create', 'AccountHead-edit', 'AccountHead-delete',
            //     'FeeHeads-list', 'FeeHeads-create', 'FeeHeads-edit', 'FeeHeads-delete',
            //     'FeeCategory-list', 'FeeCategory-create', 'FeeCategory-edit', 'FeeCategory-delete',
            //     'FeeStructure-list', 'FeeStructure-create', 'FeeStructure-edit', 'FeeStructure-delete',
            //     'FeeCollection-list', 'FeeCollection-create', 'FeeCollection-edit', 'FeeCollection-delete',
            //     'FeeDiscount-list', 'FeeDiscount-create', 'FeeDiscount-edit', 'FeeDiscount-delete',
            //     'FeeTerm-list', 'FeeTerm-create', 'FeeTerm-edit', 'FeeTerm-delete',
            //     'StudentRegularFee-list', 'StudentRegularFee-create', 'StudentRegularFee-edit', 'StudentRegularFee-delete',
            //     'BillGeneration-list', 'BillGeneration-create', 'BillGeneration-edit', 'BillGeneration-delete',
            //     'Banks-list', 'Banks-create', 'Banks-edit', 'Banks-delete',
            //     'BanksBranches-list', 'BanksBranches-create', 'BanksBranches-edit', 'BanksBranches-delete',
            //     'BanksAccounts-list', 'BanksAccounts-create', 'BanksAccounts-edit', 'BanksAccounts-delete',
            //     'BanksFile-list',
            // ],




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
