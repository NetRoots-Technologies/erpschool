<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\inventory\InventoryCategory;
use App\Services\InventoryCategoryService;
use Illuminate\Support\Facades\Gate;

class InventoryCategoryController extends Controller
{
    private $inventoryCategoryService;
    private $baseCode = 1050;

    public function __construct()
    {
        $this->inventoryCategoryService = new InventoryCategoryService;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $categories = $this->inventoryCategoryService->getCategories();
        $inventoryCategories = $this->inventoryCategoryService->getData();
        return view('admin.inventory_management.inventory_center.index', compact('categories', 'inventoryCategories'));
    }

    public function store(Request $request)
    { 
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $request->validate([
            'name' => 'required|min:3',
            'category' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $category = InventoryCategory::find($value);
                    if (!$category || $category->level > 2) {
                        $fail('The selected category must be level 2 or above.');
                    }
                },
            ],
        ]);

        $level = InventoryCategory::find($request['category'], ['level']);
        $code = $this->createCode($level->level + 1, $request['category']);

                $this->inventoryCategoryService->store([
                    'name' => $request['name'],
                    'parent_id' => $request['category'],
                    'level' => $level->level + 1,
                    'code' => $code,
                ]);

        return redirect()->route('inventory.inventory-center.index')->with('success', 'Category created successfully.');
    }

    private function createCode($level, $parent)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        switch ($level) {
            case 2:
                return $this->baseCode . '-' . str_pad('', 2, '0') . (InventoryCategory::where('level', $level)->count() + 1);
            case 3:
                $code = InventoryCategory::find($parent)->code ?? '';
                return $code . '-' . str_pad('', 2, '0') . (InventoryCategory::where('level', $level)->where('parent_id', $parent)->count() + 1);
        }
        return null;
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $category = InventoryCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $request->validate([
            'name' => 'required|min:3',
        ]);

        if ($this->inventoryCategoryService->update($request, $id)) {
            return redirect()->back()->with('success', 'Category updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update category.');
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if ($this->inventoryCategoryService->destroy($id)) {
            return redirect()->back()->with('success', 'Category deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete category.');
    }
}
