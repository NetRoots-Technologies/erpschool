<?php

namespace App\Services;


use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\ClassTimeTable;

class ClassTimeService
{

    public function store($request)
    {

        $classTime = ClassTimeTable::create([
            'days' => $request->get('days'),
            'course_id' => $request->get('course_id'),
            'section_id' => $request->get('section_id'),
            'session_id' => $request->get('session_id'),
            'branch_id' => $request->get('branch_id'),
            'class_id' => $request->get('class_id'),
            'company_id' => $request->get('company_id'),
            'time_table_id' => $request->get('time_table_id'),

        ]);
        return $classTime;
    }


    public function getdata()
    {

        $data = ClassTimeTable::with('section', 'company', 'branch', 'academicSession', 'class', 'course')->orderBy('created_at', 'desc')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                if (Gate::allows('ClassTimetable-edit')) {

                $btn .= '<a href="' . route("academic.class_timetable.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                     }
                if (Gate::allows('ClassTimetable-delete')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("academic.class_timetable.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                  }
                $btn .= '</div>';

                return $btn;

            })

            ->addColumn('company', function ($row) {


                if ($row->company) {
                    return $row->company->name;

                } else {
                    return "N/A";
                }

            })
            ->addColumn('academicSession', function ($row) {


                if ($row->academicSession) {
                    return $row->academicSession->name;

                } else {
                    return "N/A";
                }

            })
            ->addColumn('branch', function ($row) {


                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }


            })->addColumn('class', function ($row) {


                if ($row->class) {
                    return $row->class->name;

                } else {
                    return "N/A";
                }


            })->addColumn('section', function ($row) {
                if ($row->section) {
                    return $row->section->name;

                } else {
                    return "N/A";
                }
            })->addColumn('course', function ($row) {
                if ($row->course) {
                    return $row->course->name;

                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'company', 'branch', 'academicSession', 'section', 'course'])
            ->make(true);
    }

    public function update($request, $id)
    {

        $classTime = ClassTimeTable::find($id);

        $classTime->update([
            'days' => $request->get('days'),
            'course_id' => $request->get('course_id'),
            'section_id' => $request->get('section_id'),
            'session_id' => $request->get('session_id'),
            'branch_id' => $request->get('branch_id'),
            'class_id' => $request->get('class_id'),
            'company_id' => $request->get('company_id'),
            'time_table_id' => $request->get('time_table_id'),

        ]);
        return $classTime;
    }

    public function destroy($id)
    {

        $classTime = ClassTimeTable::find($id);
        if ($classTime) {
            $classTime->delete();
        }
    }


}
