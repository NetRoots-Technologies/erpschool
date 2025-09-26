<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $company = Company::where('name', 'Cornerstone Pvt Ltd')->first();

        if ($company) {
            Branch::firstOrCreate(
                ['name' => 'Global Campus', 'company_id' => $company->id],
                [
                    'address' => 'Lahore, Pakistan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            Branch::firstOrCreate(
                ['name' => 'PTCHS Campus', 'company_id' => $company->id],
                [
                    'address' => 'Lahore, Pakistan',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
