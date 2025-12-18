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

        $quota = Quotta::create([
            'leave_type' => $request->name,
            'permitted_days' => $request->permit_days,
            'compensatory_status' => 1, // ✅ auto insert
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

        return Quotta::with('department')->find($id);

        // return Quotta::with('departments')->findOrFail($id);
    }

    public function getdata()
    {

        $data = Quotta::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()


            ->addColumn('action', function ($row) {
                //                $btn = '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("hr.qouta_sections.destroy", $row->id) . '">';
                if (Gate::allows('Quota edit')) {
                    $btn = '<a href="' . route("hr.qouta_sections.edit", $row->id) . '" class="btn btn-primary ml-2 mr-2 btn-sm">Edit</a>';
                }
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

        $quota = Quotta::find($id);
        $quota->delete();
    }
}
