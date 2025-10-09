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

        // Optional: Call other essential seeders if needed
        // $this->call(CountrySeeder::class);
        // $this->call(StateSeeder::class);
        // $this->call(CitySeeder::class);
        // Add other essential seeders here as needed
    }
}
