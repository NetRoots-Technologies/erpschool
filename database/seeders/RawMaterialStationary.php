<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class RawMaterialStationary extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Pens', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'box'],
            ['name' => 'Notebooks', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'piece'],
            ['name' => 'Sticky Notes', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'pack'],
            ['name' => 'Markers', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'box'],
            ['name' => 'A4 Paper', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'ream'],
            ['name' => 'Folders', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'piece'],
            ['name' => 'Tape', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'roll'],
            // ['name' => 'Scissors', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'piece'],
            // ['name' => 'Staplers', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'piece'],
            // ['name' => 'Highlighters', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'set'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
