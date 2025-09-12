<?php

namespace App\Services;

use Yajra\DataTables\DataTables;
use App\Models\Academic\TimeTable;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AssignTimeTable;

class AssignTimeTableService
{
    public function store($request)
    {

        //dd($request->all());
        $timetable = AssignTimeTable::create([
            'class_id' => $request->get('class_id'),
            'section_id' => $request->get('section_id'),
            'course_id' => $request->get('course_id'),
            'timetable_id' => $request->get('time_table_id'),
            'teacher_id' => $request->get('teacher_id'),
        ]);
        return $timetable;
    }

    public function getdata()
    {

        $data = AssignTimeTable::with('class', 'section', 'course', 'employee', 'classTimeTable')->orderBy('created_at', 'desc')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

             if (Gate::allows('AssignTimetable-edit')) {
                $btn .= '<a href="' . route("academic.assign_timetable.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
             }
             if (Gate::allows('AssignTimetable-delete')) {
                $btn .= '<form method="POST" action="' . route("academic.assign_timetable.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm btnDelete" data-id="'. $row->id .'" data-url="'. route("academic.assign_timetable.destroy", $row->id) .'" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
             }
                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('class', function ($row) {


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


            })->addColumn('subject', function ($row) {


                if ($row->course) {
                    return $row->course->name;

                } else {
                    return "N/A";
                }


            })->addColumn('teacher', function ($row) {


                if ($row->employee) {
                    return $row->employee->name;

                } else {
                    return "N/A";
                }


            })->addColumn('classTimeTable', function ($row) {


                if ($row->classTimeTable) {
                    return $row->classTimeTable->name;

                } else {
                    return "N/A";
                }


            })
            ->rawColumns(['action', 'classTimeTable', 'teacher', 'subject', 'section', 'class'])
            ->make(true);
    }


    public function update($request, $id)
    {

        $timetable = AssignTimeTable::find($id);

        if ($timetable != null) {
            $timetable->update([
                'class_id' => $request->get('class_id'),
                'section_id' => $request->get('section_id'),
                'course_id' => $request->get('course_id'),
                'timetable_id' => $request->get('time_table_id'),
                'teacher_id' => $request->get('teacher_id'),
            ]);
        }
        return $timetable;
    }


    public function destroy($id)
    {

        $timetable = AssignTimeTable::find($id);
        if ($timetable) {
            $timetable->delete();
        }
    }
}
