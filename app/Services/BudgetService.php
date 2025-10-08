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
       if (!auth()->user()->hasPermissionTo('Budget')) {
            abort(503, 'Unauthorized');
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
       
        return Department::with('branch')->where('status', true)->get();
    }

    public function getCategories()
    {
    
        return BCategory::get();
    }

        public function store($validatedData)
        {
            
            
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
       

        $data = Budget::orderBy('created_at', 'desc');

        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('title', fn($row) => $row->title ?? 'N/A')
            ->addColumn('action', function ($row) {
                $btn = '';

                if (auth()->user()->can('Budget-edit')) {
                    $btn .= '<a href="'.route('inventory.budget.edit', $row->id).'" class="btn btn-primary me-2 btn-sm">Edit</a>';
                }

                if (auth()->user()->can('Budget-delete')) {
                    $btn .= '<form class="delete_form d-inline" data-route="'.route('inventory.budget.destroy', $row->id).'" id="budget-'.$row->id.'" method="POST">'
                        . csrf_field()
                        . method_field('DELETE')
                        . '<button data-id="budget-'.$row->id.'" type="button" class="btn btn-danger delete btn-sm">Delete</button>'
                        . '</form>';
                }

                if (auth()->user()->can('Budget-list')) {
                    $btn .= '<a href="'.route('inventory.budget.assignDepartment', $row->id).'" class="btn btn-info ms-2 btn-sm">Assign Departments</a>';
                }

                return $btn ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    
    public function delete($id)

    {
      
        $budget = Budget::findOrFail($id);
        $budget->details()->delete();
        $budget->delete();

    }

}
