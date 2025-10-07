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
use App\Models\Fee\FeeFactor;
use Faker\Factory as Faker;




class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

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
                'role_id' => 1,
                'branch_id' => NULL,
                'department_id' => NULL,
                'password' => bcrypt('12345678')
            ]);

            $user->assignRole([$role->id]);
        }

        // Create Fee Factors
        $feeFactorsData = [
            ['name' => '12 Months Billing', 'factor_value' => 1.0],
            ['name' => '10 Months (Aug-May)', 'factor_value' => 1.2],
            ['name' => '6 Months Billing', 'factor_value' => 2.0],
            ['name' => 'A-Level/College Installments', 'factor_value' => 2.4],
        ];

        $feeFactors = [];
        foreach ($feeFactorsData as $factorData) {
            $feeFactors[] = FeeFactor::firstOrCreate(
                ['name' => $factorData['name']],
                array_merge($factorData, [
                    'description' => $faker->sentence,
                    'is_active' => 1,
                    'company_id' => 1,
                    'branch_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ])
            );
        }
    }
}
