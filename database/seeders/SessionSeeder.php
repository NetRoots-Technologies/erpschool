<?php

namespace Database\Seeders;

use App\Models\Admin\Session;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Session::create([
            'title' => '2021-2022 (Jan-March)',
            'start_date' => '2022-01-01',
            'end_date' => '2022-03-01',
            'status' => '1',
        ]);
    }
}
