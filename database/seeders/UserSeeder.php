<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Branch;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch=Branch::first();
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role_id' => 1,
            'branch_id' => $branch->id,
            'password' => bcrypt('12345678'),
        ]);
    }

}
