<?php

namespace App\Services;


use App\Models\HR\Quotta;
use Yajra\DataTables\DataTables;
use App\Models\HR\QuotaDepartment;
use App\Models\HR\QuotaDesignation;
use Illuminate\Support\Facades\Gate;

class QuottaService
{


    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $quota = Quotta::create([
            'leave_type' => $request->name,
            'permitted_days' => $request->permit_days,
        ]);

        foreach ($request->departments as $department) {
            QuotaDepartment::create([
                'department_id' => $department,
                'hr_quota_settings_id' => $quota->id,
            ]);
        }

        return $quota;
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Quotta::with('department')->find($id);
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Quotta::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()


            ->addColumn('action', function ($row) {
                //                $btn = '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("hr.qouta_sections.destroy", $row->id) . '">';
                // if (Gate::allows('company-edit'))
                $btn = '<a href="' . route("hr.qouta_sections.edit", $row->id) . '" class="btn btn-primary ml-2 mr-2 btn-sm">Edit</a>';
                // if (Gate::allows('company-delete'))
//                $btn .= '<button data-id="quota-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
//                $btn .= method_field('DELETE') . csrf_field();
//                $btn .= '</form>';
                return $btn;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $quota = Quotta::findOrFail($id);

        $quota->update([
            'leave_type' => $request->name,
            'permitted_days' => $request->permit_days,
        ]);

        $quota->department->each->delete();
        if ($request->departments != null) {
            foreach ($request->departments as $department) {
                QuotaDepartment::create([
                    'department_id' => $department,
                    'hr_quota_settings_id' => $quota->id,
                ]);
            }
        }

        return $quota;
    }


    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $quota = Quotta::find($id);
        $quota->delete();
    }

}
