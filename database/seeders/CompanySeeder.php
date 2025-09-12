<?php

namespace Database\Seeders;

use App\Models\Admin\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::where('name', 'Cornerstone Pvt Ltd')->first();
        if(empty($company)){
            Company::create([
                'name' => 'Cornerstone Pvt Ltd',
            ]);
        }
    }
}
