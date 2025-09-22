<?php

namespace App\Services;


use Carbon\Carbon;
use App\Models\Exam\SkillType;
use App\Models\Exam\ExamDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class skillTypeService
{
    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        SkillType::create([
            'company_id' => $request->company_id,
            'session_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->course_id,
            'group_id' => $request->group_id,
            'skill_id' => $request->skill_id,
            'user_id' => Auth::id(),
        ]);
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = SkillType::with('branch', 'subject', 'AcademicClass', 'group', 'user', 'skill')->orderBy('created_at', 'desc')->get();
        Log::info(json_encode($data, true));
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("exam.skill_types.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("exam.skill_types.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                //                }
                $btn .= '</div>';

                return $btn;
            })
            //            }) ->addColumn('active', function ($row) {
//                $statusButton = ($row->active == 1)
//                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
//                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
//
//                return $statusButton;
//            })

            ->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }
            })->addColumn('subject', function ($row) {
                if ($row->subject) {
                    return $row->subject->name;

                } else {
                    return "N/A";
                }
            })->addColumn('AcademicClass', function ($row) {
                if ($row->AcademicClass) {
                    return $row->AcademicClass->name;

                } else {
                    return "N/A";
                }
            })->addColumn('group', function ($row) {
                if ($row->group) {
                    return $row->group->skill_group;

                } else {
                    return "N/A";
                }
            })->addColumn('skill', function ($row) {
                if ($row->skill) {
                    return $row->skill->name;

                } else {
                    return "N/A";
                }
            })->addColumn('user', function ($row) {
                if ($row->user) {
                    return $row->user->name;

                } else {
                    return "N/A";
                }
            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate;
            })
            ->rawColumns(['action', 'branch', 'subject', 'AcademicClass', 'group', 'skill', 'created_at', 'user'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $skillType = SkillType::find($id);
        $skillType->update([
            'company_id' => $request->company_id,
            'session_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'subject_id' => $request->course_id,
            'group_id' => $request->group_id,
            'skill_id' => $request->skill_id,
            'user_id' => Auth::id(),
        ]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $skillType = SkillType::find($id);
        if ($skillType) {
            $skillType->delete();
        }
    }

}

