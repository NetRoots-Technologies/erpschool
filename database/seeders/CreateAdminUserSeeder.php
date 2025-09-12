<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin\Branch;
use App\Models\Admin\Department;



class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id', 'id')->all();

        $branch = Branch::first();

        $department = Department::first();


        $role->syncPermissions($permissions);
        $user = User::create([
            'name' => 'netroots',
            'email' => 'admin@admin.com',
            'role_id' => json_encode([$role->id]),
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'password' => bcrypt('12345678')
        ]);
        $user->assignRole([$role->id]);

    }
}
