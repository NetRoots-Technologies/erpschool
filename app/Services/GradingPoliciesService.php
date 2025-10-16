<?php

namespace App\Services;

use App\Models\Exam\GradingPolicies;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GradingPoliciesService
{
    public function store($request)
    {

        $logs = [
            'user_id' => Auth::user()->id ?? 'N/A',
            'old_acadmeic_session_id' => 'N/A',
            'old_class_id' => 'N/A',
            'old_grade' => 'N/A',
            'old_marks_range' => 'N/A',
            'old_marks_from' => 'N/A',
            'old_marks_to' => 'N/A',
            'old_description' => 'N/A',
            'old_status' => 'N/A',
            'date_time' => now()->toDateTimeString(),
        ];
        GradingPolicies::create([
            'acadmeic_session_id' => $request->acadmeic_session_id,
            'class_id' => $request->class_id,
            'grade' => $request->grade,
            'marks_range' => $request->marks_range,
            'marks_from' => $request->marks_from,
            'marks_to' => $request->marks_to,
            'description' => $request->description,
            'status' => 1,
            'logs' => json_encode([$logs])
        ]);
    }

    public function getdata()
    {

        $data = GradingPolicies::with('academic_session', 'academic_class')->orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('academic_session', function ($row) {
                return $row->academic_session->name;
            })
            ->addColumn('academic_class', function ($row) {
                return $row->academic_class->name ?? 'N/A';
            })
            ->addColumn('grade', function ($row) {
                return $row->grade;
            })
            ->addColumn('marks_range', function ($row) {
                return $row->marks_range;
            })
            ->addColumn('marks_from', function ($row) {
                return $row->marks_from;
            })
            ->addColumn('marks_to', function ($row) {
                return $row->marks_to;
            })
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 0)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("exam.grading_policies.destroy", $row->id) . '"   id="grading_policies_' . $row->id . '"  method="POST"> ';
                if (Gate::allows('GradingPolicies-edit')) {
                    $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm grading_policies_edit" data-grading_policies_edit=\'' . $row . '\'>Edit</a>';
                    $btn = $btn . method_field('DELETE') . '' . csrf_field();
                    $btn = $btn . ' </form>';
                }

                return $btn;
            })
            ->rawColumns(['academic_session', 'academic_class', 'grade', 'marks_range', 'marks_from', 'marks_to', 'description', 'action', 'status'])
            ->make(true);
    }

    public function update($request, $id, $image = null)
    {

        $grading_policies = GradingPolicies::find($id);
        if ($grading_policies) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_acadmeic_session_id' => $grading_policies->acadmeic_session_id,
                'old_class_id' => $grading_policies->class_id,
                'old_grade' => $grading_policies->grade,
                'old_marks_range' => $grading_policies->marks_range,
                'old_marks_from' => $grading_policies->marks_from,
                'old_marks_to' => $grading_policies->marks_to,
                'old_description' => $grading_policies->description,
                'old_status' => $grading_policies->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($grading_policies->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $grading_policies->acadmeic_session_id = $request->acadmeic_session_id;
            $grading_policies->class_id = $request->class_id;
            $grading_policies->grade = $request->grade;
            $grading_policies->marks_range = $request->marks_range;
            $grading_policies->marks_from = $request->marks_from;
            $grading_policies->marks_to = $request->marks_to;
            $grading_policies->description = $request->description;
            $grading_policies->logs = json_encode($old_logs);

            $grading_policies->save();
            return $grading_policies;
        }
        return null;
    }

    public function destroy($id) {}

    public function changeStatus($request)
    {

        $grading_policies = GradingPolicies::find($request->id);
        if ($grading_policies) {
            $current_logs = [
                'user_id' => Auth::user()->id,
                'old_acadmeic_session_id' => $grading_policies->acadmeic_session_id,
                'old_class_id' => $grading_policies->class_id,
                'old_grade' => $grading_policies->grade,
                'old_marks_range' => $grading_policies->marks_range,
                'old_marks_from' => $grading_policies->marks_from,
                'old_marks_to' => $grading_policies->marks_to,
                'old_description' => $grading_policies->description,
                'old_status' => $grading_policies->status,
                'date_time' => now()->toDateTimeString()
            ];
            $old_logs = json_decode($grading_policies->logs, true) ?? [];
            $old_logs[] = $current_logs;
            $grading_policies->logs = json_encode($old_logs);
            $grading_policies->status = $grading_policies->status == 1 ? 0 : 1;
            $grading_policies->save();
            return $grading_policies;
        }
        return null;
    }
}
