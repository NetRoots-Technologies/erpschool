<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalRole;

class ApprovalRoleSeeder extends Seeder
{
    public function run()
    {
        ApprovalRole::updateOrCreate(
            ['id' => 1], // fixed ID (optional)
            [
                'name' => 'Super Admin',
                'level' => 0, // highest priority
                'description' => 'System Super Administrator with full approval authority',
            ]
        );
    }
}
