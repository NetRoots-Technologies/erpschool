<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;



use Spatie\Permission\Models\Permission;

use Spatie\Permission\Models\Role;

use \App\Models\Admin\Settings;



class SettingsSeed extends Seeder
{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()
    {

        //        // Permissions has been added
//
//        $MainPermission = Permission::create([
//
//            'name' => 'settings_manage',
//
//            'guard_name' => 'web',
//
//            'parent_id' => 0,
//
//        ]);
//
//        Permission::insert([
//
//            [
//
//                'name' => 'settings_create',
//
//                'guard_name' => 'web',
//
//                'created_at' => \Carbon\Carbon::now(),
//
//                'updated_at' => \Carbon\Carbon::now(),
//
//                'parent_id' => $MainPermission->id,
//
//            ],
//
//            [
//
//                'name' => 'settings_edit',
//
//                'guard_name' => 'web',
//
//                'created_at' => \Carbon\Carbon::now(),
//
//                'updated_at' => \Carbon\Carbon::now(),
//
//                'parent_id' => $MainPermission->id,
//
//            ],
//
//            [
//
//                'name' => 'settings_destroy',
//
//                'guard_name' => 'web',
//
//                'created_at' => \Carbon\Carbon::now(),
//
//                'updated_at' => \Carbon\Carbon::now(),
//
//                'parent_id' => $MainPermission->id,
//
//            ],
//
//        ]);
//
//
//
//        // Assign Permission to 'administrator' role
//
//        $role = Role::findById(1, 'web');
//
//        $role->givePermissionTo('settings_manage');
//
//        $role->givePermissionTo('settings_create');
//
//        $role->givePermissionTo('settings_edit');
//
//        $role->givePermissionTo('settings_destroy');
//
//

        // Insert Default Settings

        $Groups = Settings::insert([

            [

                'id' => 1,

                'name' => 'Currency Symbol',

                'description' => 'PKR',

                'created_at' => \Carbon\Carbon::now(),

                'updated_at' => \Carbon\Carbon::now(),

                'created_by' => 1,

                'updated_by' => 1,

            ],

            [

                'id' => 2,

                'name' => 'Currency Format',

                'description' => '###,###.##',

                'created_at' => \Carbon\Carbon::now(),

                'updated_at' => \Carbon\Carbon::now(),

                'created_by' => 1,

                'updated_by' => 1,

            ],

            [

                'id' => 3,

                'name' => 'Date Format',

                'description' => 'd-M-Y|dd-M-yy',

                'created_at' => \Carbon\Carbon::now(),

                'updated_at' => \Carbon\Carbon::now(),

                'created_by' => 1,

                'updated_by' => 1,

            ]

        ]);

    }

}

