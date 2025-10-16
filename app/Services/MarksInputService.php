<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Exam\MarkEntry;
use App\Models\Exam\MarkInput;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MarksInputService
{
    public function store($request)
    {


        // $validated = $request->validate([
        //     'company_id' => 'required',
        //     'sessions_id' => 'required',
        //     'branch_id' => 'required',
        //     'class_id' => 'required',
        //     'section_id' => 'required',
        //     'course_id' => 'required',
        //     'component_id' => 'required',
        //     'sub_component_id' => 'required',
        //     'allocated_marks' => 'required|array',
        //     'allocated_marks.*' => 'nullable|numeric|min:0',
        //     'max_marks' => 'required|array',
        //     'max_marks.*' => 'nullable|numeric|min:0',
        // ]);


        // dd($request->all() , $validated, "io");
        // dd($request);

        // try {
        // DB::beginTransaction();

        // Step 1: Create mark_input record
        $markInput = MarkInput::create([
            'company_id' => $request->company_id,
            'acadmeic_sessions_id' => $request->session_id,
            'branch_id' => $request->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'course_id' => $request->course_id,
            'component_id' => $request->component_id,
            'sub_component_id' => $request->sub_component_id,
        ]);

        // dd($markInput);

        // Step 2: Insert marks for each student
        foreach ($request->allocated_marks as $studentId => $marks) {
            $max = $request->max_marks[$studentId] ?? null;

            if ($marks === null || $max === null) {
                continue;
            }

            if ($marks > $max) {
                throw new \Exception("Marks for student {$studentId} exceed max allowed ({$max}).");
            }

            $entry = MarkEntry::create([
                'mark_input_id' => $markInput->id,
                'student_id' => $studentId,
                'max_marks' => $max,
                'allocated_marks' => $marks,
            ]);

            \Log::info("Inserted MarkEntry", $entry->toArray());
        }

        // DB::commit();
        return redirect()->back()->with('success', 'Marks saved successfully!');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error("Error saving marks: " . $e->getMessage());
        //     return back()->withErrors(['error' => 'Failed to save: ' . $e->getMessage()]);
        // }
    }


    public function update(Request $request, $id)
    {

        $marksInput = MarkInput::find($id);
        $marksInput->company_id = $request->company_id;
        $marksInput->session_id = $request->session_id;
        $marksInput->branch_id = $request->branch_id;
        $marksInput->class_id = $request->class_id;
        $marksInput->section_id = $request->section_id;
        $marksInput->subject_id = $request->subject_id;
        $marksInput->component_id = $request->component_id;
        $marksInput->sub_component_id = $request->sub_component_id;
        $marksInput->save();
    }

    public function getData()
    {

        $data = MarkInput::with(['academicSession', 'company', 'branch', 'section', 'fetchClass', 'subject', 'component'])->orderBy('created_at', 'desc');

        return DataTables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';
                if (Gate::allows('MarksInput-edit')) {
                }
                $btn .= '<a href="' . route("exam.marks_input.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';
                if (Gate::allows('MarksInput-delete')) {
                    $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("exam.marks_input.destroy", $row->id) . '">';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                    $btn .= '</form>';
                }
                $btn .= '</div>';

                return $btn;
            })
            ->addColumn('active', function ($row) {
                $statusButton = ($row->active == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('company', function ($row) {
                if ($row->company) {
                    return $row->company->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('Session', function ($row) {
                // dd($row,$row->academicSession);
                if ($row->academicSession) {
                    return $row->academicSession->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('section', function ($row) {
                if ($row->section) {
                    return $row->section->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('class', function ($row) {
                if ($row->fetchClass) {
                    return $row->fetchClass->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('subject', function ($row) {
                if ($row->subject) {
                    return $row->subject->name;
                } else {
                    return "N/A";
                }
            })
            ->addColumn('component', function ($row) {
                if ($row->component) {
                    return $row->component->name;
                } else {
                    return "N/A";
                }
            })
            ->rawColumns(['action', 'Session', 'branch', 'company', 'section', 'class', 'subject', 'component'])
            ->make(true);
    }

    public function delete($id)
    {

        $marksInput = MarkInput::find($id);
        if ($marksInput) {
            $marksInput->delete();
            return redirect()->back()->with('success', 'Marks Input Deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Record not found');
        }
    }
}
