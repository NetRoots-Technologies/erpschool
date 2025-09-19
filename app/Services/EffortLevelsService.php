<?php

namespace App\Services;
use App\Models\Exam\EffortLevels;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EffortLevelsService
{
    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $logs = [
            'user_id' => Auth::user()->id ?? 'N/A',
            'old_abbrev' => 'N/A',
            'old_key' => 'N/A',
            'old_status' => 'N/A',
            'date_time' => now()->toDateTimeString(),
        ];
        EffortLevels::create([
            'abbrev' => strtolower(trim($request->abbrev)),
            'key' => strtolower(trim($request->key)),
            'status' => 1,
            'logs' => json_encode([$logs])
        ]);
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = EffortLevels::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('abbrev', function ($row) {
                return $row->abbrev;
            })
            ->addColumn('key', function ($row) {
                return $row->key;
            })
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 0)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("exam.effort_levels.destroy", $row->id) . '"   id="effort_levels_' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm effort_levels_edit" data-effort_levels_edit=\'' . $row . '\'>Edit</a>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['abbrev', 'key', 'action', 'status'])
            ->make(true);
    }

    public function update($request, $id, $image = null)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $effort_levels = EffortLevels::find($id);
        if ($effort_levels) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_abbrev' => $effort_levels->abbrev,
                'old_key' => $effort_levels->key,
                'old_status' => $effort_levels->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($effort_levels->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $effort_levels->logs = json_encode($old_logs);
            $effort_levels->abbrev = $request->abbrev;
            $effort_levels->key = $request->key;
            $effort_levels->save();
            return $effort_levels;
        }
        return null;
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        } 
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $effort_levels = EffortLevels::find($request->id);
        if ($effort_levels) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_abbrev' => $effort_levels->abbrev,
                'old_key' => $effort_levels->key,
                'old_status' => $effort_levels->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($effort_levels->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $effort_levels->logs = json_encode($old_logs);
            $effort_levels->status = $effort_levels->status == 1 ? 0 : 1;
            $effort_levels->save();
            return $effort_levels;
        }
        return null;
    }
}

