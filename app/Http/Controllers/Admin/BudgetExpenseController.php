<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Tag\B;
use App\Models\Budget;
use App\Models\BCategory;
use Illuminate\Http\Request;
use App\Models\BudgetExpense;
use App\Models\DepartmentBudget;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BudgetExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      
        $budgets = BudgetExpense::with('budget', 'category', 'subcategory');
        if ($request->ajax()) {

            return DataTables::of($budgets)
                ->addIndexColumn()
                ->addColumn('budget_title', function ($row) {
                    return $row->budget->title ?? '-';
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category->title ?? '-';
                })
                ->addColumn('subcategory_name', function ($row) {
                    return $row->subcategory->title ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $btn  = '<a href="' . route("inventory.expense.edit", $row->id) . '" class="btn btn-primary me-2 btn-sm text-white">Edit</a>';
                    $btn .= '<form class="delete_form d-inline" data-route="' . route("inventory.expense.destroy", $row->id) . '" id="budget-expense-' . $row->id . '" method="POST">';
                    $btn .= method_field('DELETE') . csrf_field();
                    $btn .= '<button data-id="budget-expense-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['action', 'budget_title', 'category_name', 'subcategory_name'])
                ->make(true);
        }

        return view("admin.budget_expense.index");
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $budgets = Budget::all();
        return view('admin.budget_expense.create', compact('budgets'));
    }

    public function getCategories(Request $request)
    {
        $categories = DepartmentBudget::where('budget_id', $request->budget_id)
            ->with('category:id,title')
            ->get()
            ->pluck('category')
            ->unique('id')
            ->values();
        // dd($categories);
        return response()->json($categories);
    }

    public function getSubcategories(Request $request)
    {
        $subcategories = DepartmentBudget::where('category_id', $request->category_id)
            ->with('subcategory:id,title')
            ->get()
            ->pluck('subcategory')
            ->unique('id')
            ->values();

        return response()->json($subcategories);
    }

    public function getAllowedAmount(Request $request)
    {

        
        $record = DepartmentBudget::where('budget_id', $request->budget_id)
            ->where('category_id', $request->category_id)
            ->where('sub_category_id', $request->subcategory_id)
            ->first();

            $spent = BudgetExpense::where('budget_id', $request->budget_id)
            ->where('category_id', $request->category_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->sum('expense_amount');


               
                if($spent > 0){
                    $rem = (int)$record->amount - (int)$spent;
                }
            
        return response()->json([
            'allowed_amount' => $record ? $record->amount : 0,
            'rem_amount' => $rem ?? $record->amount,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'budget_id' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'expense_date' => 'required|date',
            'expense_amount' => 'required|numeric|min:1',
        ]);

        // check available allocated amount
        $allocated = DepartmentBudget::where('budget_id', $request->budget_id)
            ->where('category_id', $request->category_id)
            ->where('sub_category_id', $request->subcategory_id)
            ->sum('amount');

        $spent = BudgetExpense::where('budget_id', $request->budget_id)
            ->where('category_id', $request->category_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->sum('expense_amount');

        $remaining = $allocated - $spent;

        if ($request->expense_amount > $remaining) {
            return back()->with('error', 'Expense amount exceeds allocated amount!');
        }

        BudgetExpense::create($request->all());

        return redirect()->route('inventory.expense.index')->with('success', 'Expense Added Successfully');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BudgetExpense  $budgetExpense
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetExpense $budgetExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BudgetExpense  $budgetExpense
     * @return \Illuminate\Http\Response
     */
    public function edit(BudgetExpense $budgetExpense , $id)
    {
    
            $budgets = Budget::get();
            $categories = BCategory::whereIn('id', DepartmentBudget::pluck('category_id'))->get();
            $subcategoy = BCategory::whereIn('id', DepartmentBudget::pluck('sub_category_id'))->get();
            $expense = BudgetExpense::with('budget')->findOrFail($id);

            $departmentBudget = DepartmentBudget::where('budget_id', $expense->budget_id)
            ->where('category_id', $expense->category_id)
            ->where('sub_category_id', $expense->subcategory_id)
            ->first();

           $allocatedAmount = $departmentBudget->amount ?? 0;

           
            
         
          return view('admin.budget_expense.edit', compact('budgets' , 'expense' , 'categories' , 'subcategoy' , 'allocatedAmount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BudgetExpense  $budgetExpense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BudgetExpense $budgetExpense , $id)
    {
        
        $expense = BudgetExpense::findOrFail($id);

        $request->validate([
            'budget_id' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'expense_date' => 'required|date',
            'expense_amount' => 'required|numeric|min:1',
        ]);

        // same allocated vs spent check here
        $allocated = DepartmentBudget::where('budget_id', $request->budget_id)
                        ->where('category_id', $request->category_id)
                        ->where('sub_category_id', $request->subcategory_id)
                        ->sum('amount');

        $spent = BudgetExpense::where('budget_id', $request->budget_id)
                        ->where('category_id', $request->category_id)
                        ->where('subcategory_id', $request->subcategory_id)
                        ->where('id','!=',$id)
                        ->sum('expense_amount');

        $remaining = $allocated - $spent;

        if ($request->expense_amount > $remaining) {
            return back()->with('error', 'Expense amount exceeds allocated amount!');
        }

        $expense->update($request->all());

        return redirect()->route('inventory.expense.index')->with('success', 'Expense Updated Successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetExpense  $budgetExpense
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetExpense $budgetExpense , $id)
    {
        
        $budget = BudgetExpense::findOrFail($id);
        $budget->delete();
        
        return response()->json([
            'success'=> true,

        ]);
    }
}
