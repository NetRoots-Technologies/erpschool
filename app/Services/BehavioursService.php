<?php

namespace App\Services;
use App\Models\Exam\Behaviours;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BehavioursService
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
        Behaviours::create([
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
        $data = Behaviours::orderby('id', 'DESC');
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

                $btn = ' <form class="delete_form" data-route="' . route("exam.behaviours.destroy", $row->id) . '"   id="behaviours-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm behaviours_edit" data-behaviours-edit=\'' . $row . '\'>Edit</a>';
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
        $behaviours = Behaviours::find($id);
        if ($behaviours) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_abbrev' => $behaviours->abbrev,
                'old_key' => $behaviours->key,
                'old_status' => $behaviours->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($behaviours->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $behaviours->logs = json_encode($old_logs);
            $behaviours->abbrev = $request->abbrev;
            $behaviours->key = $request->key;
            $behaviours->save();
            return $behaviours;
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
        $behaviours = Behaviours::find($request->id);
        if ($behaviours) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_abbrev' => $behaviours->abbrev,
                'old_key' => $behaviours->key,
                'old_status' => $behaviours->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($behaviours->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $behaviours->logs = json_encode($old_logs);
            $behaviours->status = $behaviours->status == 1 ? 0 : 1;
            $behaviours->save();
            return $behaviours;
        }
        return null;
    }
}

