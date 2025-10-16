<?php

namespace App\Services;

use App\Models\Exam\SubjectMarks;
use Carbon\Carbon;
use App\Models\Exam\ExamDetail;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class ExamDetailService
{

    public function store(\Symfony\Component\HttpFoundation\Request $request)
    {


        // basic validation (adjust rules to your database tables if you want 'exists:...')
        $request->validate([
            'branchId' => 'required',
            'classId' => 'required',
            'test_name' => 'required|string|max:255',
            'rows' => 'required|array|min:1',
            'rows.*.subject_id' => 'required',
            'rows.*.total_marks' => 'required|numeric',
            'rows.*.passing_percentage' => 'required|numeric',
        ]);

        $testType = $request->testTypeId ?? null;
        $examType = $request->examTypeId ?? null;
        // dd(Auth::id());
        DB::beginTransaction();
        try {
            $detail = ExamDetail::create([
                'test_type_id' => $testType,
                'class_id' => $request->classId,
                'exam_term_id' => $examType,
                'initial' => $request->initial,
                'test_name' => $request->test_name,
                'user_id' => Auth::id()
            ]);

            foreach ($request->rows as $row) {
                SubjectMarks::create([
                    'exam_detail_id' => $detail->id,
                    'class_id' => $request->classId,
                    'course_id' => $row['subject_id'],
                    'totalMarks' => $row['total_marks'],
                    'passingPercentage' => $row['passing_percentage'],
                    'showGrade' => isset($row['show_grade']) ? 1 : 0,
                    'showPercentage' => isset($row['show_percentage']) ? 1 : 0,
                    'passOrFail' => isset($row['show_pass_fail']) ? 1 : 0,
                ]);
            }

            DB::commit();

            return redirect()->route('exam.exam_details.index')
                ->with('success', 'Exam details created successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            // log error if you want: \Log::error($e);
            return back()->withInput()->withErrors(['error' => 'Failed to create exam details: ' . $e->getMessage()]);
        }
    }

    public function getData()
    {

        $data = $data = ExamDetail::with(['testType', 'examType', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();



        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                if (Gate::allows('ExamDetails-list')) {
                    $btn .= '<a href="' . route("exam.exam_details.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">View</a>';
                }

                if (Gate::allows('ExamDetails-list')) {
                    $btn .= '<form method="POST"  action="' . route("exam.exam_details.destroy", $row->id) . '">';
                    $btn .= '<button type="button" 
                        data-id="' . $row->id . '" 
                        class="btn btn-danger btn-sm delete-btn" 
                        style="margin-right: 4px;">Delete</button>';
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
            ->addColumn('createdBy', function ($row) {
                return $row->user->name;
            })
            ->addColumn('testType', function ($row) {
                if ($row->testType) {
                    return $row->testType->name; // adjust field name
                } elseif ($row->examType) {
                    return $row->examType->progress_heading; // adjust field name
                }
                return '-';
            })
            ->addColumn('created_at', function ($row) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('d-M-Y h:i A');
                return $formatedDate;
            })
            ->rawColumns(['action', 'testType', 'created_at'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {


        $examDetail = ExamDetail::findOrFail($id);

        // Update exam detail info
        $examDetail->update([
            'test_name' => $request->test_name,
            'initial' => $request->initial,
        ]);

        // Handle subjects/marks
        if ($request->has('rows')) {
            foreach ($request->rows as $row) {
                if (isset($row['id'])) {
                    // Update existing record
                    $subjectMark = SubjectMarks::find($row['id']);
                    if ($subjectMark) {
                        $subjectMark->update([
                            'course_id' => $row['course_id'],
                            'total_marks' => $row['total_marks'],
                            'passing_marks' => $row['passing_marks'],
                            'attendance' => isset($row['attendance']) ? 1 : 0,
                            'mcq' => isset($row['mcq']) ? 1 : 0,
                            'subjective' => isset($row['subjective']) ? 1 : 0,
                            'practical' => isset($row['practical']) ? 1 : 0,
                        ]);
                    }
                } else {
                    // Create new subject mark
                    SubjectMarks::create([
                        'exam_detail_id' => $examDetail->id,
                        'course_id' => $row['course_id'],
                        'total_marks' => $row['total_marks'],
                        'passing_marks' => $row['passing_marks'],
                        'attendance' => isset($row['attendance']) ? 1 : 0,
                        'mcq' => isset($row['mcq']) ? 1 : 0,
                        'subjective' => isset($row['subjective']) ? 1 : 0,
                        'practical' => isset($row['practical']) ? 1 : 0,
                    ]);
                }
            }
        }

        return redirect()->route('exam.exam_details.index')
            ->with('success', 'Exam detail updated successfully.');
    }


    public function destroy($id)
    {

        $examDetail = ExamDetail::find($id);
        if ($examDetail) {
            $examDetail->delete();
        }
    }
}
