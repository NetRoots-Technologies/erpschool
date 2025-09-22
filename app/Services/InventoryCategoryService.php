<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\inventory\InventoryCategory;

class InventoryCategoryService
{
    private $baseCode = 850;

    public function getCategories()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return InventoryCategory::get();
    }
    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return InventoryCategory::whereNull('parent_id')->with('recursiveChildren')->get();
    }


    public function store(array $data)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $level = InventoryCategory::find($data['parent_id'], ['level']);
        $code = $this->createCode($level->level + 1, $data['parent_id']);

        InventoryCategory::create([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'],
            'code' => $code,
            'level' => $level->level + 1,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $category = InventoryCategory::find($id);
        return $category->update([
            'name' => $request['name'],
        ]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $category = InventoryCategory::findOrFail($id);
        return $category->delete();
    }

    private function createCode($level, $parent)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        switch ($level) {
            case 2:
                return $this->baseCode . '-' . str_pad('', 1, '0') . InventoryCategory::where('level', $level)->count() + 1;
            case 3:
                $code = InventoryCategory::where('id', $parent)->first(['code']);
                return $code->code . '-' . str_pad('', 2, '0') . InventoryCategory::where('level', $level)->where('parent_id', $parent)->count() + 1;
        }
        return null;
    }
}

