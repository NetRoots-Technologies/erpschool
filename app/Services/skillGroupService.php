<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Exam\SkillGroup;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AcademicClass;

class skillGroupService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        SkillGroup::create([
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'skill_group' => $request->skill_group,
            'sort_skill' => $request->sort_skill,
            'user_id' => Auth::id(),

        ]);
    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = SkillGroup::with('user', 'branch')->OrderBy('created_at', 'desc');


        return Datatables::of($data)->addIndexColumn()

            ->addColumn('status', function ($row) {
                $statusButton = ($row->active == 0)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("exam.skill_groups.destroy", $row->id) . '"   id="class-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('branches-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm skill_group_edit"  data-skill_group-edit=\'' . $row . '\'>Edit</a>';


                // if (Gate::allows('branches-delete'))
                $btn = $btn . ' <button data-id="branch-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->addColumn('branch', function ($row) {
                if ($row->branch)
                    return $row->branch->name;
                else
                    return "N/A";

            })->addColumn('user', function ($row) {
                if ($row->user)
                    return $row->user->name;
                else
                    return "N/A";

            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate; })

            ->rawColumns(['action', 'status', 'school', 'branch'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $skillGroup = SkillGroup::find($id);

        $skillGroup->update([
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'skill_group' => $request->skill_group,
            'sort_skill' => $request->sort_skill,
            'user_id' => Auth::id(),
        ]);

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $skillGroup = SkillGroup::find($id);
        if ($skillGroup) {
            $skillGroup->delete();
        }
    }
    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $skillGroup = SkillGroup::find($request->id);
        if ($skillGroup) {
            $skillGroup->active = ($skillGroup->active == 1) ? 0 : 1;
            $skillGroup->save();
            return $skillGroup;
        }
    }


}
