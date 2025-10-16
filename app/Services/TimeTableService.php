<?php

namespace App\Services;


use Yajra\DataTables\DataTables;
use App\Models\Academic\TimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TimeTableService
{
    public function store($request)
    {

        $timetable = TimeTable::create([
            'name' => $request->get('name'),
            'session_id' => $request->get('session_id'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'company_id' => $request->get('company_id'),
            'branch_id' => $request->get('branch_id'),
            'school_id' => $request->get('school_id'),
        ]);

        return $timetable;
    }


    public function getdata()
    {

        $user = Auth::user();
        $data = TimeTable::with('company', 'branch', 'school', 'session')
            ->when($user->company_id, fn($q) => $q->where('company_id', $user->company_id))
            ->when($user->branch_id, fn($q) => $q->where('branch_id', $user->branch_id))
            ->orderBy('created_at', 'desc')
            ->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                if (auth()->user()->can('Timetable-edit')) {
                    $btn .= '<a href="' . route("academic.timetables.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                }

                if (auth()->user()->can('Timetable-delete')) {
                    $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("academic.timetables.destroy", $row->id) . '">';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                    $btn .= '</form>';

                    $btn .= '</div>';
                }


                return $btn;
            })

            ->addColumn('company', function ($row) {

                if ($row->company) {
                    return $row->company->name;
                } else {
                    return "N/A";
                }
            })->addColumn('branch', function ($row) {

                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('school', function ($row) {

                if ($row->school) {
                    return $row->school->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('session', function ($row) {

                if ($row->session) {
                    return $row->session->name;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'company', 'branch', 'school', 'session'])
            ->make(true);
    }

    public function update($request, $id)
    {

        $timetable = TimeTable::find($id);

        $timetable->update([
            'name' => $request->get('name'),
            'session_id' => $request->get('session_id'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'company_id' => $request->get('company_id'),
            'branch_id' => $request->get('branch_id'),
            'school_id' => $request->get('school_id'),
        ]);
    }

    public function destroy($id)
    {

        $timetable = TimeTable::find($id);
        if ($timetable) {
            $timetable->delete();
        }
    }
}
