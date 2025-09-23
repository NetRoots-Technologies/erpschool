<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Budget;
use Illuminate\Http\Request;
use App\Models\DepartmentBudget;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\SupplementaryBudget;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SuppliementaryBudgetController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SupplementaryBudget::with(['budget', 'category', 'subcategory', 'requestedByUser', 'approvedByUser']);
            // dd($data->get()->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('budget_name', function ($row) {
                    return $row->budget->title ?? '-';
                })

                ->addColumn('category_name', function ($row) {
                    return $row->category->title ?? '-';
                })

                ->addColumn('subcatgory_name', function ($row) {
                    return $row->subcategory->title ?? '-';
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 'rejected') {
                        return '<span style = "font-size: 14px; background: #ff0000; color: white;">' . ucfirst($row->status) . '</span>';
                    } else if ($row->status == 'approved') {
                        return '<span style = "font-size: 14px; background: #0089ff; color: white;">' . ucfirst($row->status) . '</span>';
                    } else {
                        return '<span style = "font-size: 14px; background: #4b00ff; color: white;">' . ucfirst($row->status) . '</span>';
                    }
                })

                ->addColumn('requested_by_user', function ($row) {
                    return ucfirst($row->requestedByUser->name);
                })

                ->addColumn('approved_by_user', function ($row) {
                    return $row->approvedByUser->name ?? '-';
                })
                // ->addColumn('action', function ($row) {
                //     return '
                //         <a href="' . route('inventory.supplementory.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                //         <form action="' . route('inventory.supplementory.destroy', $row->id) . '" method="POST" style="display:inline;">
                //             ' . csrf_field() . method_field("DELETE") . '
                //             <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                //         </form>
                //     ';
                // })
                ->rawColumns(['budget_name', 'category_name', 'subcatgory_name', 'status', 'requested_by_user', 'approved_by_user'])
                ->make(true);
        }
        return view('admin.supplementary_budgets.index');
    }

    public function create()
    {
        $budgets = Budget::all();
        return view('admin.supplementary_budgets.create', compact('budgets'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'budget_id' => 'required|exists:sub_budgets,id',
            'category_id' => 'required|exists:b_category,id',
            'sub_category_id' => 'required|exists:b_category,id',
            'requested_amount' => 'required|numeric|min:1',
            'month' => 'required',
            'reason' => 'required',
            'approved_by' => 'nullable',
            'approved_amount' => 'nullable',
        ]);

        $data = $request->all();
        $data['requested_by'] = Auth::user()->id;
        $data['status'] = 'pending';

        SupplementaryBudget::create($data);

        return redirect()->route('inventory.supplementory.index')->with('success', 'Supplementary budget added');
    }

    public function edit(SupplementaryBudget $supplementaryBudget)
    {
        $departmentBudgets = DepartmentBudget::all();
        return view('admin.supplementary_budgets.edit', compact('supplementaryBudget', 'departmentBudgets'));
    }

    public function update(Request $request, SupplementaryBudget $supplementaryBudget)
    {
        $request->validate([
            'department_budget_id' => 'required|exists:department_budgets,id',
            'requested_amount' => 'required|numeric|min:1',
        ]);

        $supplementaryBudget->update($request->all());

        return redirect()->route('inventory.supplementory.index')->with('success', 'Supplementary budget updated');
    }

    public function destroy(SupplementaryBudget $supplementaryBudget)
    {
        $supplementaryBudget->delete();
        return redirect()->route('inventory.supplementory.index')->with('success', 'Deleted successfully');
    }

    // Show requests List
    public function requestList(Request $request)
    {
        if ($request->ajax()) {
            $data = SupplementaryBudget::with(['budget', 'category', 'subcategory', 'requestedByUser', 'approvedByUser']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('budget_name', function ($row) {
                    return $row->budget->title ?? '-';
                })

                ->addColumn('category_name', function ($row) {
                    return $row->category->title ?? '-';
                })

                ->addColumn('subcatgory_name', function ($row) {
                    return $row->subcategory->title ?? '-';
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 'rejected') {
                        return '<span style = "font-size: 14px; background: #ff0000; color: white;">' . ucfirst($row->status) . '</span>';
                    } else if ($row->status == 'approved') {
                        return '<span style = "font-size: 14px; background: #0089ff; color: white;">' . ucfirst($row->status) . '</span>';
                    } else {
                        return '<span style = "font-size: 14px; background: #4b00ff; color: white;">' . ucfirst($row->status) . '</span>';
                    }
                })

                ->addColumn('requested_by_user', function ($row) {
                    return ucfirst($row->requestedByUser->name);
                })

                ->addColumn('approved_by_user', function ($row) {
                    return $row->approvedByUser->name ?? '-';
                })

                ->addColumn('action', function ($row) {

                    if ($row->status == 'approved') {
                        $btn  = '<button type="button" class="btn btn-sm btn-primary" disabled>Approve</button> ';
                    } else {
                        $btn  = '<a data-id="' . $row->id . '" href="' . route('inventory.supplimentary.approved.status', $row->id) . '" class="btn btn-sm btn-primary approve_status">Approve</a> ';
                    }


                    $btn .= '<a data-id="' . $row->id . '" href="' . route('inventory.supplimentary.reject.status', $row->id) . '" class="btn btn-sm btn-danger reject_status">Reject</a> ';
                    return $btn;
                })


                ->rawColumns(['action', 'budget_name', 'category_name', 'subcatgory_name', 'status', 'requested_by_user', 'approved_by_user'])
                ->make(true);
        }
        return view('admin.supplementary_budgets.request_list');
    }

    public function approvedStatus($id)
    {
        $supp = SupplementaryBudget::findOrFail($id);

        // dd($id);
        if ($supp->status == 'approved') {
            return response()->json(['error' => 'This request is already approved!']);
        }

        // Update supplementary budget status
        $supp->status = 'approved';
        $supp->approved_by = Auth::id();
        $supp->save();
        if ($supp) {
            $departmentBudget = DepartmentBudget::where('budget_id', $supp->budget_id)
                ->where('category_id', $supp->category_id)
                ->where('sub_category_id', $supp->sub_category_id)
                ->first();
            if ($departmentBudget) {
                $departmentBudget->amount += $supp->requested_amount;
                $departmentBudget->save();
            }
        }
        return response()->json(['success' => 'Request Approved Successfully!']);
    }


    public function rejectedStatus($id)
    {
        $supp = SupplementaryBudget::findOrFail($id);
        $supp->status = 'rejected';
        $supp->approved_by = Auth::user()->id;
        $supp->save();

        return response()->json(['success' => 'Request Rejected Successfully!']);
    }


    public function varianceReport(Request $request)
    {
        $query = $this->buildVarianceQuery(); // jo upar query bnai hai
        // dd($query->get());
        if ($request->ajax()) {
            return DataTables::of($query)

                ->editColumn('month', function ($row) {
                    $url = route('inventory.reports.suppliementory.details', [
                        'budget_id' => $row->budget_id,
                        'category_id' => $row->category_id,
                        'sub_category_id' => $row->sub_category_id,
                        'month' => $row->month,
                    ]);
                    return '<a href="' . $url . '" target="_blank">' . date("M-Y", strtotime($row->month)) . '</a>';
                })

                ->rawColumns(['month'])
                ->make(true);
        }
        return view("reports.suppliementory_report");
    }

    public function buildVarianceQuery()
    {
        return DB::table('supplementary_budgets as sb')
            ->leftJoin('department_budgets as db', function ($join) {
                $join->on('db.budget_id', '=', 'sb.budget_id')
                    ->on('db.category_id', '=', 'sb.category_id')
                    ->on('db.sub_category_id', '=', 'sb.sub_category_id');
            })
            ->leftJoin('budget_expenses as be', function ($join) {
                $join->on('be.budget_id', '=', 'sb.budget_id')
                    ->on('be.category_id', '=', 'sb.category_id')
                    ->on('be.subcategory_id', '=', 'sb.sub_category_id');
            })
            // ->select([
            //     DB::raw("DATE_FORMAT(sb.month, '%Y-%m') as month"),
            //     'sb.budget_id',
            //     'sb.category_id',
            //     'sb.sub_category_id',
            //     DB::raw('(db.amount - sb.requested_amount) as allocated_budget'),
            //     DB::raw('sb.requested_amount as supplementary_budget'),
            //     DB::raw('db.amount  as total_allowed'),
            //     DB::raw('IFNULL(SUM(be.expense_amount),0) as actual_expense'),
            //     DB::raw('((db.amount - IFNULL(sb.requested_amount,0)) - IFNULL(SUM(be.expense_amount),0)) as variance')
            // ])


            ->select([
                'sb.budget_id',
                'sb.category_id',
                'sb.sub_category_id',
                DB::raw("DATE_FORMAT(sb.month, '%Y-%m') as month"),
                DB::raw("SUM(db.amount - sb.requested_amount) as allocated_budget"),
                DB::raw("SUM(sb.requested_amount) as supplementary_budget"),
                DB::raw("SUM(db.amount) as total_allowed"),
                DB::raw("IFNULL(SUM(be.expense_amount), 0) as actual_expense"),
                DB::raw("SUM((db.amount - IFNULL(sb.requested_amount,0)) - IFNULL(be.expense_amount,0)) as variance")
            ])
            ->where('sb.status', 'approved')


            ->groupBy(DB::raw("DATE_FORMAT(sb.month, '%Y-%m')"));
    }

    public function supplementoryDetails(Request $request)
    {

        $budgetId = $request->budget_id;
        $categoryId = $request->category_id;
        $subCategoryId = $request->sub_category_id;
        // $month = $request->month;
        $date = Carbon::parse($request->month);

        $month = $date->month;
        $year  = $date->year;



        $details = DB::table("budget_expenses")
            ->Join("sub_budgets", "sub_budgets.id", "=", "budget_expenses.budget_id")
            ->Join("b_category", "b_category.id", "=", "budget_expenses.category_id")
            ->Join("b_category as sbcategory", "sbcategory.id", "=", "budget_expenses.subcategory_id")
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->select(
                'sub_budgets.title as budget_name',
                'budget_expenses.expense_date',
                'budget_expenses.expense_amount',
                'budget_expenses.description',
                'b_category.title as c_name',
                'sbcategory.title as sb_name'
            )

            ->paginate(20);

        return view('reports.supplementory_details', compact('details', 'budgetId', 'categoryId', 'subCategoryId', 'month', 'year'));
    }
}
