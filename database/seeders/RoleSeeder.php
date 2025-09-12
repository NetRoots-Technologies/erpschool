<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            // Leadership & Management
            'board_of_management',
            'director',
            'principal',
            'vice_principal',
            'education_consultant',
            // Admin
            'central_office_staff',
            'hr_manager',
            'admin_officer',
            'campus_manager',

            // Finance
            'finance_manager',
            'fee_recovery_officer',
            'asset_manager',
            'budget_officer',
            'accountant',

            // Academic
            'headmistress',
            'preschool_teacher',
            'elementary_teacher',
            'middle_school_teacher',
            'high_school_teacher',
            'btec_teacher',
            'sen_teacher',

            // Support Staff
            'maintenance_staff',
            'safety_security_staff',
            'housekeeping_staff',
            'transport_staff',
            'store_keeper',

            // Campus Assignment (if needed)
            'global_campus_staff',
            'ptchs_campus_staff',
            'national_campus_staff',
            'mangla_campus_staff',
        ];

        foreach ($roles as $role) {
            $formattedRole = Str::of($role)->replace('_', ' ')->title(); // e.g. "Board Of Management"
            Role::firstOrCreate(['name' => $formattedRole]);
        }
    }
}
