<?php

namespace App\Services;

use Config;
use DataTables;
use Carbon\Carbon;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Exam\ClassSubject;
use App\Models\Academic\SchoolType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AcademicClass;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;


class ClassSubjectService
{
    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $fields = ['skill', 'acd', 'compulsory'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $request->$field = 1;
            }
        }

        ClassSubject::create([
            'company_id' => $request->company_id,
            'session_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'skill' => $request->skill ?? 0,
            'skill_sort' => $request->skill_sort ?? '',
            'acd' => $request->acd ?? 0,
            'acd_sort' => $request->acd_sort ?? '',
            'compulsory' => $request->compulsory ?? 0,
            'user_id' => Auth::id(),
        ]);
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = ClassSubject::with('user', 'company', 'branch', 'Subject', 'AcademicClass')->orderBy('created_at', 'desc');

        // if (Auth::check()) {
        //     $company_id = Auth::user()->company_id;
        //     $branch_id = Auth::user()->branch_id;
        //     if (!is_null($company_id)) {
        //         $data->where('company_id', $company_id);
        //     }
        //     if (!is_null($branch_id)) {
        //         $data->where('branch_id', $branch_id);
        //     }
        // }

        return \Yajra\DataTables\DataTables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                $btn .= '<a href="' . route("exam.class_subjects.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("exam.class_subjects.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';

                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('user', function ($row) {

                if ($row->user) {
                    return $row->user->name;

                } else {
                    return "N/A";
                }

            })->addColumn('company', function ($row) {

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

            })->addColumn('Subject', function ($row) {

                if ($row->Subject) {
                    return $row->Subject->name;

                } else {
                    return "N/A";
                }

            })->addColumn('AcademicClass', function ($row) {

                if ($row->AcademicClass) {
                    return $row->AcademicClass->name;

                } else {
                    return "N/A";
                }
            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate;
            })
            ->rawColumns(['action', 'AcademicClass', 'Subject', 'branch', 'company', 'user', 'created_at'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $fields = ['skill', 'acd', 'compulsory'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $request->$field = 1;
            }
        }

        $classSubject = ClassSubject::find($id);
        $classSubject->update([
            'company_id' => $request->company_id,
            'session_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'skill' => $request->skill ?? 0,
            'skill_sort' => $request->skill_sort ?? '',
            'acd' => $request->acd ?? 0,
            'acd_sort' => $request->acd_sort ?? '',
            'compulsory' => $request->compulsory ?? 0,
            'user_id' => Auth::id(),
        ]);

    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $classSubject = ClassSubject::find($id);
        if ($classSubject) {
            $classSubject->delete();
        }
    }


}

