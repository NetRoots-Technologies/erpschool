<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\BCategory;
use App\Models\Admin\Department;
use App\Models\inventory\Budget;
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
        // dd($validatedData);
        return Budget::create([
            'title' => $validatedData['title'],
            'timeFrame' => $validatedData['timeFrame'],
            'department_id' => $validatedData['costCenter'],
            'startDate'=>$validatedData['startDate'],
            'endDate'=>$validatedData['endDate']??$this->generateEndDate($validatedData['startDate'],$validatedData['timeFrame']),
            'b_category_id' => $validatedData['category'],
            'amount' => $validatedData['amount']
        ]);

    }


    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Budget::with('category', 'department.branch')->orderBy('created_at', 'desc');

        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('title', fn($row) => $row->title ?? 'N/A')

            ->addColumn('category', fn($row) => optional($row->category)->title  ?? 'N/A')

            ->addColumn('timeFrame', fn($row) => $row->timeFrame ?? 'N/A')

            ->addColumn('cost_center', function($row) {
                if ($row->department) {
                    $departmentName = $row->department->name ?? 'N/A';
                    $branchName = $row->department->branch->name ?? 'N/A';
                    return $departmentName . ' (' . $branchName . ')';
                }
                return 'N/A';
            })

            ->addColumn('amount', fn($row) => $row->amount ?? '0')

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("inventory.budget.destroy", $row->id) . '" id="budget-' . $row->id . '" method="POST">';
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary me-2 btn-sm text-white budget_edit" data-budget-edit=\'' . $row . '\'>Edit</a>';
                $btn .= '<button data-id="budget-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                return $btn;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function update($validatedData,$id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data=Budget::findOrFail($id);
        return $data->update([
            'title' => $validatedData['title'],
            'timeFrame' => $validatedData['timeFrame'],
            'department_id' => $validatedData['costCenter'],
            'b_category_id' => $validatedData['category'],
            'amount' => $validatedData['amount']
        ]);
    }
    public function delete($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $budget = Budget::findOrFail($id);
        $budget->delete();
    }
    

}
