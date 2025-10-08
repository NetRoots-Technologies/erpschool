<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRpequest;
use App\Services\BCategoryService;

class BudgetCategoryController extends Controller
{

    protected $BcategoryService;

    public function __construct(BCategoryService $BcategoryService)
    {
        $this->BcategoryService = $BcategoryService;
    }

    public function index()
    {

       
        if (!auth()->user()->hasPermissionTo("BudgetCategory-list")) {
            return abort(503);
        }
        $categories = BCategory::whereNull('parent_id')->get();

        return view('admin.inventory_management.budgetcategory.index', compact('categories'));
    }


    public function store(CategoryRpequest $request)
    {

        // dd($request->all());
        if (!auth()->user()->hasPermissionTo("BudgetCategory-create")) {
            return abort(503);
        }
        $this->BcategoryService->store($request->validated());
        return redirect()->route('inventory.category.index');
    }


    public function edit(BCategory $Bcategory, $id)
    {
        if (!auth()->user()->hasPermissionTo("BudgetCategory-edit")) {
            return abort(503);
        }
        $categories = BCategory::whereNull('parent_id')->get(); // Fetch all categories
        $bCategoryId = BCategory::where('id', $id)->first();



        return view('admin.inventory_management.budgetcategory.edit', compact('categories', 'bCategoryId'));
    }


    public function getData()
    {

        if (!auth()->user()->hasPermissionTo("BudgetCategory-list")) {
            return abort(503);
        }

        return $this->BcategoryService->getData();
    }

    public function update(CategoryRpequest $request, $id)
    {
        if (!auth()->user()->hasPermissionTo("BudgetCategory-edit")) {
            return abort(503);
        }
        $this->BcategoryService->update($request->validated(), $id);
        return redirect()
            ->route('inventory.category.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo("BudgetCategory-edit")) {
            return abort(503);
        }
        $this->BcategoryService->delete($id);
        return response()->json(['success' => 'Budget deleted successfully.']);
    }
}
