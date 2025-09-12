<?php

namespace App\Services;
use App\Models\Exam\Skills;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SkillsService
{
    // public function store($request)
    // {
    //     if (!Gate::allows('students')) {
    //         return abort(503);
    //     }
    //     $logs = [
    //         'user_id' => Auth::user()->id ?? 'N/A',
    //         'old_name' => 'N/A',
    //         'old_status' => 'N/A',
    //         'date_time' => now()->toDateTimeString(),
    //     ];
    //     Skills::create([
    //         'name' => strtolower(trim($request->name)),
    //         'class_id'     => 'required|exists:academic_classes,id',
    //         'subject_id'   => 'required|exists:subjects,id',
    //         'component_id' => 'required|exists:components,id',

    //         'logs' => json_encode([$logs])
    //     ]);
    // }


public function store($request)
{
    $logs = [
        'user_id'    => Auth::id() ?? 'N/A',
        'old_name'   => 'N/A',
        'old_status' => 'N/A',
        'date_time'  => now()->toDateTimeString(),
    ];

    return Skills::create([
        'name'         => strtolower(trim($request->name)),
        'class_id'     => $request->class_id,
        'course_id'   => $request->course_id,
        'component_id' => $request->component_id,
        'logs'         => json_encode([$logs]),
    ]);
}


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
     $data = Skills::with(['class', 'subject', 'component'])->orderBy('id', 'DESC')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('class', function ($row) {
                return $row->class ? $row->class->name : '-';
            })
            ->addColumn('subject', function ($row) {
                return $row->subject ? $row->subject->name : '-';
            })
            ->addColumn('component', function ($row) {
                return $row->component ? $row->component->name : '-';
            })
            ->addColumn('status', function ($row) {
                return $row->status == 1
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="'.$row->id.'" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="'.$row->id.'" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("exam.skills.destroy", $row->id) . '" id="skills-' . $row->id . '" method="POST">';
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm skills_edit" data-skills-edit=\'' . $row . '\'>Edit</a>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    public function update($request, $id, $image = null)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $skills = Skills::find($id);
        if ($skills) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_name' => $skills->name,
                'old_status' => $skills->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($skills->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $skills->logs = json_encode($old_logs);
            $skills->name = $request->name;
            $skills->save();
            return $skills;
        }
        return null;
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $skills = Skills::find($request->id);
        if ($skills) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_name' => $skills->name,
                'old_status' => $skills->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($skills->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $skills->logs = json_encode($old_logs);
            $skills->status = $skills->status == 1 ? 0 : 1;
            $skills->save();
            return $skills;
        }
        return null;
    }
}
