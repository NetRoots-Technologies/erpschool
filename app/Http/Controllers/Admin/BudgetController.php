<?php

namespace App\Http\Controllers\Admin;


use App\Models\Budget;
use App\Models\BCategory;
use App\Models\BudgetDetail;
use Illuminate\Http\Request;
use App\Imports\BudgetImport;
use App\Services\BudgetService;
use App\Models\DepartmentBudget;
use App\Models\Admin\Departments;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BudgetController extends Controller
{
    protected $budgetService;
    public function __construct(BudgetService $budgetService)
    {
        $this->budgetService = $budgetService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $departments = $this->budgetService->getDepartments();
        $category = $this->budgetService->getCategories();
        return view('admin.inventory_management.budget.index', compact('departments', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        // $departments = Departments::whereNull('parent_id')->get();
        // $categories = BCategory::whereNull('parent_id')->get();
        return view('admin.inventory_management.budget.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestinventory
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->budgetService->store($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Budget Add Successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    public function getData()
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->budgetService->getData();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $budget = Budget::with('details')->findOrFail($id);

        return view('admin.inventory_management.budget.edit', compact('budget'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'timeFrame'   => 'required|string',
            'months'      => 'required|array|min:1',
            'months.*.month'            => 'required|string',
            'months.*.allocated_amount' => 'required|numeric',
            'months.*.allowed_spend'    => 'required|numeric',
        ]);


        $budget = Budget::findOrFail($id);

        $budget->update([
            'title'       => $validatedData['name'],
            'amount'      => $validatedData['amount'],
            'startDate'   => $validatedData['start_date'],
            'endDate'     => $validatedData['end_date'],
            'description' => $validatedData['description'],
            'timeFrame'   => $validatedData['timeFrame'],
        ]);


        $budget->details()->delete();

        foreach ($validatedData['months'] as $month) {
            BudgetDetail::create([
                'budget_id'        => $budget->id,
                'month'            => $month['month'],
                'allocated_amount' => $month['allocated_amount'],
                'allowed_spend'    => $month['allowed_spend'],
            ]);
        }


        return response()->json([
            'status' => 200,
            'message' => 'Budget updated successfully with monthly breakdown!',
        ]);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->budgetService->delete($id);
        return response()->json(['success' => 'Budget deleted successfully.']);
    }


    public function assignDepartment(Budget $budget)
    {

        // $departments = Departments::whereNull('parent_id')->get();
        $categories = BCategory::whereNull('parent_id')->get();
        $subcategories = BCategory::with('parent')->get();


        $dpartBudget = DepartmentBudget::with(['budget', 'category', 'subcategory'])
            ->where('budget_id', $budget->id)
            ->get()
            ->groupBy('month');

        return view('admin.inventory_management.budget.assign_department', compact(
            'subcategories',
            'categories',
            'dpartBudget',
            'budget'
        ));
    }

    public function getSubcategories($parentId)
    {

        $subcategories = BCategory::where('parent_id', $parentId)->get();
        return response()->json($subcategories);
    }


    public function storeDepartmentBudget(Request $request, Budget $budget)
    {

        // dd($request->all());
        foreach ($request->subcategories as $month => $subcatIds) {
            foreach ($subcatIds as $index => $subcatId) {

                $categoryId = $request->categories[$month][$index] ?? null;
                $amount     = $request->amounts[$month][$index] ?? null;

                // null / empty skip
                if (empty($subcatId) || empty($categoryId) || empty($amount)) {
                    continue;
                }

                // Ensure unique: month + subcategory + budget_id
                DepartmentBudget::updateOrCreate(
                    [
                        'budget_id'     => $request->budget_id,
                        'month'         => $month,
                        'sub_category_id' => $subcatId,
                    ],
                    [
                        'category_id'   => $categoryId,
                        'amount'        => $amount,
                    ]
                );
            }
        }

        return response()->json(['status' => 200, 'message' => 'Saved successfully']);
    }



    public function listOfAssignDepartment(Request $request)
    {
        if ($request->ajax()) {
            $data = DepartmentBudget::with(['budgetDeparmnetWise', 'subcategory', 'category']);

            // dd($data);
            return \Yajra\DataTables\DataTables::of($data)

                ->addIndexColumn()
                ->addColumn('budget_name', function ($row) {

                    // dd($row);
                    return $row->budgetDeparmnetWise->title;
                })
                ->addColumn('subcategory', fn($row) => $row->subcategory->title ?? 'N/A')
                ->addColumn('month', fn($row) => $row->month ?? 'N/A')
                ->addColumn('category', fn($row) => $row->category->title ?? 'N/A')
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->rawColumns(['budget_name', 'subcategory', 'category', 'amount', 'month'])
                ->make(true);
        }

        return view('admin.inventory_management.budget.assign_department_list');
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/budget_template.xlsx');

        if (file_exists($file)) {
            return response()->download($file, 'budget_template.xlsx');
        } else {
            return redirect()->back()->with('error', 'Template file not found!');
        }
    }

    // import Budget

    public function import(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');

        try {
            $file = $request->file('file');
            Excel::import(new BudgetImport, $file);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Budget Imported Successfully!');
    }



}

