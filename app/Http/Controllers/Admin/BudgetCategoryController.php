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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $categories = BCategory::all(); // Fetch all categories
        // dd($categories); // Debugging line to check categories
        return view('admin.inventory_management.budgetcategory.index', compact('categories'));
    }


       public function store(CategoryRpequest $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->BcategoryService->store($request->validated());
        return redirect()->route('inventory.category.index');
    }

     public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->BcategoryService->getData();
    }

      public function update(CategoryRpequest $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->BcategoryService->update($request->validated(),$id);
        return response()->json(['message' => 'Category updated successfully']);
    }




        public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->BcategoryService->delete($id);
        return response()->json(['success' => 'Budget deleted successfully.']);
    }
}
