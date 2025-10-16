<?php

namespace App\Services;


use App\Models\Exam\ExamTerm;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class ExamTermService
{
    public function store($request)
    {
        
        //dd($request->all());
        ExamTerm::create([
            'session_id' => $request->get('session_id'),
            'branch_id' => $request->get('branch_id'),
            'term_id' => $request->get('term_id'),
            'progress_heading' => $request->get('progress_heading'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'issue_date' => $request->get('issue_date'),
            'term_desc' => $request->get('term_desc'),
            'total_month' => $request->get('total_month'),
            'coordinator_1' => $request->get('coordinator_1'),
            'staff_id_1' => $request->get('staff_id_1'),
            'coordinator_2' => $request->get('coordinator_2'),
            'staff_id_2' => $request->get('staff_id_2'),
            'coordinator_3' => $request->get('coordinator_3'),
            'staff_id_3' => $request->get('staff_id_3'),
            'coordinator_4' => $request->get('coordinator_4'),
            'staff_id_4' => $request->get('staff_id_4'),
        ]);
    }

    public function getData()
    {
        
        $data = ExamTerm::with('AcademicSession', 'branch')->orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

            if (Gate::allows('ExamTerms-edit')){
                $btn .= '<a href="' . route("exam.exam_terms.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
              }
                if (Gate::allows('ExamTerms-delete')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("exam.exam_terms.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                $btn .= '</div>';
                 }
                return $btn;

            })->addColumn('active', function ($row) {
                $statusButton = ($row->active == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('AcademicSession', function ($row) {
                if ($row->AcademicSession) {
                    return $row->AcademicSession->name;
                } else {
                    return "N/A";
                }
            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'AcademicSession', 'branch'])
            ->make(true);
    }

    public function update($request, $id)
    {
        
        $examTerm = ExamTerm::find($id);
        $examTerm->update([
            'session_id' => $request->get('session_id'),
            'branch_id' => $request->get('branch_id'),
            'term_id' => $request->get('term_id'),
            'progress_heading' => $request->get('progress_heading'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'issue_date' => $request->get('issue_date'),
            'term_desc' => $request->get('term_desc'),
            'total_month' => $request->get('total_month'),
            'coordinator_1' => $request->get('coordinator_1'),
            'staff_id_1' => $request->get('staff_id_1'),
            'coordinator_2' => $request->get('coordinator_2'),
            'staff_id_2' => $request->get('staff_id_2'),
            'coordinator_3' => $request->get('coordinator_3'),
            'staff_id_3' => $request->get('staff_id_3'),
            'coordinator_4' => $request->get('coordinator_4'),
            'staff_id_4' => $request->get('staff_id_4'),
        ]);
    }

    public function destroy($id)
    {
        
        $examTerm = ExamTerm::find($id);
        if ($examTerm) {
            $examTerm->delete();
        }
    }
}

