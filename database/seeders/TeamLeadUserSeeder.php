<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TeamLeadUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Team Lead']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);
        $user = User::create([
            'name' => 'Team Lead',
            'email' => 'tl@tl.com',
            'role_id' => $role->id,
            'password' => bcrypt('12345678')
        ]);


        $user->assignRole([$role->id]);

    }
}
