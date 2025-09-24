<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class RawMaterialCafe extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Coffee Beans', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'kg'],
            ['name' => 'Milk', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'litre'],
            ['name' => 'Sugar', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'kg'],
            ['name' => 'Flour', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'kg'],
            ['name' => 'Butter', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'kg'],
            ['name' => 'Eggs', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'tray'],
            ['name' => 'Chocolate Syrup', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'bottle'],
            ['name' => 'Whipping Cream', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'litre'],
            ['name' => 'Vanilla Extract', 'status' => 1, 'type' => 'F', 'measuring_unit' => 'bottle'],
            ['name' => 'Paper Cups', 'status' => 1, 'type' => 'S', 'measuring_unit' => 'pack'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
