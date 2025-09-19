<?php

namespace App\Services;

use Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\StudentAttendance;
use App\Models\Academic\StudentAttendanceData;

class StudentAttendanceService
{

    public function store($request)
    {

        $studentAttendance = StudentAttendance::create([
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'attendance_date' => $request->attendance_date,
        ]);
        foreach ($request->student_id as $student) {
            if (isset($request->attendance[$student])) {
                StudentAttendanceData::create([
                    'student_id' => $student,
                    'attendance' => $request->attendance[$student],
                    'student_attendance_id' => $studentAttendance->id,
                ]);
            } else {
                Log::warning("Missing attendance for student ID: $student");
            }
        }


    }


    public function getdata($request)
    {

        $data = StudentAttendance::with('branch', 'AcademicClass', 'section')
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->orderBy('attendance_date', 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('branch', fn($row) => $row->branch->name ?? 'N/A')
            ->addColumn('AcademicClass', fn($row) => $row->AcademicClass->name ?? 'N/A')
            ->addColumn('section', fn($row) => $row->section->name ?? 'N/A')
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                if (Gate::allows('AttendanceReport-edit')) {
                $btn .= '<a href="' . route("academic.student_attendance.edit", $row->id) . '" class="btn btn-primary btn-sm" style="margin-right: 4px;">Edit</a>';
                }
                if (Gate::allows('AttendanceReport-delete')) {

                $btn .= '<form method="POST" action="' . route("academic.student_attendance.destroy", $row->id) . '" style="margin-right: 4px;">';
                $btn .= '<button type="submit" data-id="' . $row->id . '" data-url="' . route("academic.student_attendance.destroy", $row->id) . '" class="btn btn-danger btn-sm deleteBtn">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                }
                $btn .= '<a href="' . route("academic.student_attendance.pdf", $row->id) . '" class="btn btn-warning btn-sm">PDF</a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action', 'branch', 'section', 'AcademicClass'])
            ->make(true);
    }


    public function getMonthlyData($request)
    {

        $data = StudentAttendance::selectRaw('
            branch_id,
            class_id,
            section_id,
            YEAR(attendance_date) as year,
            MONTH(attendance_date) as month,
            COUNT(*) as total_records
        ')
            ->when($request->branch_id, fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->groupBy('branch_id', 'class_id', 'section_id', 'year', 'month')
            ->with('branch', 'AcademicClass', 'section')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('branch', fn($row) => $row->branch->name ?? 'N/A')
            ->addColumn('AcademicClass', fn($row) => $row->AcademicClass->name ?? 'N/A')
            ->addColumn('section', fn($row) => $row->section->name ?? 'N/A')
            ->addColumn('attendance_date', fn($row) => date('F Y', mktime(0, 0, 0, $row->month, 1, $row->year)))
            ->addColumn('action', function ($row) {
                return '<a href="' . route('academic.student_attendance.monthly.pdf', [
                    'year' => $row->year,
                    'month' => $row->month,
                    'branch_id' => $row->branch_id,
                    'class_id' => $row->class_id,
                    'section_id' => $row->section_id
                ]) . '" class="btn btn-warning btn-sm">PDF</a>';
            })
            ->make(true);
    }


    public function update($request, $id)
    {

        $studentAttendance = StudentAttendance::with('AttendanceData')->findOrFail($id);

        $student_attendance = $request->attendance;

        foreach ($studentAttendance->AttendanceData as $attendance) {
            $student_id = $attendance->student_id;
            if (isset($student_id)) {
                $attendance->attendance = $student_attendance[$student_id];
                $attendance->save();
            }
        }
    }

    public function destroy($id)
    {

        $studentAttendance = StudentAttendance::with('AttendanceData')->findOrFail($id);

        if ($studentAttendance) {
            $studentAttendance->AttendanceData()->delete();
            $studentAttendance->delete();
        }
    }

}

