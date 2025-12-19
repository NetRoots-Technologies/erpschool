<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApprovalRole;

class ApprovalRoleSeeder extends Seeder
{
    public function run()
    {
        ApprovalRole::updateOrCreate(
            ['id' => 2], // HR role ID
            [
                'name' => 'HR',
                'level' => 1,
                'description' => 'Human Resource approval authority for leave requests',
            ]
        );
    }
}
