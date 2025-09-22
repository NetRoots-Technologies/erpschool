<?php

namespace App\Services;


use App\Models\Student\Students;
use Yajra\DataTables\DataTables;
use App\Models\Academic\AssignClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AssignClassService
{

    public function store($request)
    {

        $assignClass = AssignClass::create([
            'company_id' => $request->get('company_id'),
            'session_id' => $request->get('session_id'),
            'branch_id' => $request->get('branch_id'),
            'class_id' => $request->get('class_id'),
            'section_id' => $request->get('section_id'),
            'student_id' => $request->get('student_id'),
        ]);

        if ($assignClass) {
            $student = Students::where('id', $request->student_id)->first();
            $student->update([
                'class_id' => $request->get('class_id'),
                'section_id' => $request->get('section_id'),
            ]);
        }
    }


    public function getData()
    {

        $data = AssignClass::with('student', 'Session', 'company', 'branch', 'class', 'section')->orderBy('created_at', 'desc')->get();

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $data->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $data->where('branch_id', $user->branch_id);
            }
        }

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                if (Gate::allows('AssignClassSection-edit')) {

                $btn .= '<a href="' . route("academic.assign_class.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                }
                if (Gate::allows('AssignClassSection-delete')) {
                $btn .= '<form method="POST" action="' . route("academic.assign_class.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm btnDelete" data-id="'. $row->id .'" data-url="'. route("academic.assign_class.destroy", $row->id) .'" style="margin-right: 4px;">Delete</button>';
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

            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }

            })->addColumn('section', function ($row) {

                if ($row->section) {
                    return $row->section->name;
                } else {
                    return "N/A";
                }

            })->addColumn('class', function ($row) {

                if ($row->class) {
                    return $row->class->name;

                } else {
                    return "N/A";
                }

            })->addColumn('session', function ($row) {

                if ($row->session) {
                    return $row->session->name;

                } else {
                    return "N/A";
                }

            })->addColumn('student', function ($row) {

                if ($row->student) {
                    return $row->student->first_name . ' ' . $row->student->last_name;

                } else {
                    return "N/A";
                }

            })->addColumn('student_id', function ($row) {

                if ($row->student) {
                    return $row->student->student_id;

                } else {
                    return "N/A";
                }

            })->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->rawColumns(['action', 'company', 'branch', 'status', 'section', 'student'])
            ->make(true);
    }

    public function update($request, $id)
    {

        $assignClass = AssignClass::find($id);
        $assignClass->update([
            'company_id' => $request->get('company_id'),
            'session_id' => $request->get('session_id'),
            'branch_id' => $request->get('branch_id'),
            'class_id' => $request->get('class_id'),
            'section_id' => $request->get('section_id'),
            'student_id' => $request->get('student_id'),
        ]);

        if ($assignClass) {
            $student = Students::where('id', $request->student_id)->first();
            $student->update([
                'class_id' => $request->get('class_id'),
                'section_id' => $request->get('section_id'),
            ]);
        }
    }

    public function destroy($id)
    {

        $assignClass = AssignClass::find($id);
        if ($assignClass) {
            $assignClass->delete();
        }
    }
}

