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

    // public function getData()
    // {
    //      if (!Gate::allows('ViewStudents-list')) {
    //     return abort(403);
    // }
    //     $data = Students::with('AcademicClass', 'branch', 'student_siblings', 'student_schools', 'student_emergency_contacts')->orderBy('created_at', 'desc')->get();

    //     return Datatables::of($data)->addIndexColumn()
    //         ->addColumn('action', function ($row) {
    //             $btn = '<div style="display: flex;">';

    //             $btn .= '<a href="' . route("academic.student_view.show", $row->id) . '" class="btn btn-warning btn-sm"  style="margin-right: 4px;">View</a>';

    //             $btn .= '</div>';

    //             return $btn;

    //         })->addColumn('name', function ($row) {

    //             if ($row->first_name && $row->last_name) {
    //                 return $row->first_name . '&nbsp' . $row->last_name;
    //             }
    //         })->addColumn('student_id', function ($row) {
    //             return $row->student_id;
    //         })->addColumn('campus', function ($row) {

    //             if ($row->branch) {
    //                 return $row->branch->name;
    //             }
    //         })->addColumn('campus', function ($row) {

    //             if ($row->branch) {
    //                 return $row->branch->name;
    //             }
    //         })->addColumn('AcademicClass', function ($row) {

    //             if ($row->AcademicClass) {
    //                 return $row->AcademicClass->name;
    //             }
    //         })
    //         ->rawColumns(['action', 'name', 'campus', 'AcademicClass'])
    //         ->make(true);


    // }

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

    // Return to DataTables (no get() yet)
    return Datatables::of($query)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            return '<div style="display: flex;">
                        <a href="' . route("academic.student_view.show", $row->id) . '" class="btn btn-warning btn-sm" style="margin-right:4px;">View</a>
                    </div>';
        })
        ->addColumn('name', function ($row) {
            return $row->first_name && $row->last_name ? $row->first_name . ' ' . $row->last_name : '';
        })
        ->addColumn('student_id', fn($row) => $row->student_id)
        ->addColumn('campus', fn($row) => $row->branch->name ?? '')
        ->addColumn('AcademicClass', fn($row) => $row->AcademicClass->name ?? '')
        ->rawColumns(['action', 'name', 'campus', 'AcademicClass'])
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

