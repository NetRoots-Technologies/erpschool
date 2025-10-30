<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // ONLY call the SuperAdminSeeder - it handles everything!
        $this->call(SuperAdminSeeder::class);
        $this->call(MissingPermissionsSeeder::class);
        $this->call(CatSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(AccountGroupSeeder::class);
        // $this->call(CitySeeder::class);
        // Add other essential seeders here as needed
    }
}
