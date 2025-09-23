<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\BCategory;
use App\Models\BudgetDetail;
use App\Models\Admin\Department;
use App\Models\Budget;
use Illuminate\Support\Facades\Gate;

class BudgetService
{

    private function generateEndDate($startDate,$timeFrame)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        switch($timeFrame)
        {
            case 'monthly':
                return Carbon::parse($startDate)->addMonth();
            case 'biAnnual':
                return Carbon::parse($startDate)->addMonths(6);
            case 'quarterly':
                return Carbon::parse($startDate)->addMonths(3);
            case 'annual':
                return Carbon::parse($startDate)->addYear();
        }
        return null;
    }
    public function getDepartments()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Department::with('branch')->where('status', true)->get();
    }

    public function getCategories()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return BCategory::get();
    }

        public function store($validatedData)
        {
            // dd($validatedData);
            if (!Gate::allows('students')) {
                return abort(503);
            }
            
                    $data = Budget::create([
                    'title'       => $validatedData['name'],
                    'timeFrame'   => $validatedData['timeFrame'],
                    'startDate'   => $validatedData['start_date'],
                    'endDate'     => $validatedData['end_date'],
                                    // ?? $this->generateEndDate($validatedData['start_date'], $validatedData['timeFrame']),
                    'amount'      => $validatedData['amount'],
                    'description' => $validatedData['description']
                ]);

                // dd($data);
                
                if (!empty($validatedData['months'])) {
                    foreach ($validatedData['months'] as $month) {
                        BudgetDetail::create([ 
                            'budget_id'        => $data->id,
                            'month'            => (string) $month['month'],
                            'allocated_amount' => $month['allowed_spend'] ?? 0,
                            'allowed_spend'    => $month['allowed_spend'] ?? 0,
                        ]);
                    }
                }

            return true;

        }


    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Budget::orderBy('created_at', 'desc');

        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('title', fn($row) => $row->title ?? 'N/A')

            // ->addColumn('category', fn($row) => optional($row->category)->title  ?? 'N/A')

            // ->addColumn('timeFrame', fn($row) => $row->timeFrame ?? 'N/A')

            // ->addColumn('cost_center', function($row) {
            //     if ($row->department) {
            //         $departmentName = $row->department->name ?? 'N/A';
            //         $branchName = $row->department->branch->name ?? 'N/A';
            //         return $departmentName . ' (' . $branchName . ')';
            //     }
            //     return 'N/A';
            // })

            // ->addColumn('amount', fn($row) => $row->amount ?? '0')

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("inventory.budget.destroy", $row->id) . '" id="budget-' . $row->id . '" method="POST">';
                $btn = '<a href="' . route("inventory.budget.edit", $row->id) . '" class="btn btn-primary me-2 btn-sm">Edit</a>';
                $btn .= '<form class="delete_form d-inline" data-route="' . route("inventory.budget.destroy", $row->id) . '" id="budget-' . $row->id . '" method="POST">';
                $btn .= csrf_field();
                $btn .= method_field('DELETE'); // yahan DELETE use karna hai
                $btn .= '<button data-id="budget-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';
                $btn .= '</form>';
              

                $btn .= '<a href="' . route("inventory.budget.assignDepartment", $row->id) . '" class="btn btn-info me-2 btn-sm" style="margin-left: 6px;">Assign Departments</a>';
                return $btn;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    
    public function delete($id)

    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        
        $budget = Budget::findOrFail($id);
        $budget->details()->delete();
        $budget->delete();

    }

}
