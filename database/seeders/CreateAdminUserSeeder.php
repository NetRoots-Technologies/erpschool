<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Admin\Branch;
use Illuminate\Database\Seeder;
use App\Models\Admin\Department;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;



class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        
        $categories = [
            ['name' => 'Administration'],
            ['name' => 'Academics'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = Permission::pluck('id', 'id')->all();
        $existingPermissions = $role->permissions()->pluck('id')->toArray();

        if (count(array_diff($permissions, $existingPermissions)) > 0) {
            $role->syncPermissions($permissions);
        }

        $ur =   User::where('name', 'Super Admin')->where('email', 'superadmin@admin.com')->first();

        if (!$ur) {
            $user = User::Create([
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'role_id' => json_encode([$role->id]),
                'branch_id' => NULL,
                'department_id' => NULL,
                'password' => bcrypt('12345678')
            ]);

            $user->assignRole([$role->id]);
        }
    }
}
