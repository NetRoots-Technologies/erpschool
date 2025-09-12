<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\StudentAttendance;
use App\Models\Academic\StudentAttendanceData;
use App\Models\Academic\TimeTable;
use App\Models\Admin\Branch;
use App\Models\Admin\Branches;
use App\Models\Admin\Company;
use App\Models\Admin\Course;
use App\Models\Admin\CourseType;
use App\Models\Admin\FeeFactor;
use App\Models\Student\AcademicSession;
use App\Models\Student\Students;
use App\Services\StudentAttendanceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{
    protected $StudentAttendanceService;

    public function __construct(StudentAttendanceService $studentAttendanceService)
    {
        $this->StudentAttendanceService = $studentAttendanceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          if (!Gate::allows('StudentAttendance-list')) {
        abort(403);
    }
        $branches = Branches::all();
        return view('acadmeic.attendance.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('AttendanceReport-create')) {
            return abort(503);
        }
        $user = Auth::user();

        // Filtered Classes based on company/branch (optional)
        $classes = AcademicClass::where('status', 1)
            ->when($user->company_id, function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })
            ->when($user->branch_id, function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            })
            ->get();


        $formattedSessions = AcademicSession::where('status', 1)->get();

        $sessions = [];

        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }
        $branches = Branch::where('status', 1)->get();

        $companies = Company::where('status', 1)->get();

        return view('acadmeic.attendance.create', compact('classes', 'companies', 'sessions', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('AttendanceReport-create')) {
            return abort(503);
        }
        $attendanceDate = $request->input('attendance_date');

        $existingAttendance = StudentAttendance::where('branch_id', $request->branch_id)->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('attendance_date', $attendanceDate)->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Attendance for this date already exists.');
        }

        $this->StudentAttendanceService->store($request);
        return redirect()->route('academic.student_attendance.index')->with('success', 'Attendance assigned successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('AttendanceReport-edit')) {
            return abort(503);
        }
        $studentAttendance = StudentAttendance::with('AttendanceData.student')->find($id);
        $classes = AcademicClass::where('status', 1)->get();
        $formattedSessions = AcademicSession::where('status', 1)->get();

        $sessions = [];

        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }

        $branches = Branch::where('status', 1)->get();

        $companies = Company::where('status', 1)->get();

        return view('acadmeic.attendance.edit', compact('classes', 'companies', 'sessions', 'branches', 'studentAttendance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('AttendanceReport-edit')) {
            return abort(503);
        }
        $this->StudentAttendanceService->update($request, $id);
        return redirect()->route('academic.student_attendance.index')->with('success', 'Attendance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('AttendanceReport-delete')) {
            return abort(503);
        }
        $this->StudentAttendanceService->destroy($id);
        return redirect()->route('academic.student_attendance.index')->with('success', 'Attendance deleted successfully');
    }

    public function fetchStudent(Request $request)
    {

        $data = $request->all();
        $students = Students::where('section_id', $data['section_id'])->where('class_id', $data['class_id'])
            ->where('branch_id', $data['branch_id'])
            ->get();

        return $students;
    }

    public function studentData(Request $request)
    {

        $branch_id = $request->get('branch_id');
        $class_id = $request->get("class_id");
        $section_id = $request->get("section_id");

        $students = Students::where('section_id', $section_id)->where('class_id', $class_id)
            ->where('branch_id', $branch_id)->orderBy('student_id')
            ->get();


        return view('acadmeic.attendance.data', compact('students'));
    }

    public function handleBulkAction(Request $request)
    {
        
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $studentAttendance = StudentAttendance::find($id);
            if ($studentAttendance) {
                $studentAttendance->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    public function getData(Request $request)
    {
        
        if ($request->report_type === 'm') {
            return $this->StudentAttendanceService->getMonthlyData($request);
        }

        return $this->StudentAttendanceService->getdata($request);
    }

    public function generatePdf($id)
    {
        
        $attendance = StudentAttendance::with(['AttendanceData.student', 'AcademicClass', 'section'])->findOrFail($id);

        $Students = Students::where('class_id', $attendance->class_id)
            ->where('section_id', $attendance->section_id)->orderBy('student_id')
            ->get();

        $totalStudents = $Students->count();

        $presentStudents = StudentAttendance::where('class_id', $attendance->class_id)
            ->where('section_id', $attendance->section_id)
            ->where('attendance_date', $attendance->attendance_date)
            ->first();

        if ($presentStudents) {
            $presentStudentsCount = $presentStudents->attendanceData()
                ->where('attendance', 'P')
                ->count();
        } else {
            $presentStudentsCount = 0;
        }

        $absentStudents = $totalStudents - $presentStudentsCount;

        $pdf = PDF::loadView('acadmeic.attendance.pdf', compact('attendance', 'totalStudents', 'presentStudentsCount', 'absentStudents'));

        return $pdf->download('student_attendance_' . $id . '.pdf');
    }


    public function generateMonthlyPdf(Request $request)
    {
        
        $year = $request->year;
        $month = $request->month;
        $branch_id = $request->branch_id;
        $class_id = $request->class_id;
        $section_id = $request->section_id;

        // Get all attendance records for that month
        $attendanceRecords = StudentAttendance::with(['AttendanceData.student', 'AcademicClass', 'section'])
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->when($branch_id, fn($q) => $q->where('branch_id', $branch_id))
            ->when($class_id, fn($q) => $q->where('class_id', $class_id))
            ->when($section_id, fn($q) => $q->where('section_id', $section_id))
            ->orderBy('attendance_date', 'asc')
            ->get();

        // Get students
        $students = Students::where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->where('branch_id', $branch_id)
            ->orderBy('student_id')
            ->get();

        $totalStudents = $students->count();

        // Prepare date range for the month
        $dates = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = date('Y-m-d', mktime(0, 0, 0, $month, $d, $year));
            $dates[] = [
                'date' => $dateStr,
                'day_name' => date('D', strtotime($dateStr))
            ];
        }

        // Build attendance matrix
        $attendanceMatrix = [];
        $presentCount = $absentCount = $leaveCount = 0;

        foreach ($students as $student) {
            foreach ($dates as $dateInfo) {
                $attendanceMatrix[$student->id][$dateInfo['date']] = '';
            }
        }

        foreach ($attendanceRecords as $attendance) {
            foreach ($attendance->AttendanceData as $data) {
                $dateKey = date('Y-m-d', strtotime($attendance->attendance_date));
                $attendanceMatrix[$data->student_id][$dateKey] = $data->attendance;

                if ($data->attendance === 'P')
                    $presentCount++;
                elseif ($data->attendance === 'A')
                    $absentCount++;
                elseif ($data->attendance === 'L')
                    $leaveCount++;
            }
        }

        $monthName = date('F', mktime(0, 0, 0, $month, 1));

        // Generate PDF in landscape mode
        $pdf = Pdf::loadView('acadmeic.attendance.monthly-pdf', [
            'attendanceRecords' => $attendanceRecords,
            'students' => $students,
            'totalStudents' => $totalStudents,
            'presentCount' => $presentCount,
            'absentCount' => $absentCount,
            'leaveCount' => $leaveCount,
            'monthName' => $monthName,
            'year' => $year,
            'dates' => $dates,
            'attendanceMatrix' => $attendanceMatrix
        ])->setPaper('A4', 'landscape');

        return $pdf->download("monthly_attendance_{$year}_{$month}.pdf");
    }








    public function import(Request $request)
    {
        
        try {
            Excel::import(new AttendanceImport, $request->file('file'));
            if (session()->has('import_errors')) {
                $errors = session('import_errors');
                return redirect()->back()->withErrors($errors)->withInput();
            }
            return redirect()->back()->with('success', 'Attendance imported successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());

            return redirect()->back()->with('error', 'Failed to import attendance. Please check your file format.');
        }
    }

    public function monthyList(Request $request)
    {
        
        $data = StudentAttendance::selectRaw('
            branch_id,
            class_id,
            section_id,
            YEAR(attendance_date) as year,
            MONTH(attendance_date) as month,
            COUNT(*) as total_records
        ')
            ->groupBy('branch_id', 'class_id', 'section_id', 'year', 'month')
            ->with('branch', 'AcademicClass', 'section')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        foreach ($data as $row) {
            echo "Branch: " . ($row->branch->name ?? 'N/A') . "<br>";
            echo "Class: " . ($row->AcademicClass->name ?? 'N/A') . "<br>";
            echo "Section: " . ($row->section->name ?? 'N/A') . "<br>";
            echo "Month: " . date('F Y', mktime(0, 0, 0, $row->month, 1)) . "<br>";
            echo "Total Records: " . $row->total_records . "<hr>";
        }

        dd($data);
    }

}
