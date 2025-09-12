<?php

namespace App\Services;


use App\Models\HR\Holiday;
use App\Models\HRM\Employees;

use Carbon\Carbon;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;


class HolidayService
{

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $isRecurring = $request->has('is_recurring') ? $request->is_recurring : 0;

        $holiday = new Holiday();
        $holiday->name = $request->input('name');
        $holiday->branch_id = $request->input('branch_id');
        $holiday->department_id = $request->input('department_id');
        $holiday->employee_id = $request->input('employee_id');
        $holiday->holiday_date = $request->input('holiday_date');
        $holiday->holiday_date_to = $request->input('holiday_date_to');
        $holiday->length = $request->input('holiday_length');
        $holiday->is_recurring = $isRecurring;
        $holiday->save();


        return $holiday;
    }
    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Holiday::all();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.holidays.destroy", $row->id) . '"> ';
                //   if (Gate::allows('Employee-edit'))
                $btn = $btn . '<a href="' . route("hr.holidays.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';
                //   if (Gate::allows('Employee-destroy'))
                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->addColumn('recurring', function ($row) {
                if ($row->is_recurring == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            ->addColumn('length', function ($row) {
                if ($row->length == 'full_day') {
                    return 'Full Day';
                } else {
                    return 'Half Day';
                }
            })
            ->rawColumns(['action', 'recurring', 'length'])
            ->make(true);
    }


    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Holiday::find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $holiday = Holiday::findOrFail($id);
        $isRecurring = $request->has('is_recurring') ? $request->is_recurring : 0;

        $holiday->name = $request->input('name');
        $holiday->branch_id = $request->input('branch_id');
        $holiday->department_id = $request->input('department_id');
        $holiday->employee_id = $request->input('employee_id');
        $holiday->holiday_date = $request->input('holiday_date');

        $holiday->holiday_date_to = $request->input('holiday_date_to');

        $holiday->length = $request->input('holiday_length');

        $holiday->is_recurring = $isRecurring;
        $holiday->save();

    }
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();
    }


}

