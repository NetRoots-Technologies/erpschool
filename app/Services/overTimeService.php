<?php

namespace App\Services;


use App\Models\HR\OverTime;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class overTimeService
{
    public function store($request)
    {

        foreach ($request['employee_id'] as $key => $employee) {
            OverTime::create([
                'branch_id' => $request['branch_id'],
                'employee_id' => $employee,
                'total' => $request['total'][$key],
                'start_date' => $request['start_date'][$key],
                'end_date' => $request['end_date'][$key],
                'total_time' => $request['total_time'][$key],
                'action' => $request['action'][$key],
            ]);
        }
    }




    public function getdata()
    {

        $data = OverTime::with('employee', 'branch')->orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                if (auth()->can()->can('Overtime-edit')) {
                    $btn .= '<a href="' . route("hr.overtime.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                }

                if (auth()->can()->can('Overtime-Overtime-delete')) {
                    $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("hr.overtime.destroy", $row->id) . '">';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                    $btn .= '</form>';

                    $btn .= '</div>';
                }



                return $btn;
            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return "N/A";
                }
            })->addColumn('employee', function ($row) {
                if ($row->employee) {
                    return $row->employee->name;
                } else {
                    return "N/A";
                }
            })->addColumn('allow', function ($row) {
                if ($row->action == 'yes') {
                    return 'Yes';
                } elseif ($row->action == 'no') {
                    return 'No';
                } else {
                    return "N/A";
                }
            })

            ->rawColumns(['action', 'branch', 'employee', 'allow'])
            ->make(true);
    }


    public function update($request, $id)
    {

        $overtime = OverTime::find($id);

        $overtime_data = [
            'total' => $request['total'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'total_time' => $request['total_time'],
            'action' => $request['action'],
        ];

        $overtime->update($overtime_data);
    }

    public function destroy($id)
    {

        $overtime = OverTime::find($id);
        if ($overtime) {
            $overtime->delete();
        }
    }
}
