<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AgentRole extends Seeder
{
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'Agent', 'guard_name' => 'web']);

        $permissionNames = [
            'walk_in_student',
            'walk_in_student-create',
            'students',
            'students-create',
            'student_databank',
        ];

        $permissions = Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();

        if (empty($permissions)) {
            // /Log::warning('No permissions found for Agent role. Ensure permissions are seeded correctly.');
            return;
        }

        $role->syncPermissions($permissions);
    }
}
