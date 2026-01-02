<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class StudentViewController extends Controller
{
    public function index()
    {
         if (!Gate::allows('ViewStudents-list')) {
        return abort(403);
    }
        $user = Auth::user();

        // Get branches (you can also apply condition here if needed)
        $branches = Branch::where('status', 1)
            ->when($user->company_id, fn($q) => $q->where('company_id', $user->company_id))
            ->get();


        $classes = AcademicClass::where('status', 1)
            ->when($user->company_id, fn($q) => $q->where('company_id', $user->company_id))
            ->when($user->branch_id, fn($q) => $q->where('branch_id', $user->branch_id))
            ->get();

        //$classes = AcademicClass::all();
        return view('acadmeic.view_students.index', compact('branches', 'classes'));
    }


    public function getData(Request $request)
    {
        if (!Gate::allows('ViewStudents-list')) {
            return abort(403);
        }

        // Start query
        $query = Students::with('AcademicClass', 'branch', 'student_siblings', 'student_schools', 'student_emergency_contacts');

        // Apply campus filter
        if ($request->filled('campus')) {
            $query->whereHas('branch', function($q) use ($request) {
                $q->where('id', $request->campus);
            });
        }

        // Apply class filter
        if ($request->filled('academic_class')) {
            $query->whereHas('AcademicClass', function($q) use ($request) {
                $q->where('id', $request->academic_class);
            });
        }
        return Datatables::of($query)
            ->addIndexColumn()

            ->addColumn('name', function ($row) {
                return trim($row->first_name . ' ' . $row->last_name);
            })

            ->addColumn('campus', fn($row) => $row->branch->name ?? '')
            ->addColumn('AcademicClass', fn($row) => $row->AcademicClass->name ?? '')

            // 🔍 SEARCH FIXES
            ->filterColumn('name', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })

            ->filterColumn('campus', function ($query, $keyword) {
                $query->whereHas('branch', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            ->filterColumn('AcademicClass', function ($query, $keyword) {
                $query->whereHas('AcademicClass', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('action', function ($row) {
                return '<a href="' . route("academic.student_view.show", $row->id) . '" 
                        class="btn btn-warning btn-sm">View</a>';
            })

            ->rawColumns(['action'])
            ->make(true);

        }


    public function show($id)
    {

 if (!Gate::allows('ViewStudents-list')) {
        return abort(403);
    }
        $student = Students::with('student_schools', 'studentPictures', 'student_emergency_contacts', 'AcademicClass')->find($id);
        $siblings = Students::with('student_schools', 'studentPictures', 'student_emergency_contacts', 'AcademicClass')->where('father_cnic', $student->father_cnic)->get();
        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();

        $imageUrls = [
            'passport_photos' => $student->studentPictures->passport_photos ?? null,
            'birth_certificate' => $student->studentPictures->birth_certificate ?? null,
            'school_leaving_certificate' => $student->studentPictures->school_leaving_certificate ?? null,
            'guardian_document' => $student->studentPictures->guardian_document ?? null,
        ];

        // dd($student->studentPictures->passport_photos ?? null);
        return view('acadmeic.view_students.show', compact('branches', 'student', 'siblings', 'companies', 'imageUrls'));
    }



    public function generatePdf(Request $request)
    {

        ini_set('max_execution_time', '300');
        $studentId = $request->query('student_id');

        $student = Students::with('student_siblings', 'student_schools', 'studentPictures', 'student_emergency_contacts', 'AcademicClass')->find($studentId);

        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $students = Students::all();

        $imageUrls = [
            'passport_photos' => $student->studentPictures->passport_photos ?? null,
            'birth_certificate' => $student->studentPictures->birth_certificate ?? null,
            'school_leaving_certificate' => $student->studentPictures->school_leaving_certificate ?? null,
            'guardian_document' => $student->studentPictures->guardian_document ?? null,
        ];


        $pdf = Pdf::loadView('acadmeic.view_students.pdf', [
            'student' => $student,
            'branches' => $branches,
            'companies' => $companies,
            'students' => $students,
            'imageUrls' => $imageUrls
        ]);

        return $pdf->download();




        //        return $pdf->download('student_report.pdf');
    }

}

