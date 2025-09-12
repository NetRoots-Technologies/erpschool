<?php

namespace App\Services;
use App\Helpers\UserHelper;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\ActiveSession;

class ActiveSessionService
{
    public function store($request)
    {

        $activeSession = ActiveSession::create([
            'session_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'company_id' => $request->company_id,
            'class_id' => $request->class_id,
        ]);

        return $activeSession;

    }

    public function getdata()
    {

        $query = ActiveSession::with('company', 'branch', 'academicSession', 'class')
            ->orderBy('created_at', 'desc');

        // Apply filter based on logged-in user's company and branch
        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $query->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        $data = $query->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                            if (Gate::allows('ActiveSessions-edit')) {


                $btn .= '<a href="' . route("academic.active_sessions.edit", $row->id) . '" class="btn btn-primary btn-sm" style="margin-right: 4px;">Edit</a>';
                }
            if (Gate::allows('ActiveSessions-delete')) {

                $btn .= '<form method="POST" action="' . route("academic.active_sessions.destroy", $row->id) . '">';
                $btn .= '<button type="button" class="btn btn-danger btn-sm deleteBtn" data-id="' . $row->id . '" data-url="' . route("academic.active_sessions.destroy", $row->id) . '">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
            }

                $btn .= '</div>';

                return $btn;
            })
            ->addColumn('student_id', fn($row) => $row->student_id ?? "N/A")
            ->addColumn('company', fn($row) => $row->company?->name ?? "N/A")
            ->addColumn('academicSession', fn($row) => $row->academicSession
                ? $row->academicSession->name . ' ' . date('y', strtotime($row->academicSession->start_date)) . '-' . date('y', strtotime($row->academicSession->end_date))
                : "N/A")
            ->addColumn('branch', fn($row) => $row->branch?->name ?? "N/A")
            ->addColumn('class', fn($row) => $row->class?->name ?? "N/A")
            ->rawColumns(['action', 'company', 'branch', 'academicSession'])
            ->make(true);
    }


    public function update($request, $id)
    {

        $activeSession = ActiveSession::find($id);

        $activeSession->update([
            'session_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'company_id' => $request->company_id,
            'class_id' => $request->class_id,
        ]);
        return $activeSession;
    }

    public function destroy($id)
    {

        $activeSession = ActiveSession::find($id);
        if ($activeSession) {
            $activeSession->delete();
        }
    }
}
