<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupJSONSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('database/seeders/groups.json');

        if (!File::exists($path)) {
            $this->command->error("File does not exist at path: $path");
            return;
        }

        DB::table('groups')->truncate();
        $json = File::get($path);
        $group = collect(json_decode($json));
        $group->each(function ($p) {
            DB::table('groups')->insert([
                'id' => $p->id,
                'name' => $p->name,
                'number' => $p->number,
                'level' => $p->level,
                'account_type_id' => $p->account_type_id,
                'type' => $p->type,
                'parent_type' => $p->parent_type,
                'parent_id' => $p->parent_id,
                'status' => $p->status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
