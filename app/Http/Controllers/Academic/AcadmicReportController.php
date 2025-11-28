<?php

namespace App\Http\Controllers\Academic;

use Illuminate\Http\Request;
use App\Models\Academic\Section;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AcadmicReportController extends Controller
{

    public function Studentstatus(Request $request)
    {
        if ($request->ajax()) {
            $query = Students::with('AcademicClass', 'section');

            // Status filter
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', 1);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', 0);
                }
            }

            // Class filter
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            return Datatables::of($query)
                ->addColumn('student_id', fn($row) => $row->student_id ?? '-')
                ->addColumn('name', fn($row) => $row->full_name ?? '-')
                ->addColumn('father_name', fn($row) => $row->father_name ?? '-')
                ->addColumn('class', fn($row) => $row->AcademicClass->name ?? '-')
                ->addColumn('section', fn($row) => $row->section->name ?? '-')
                ->addColumn('summary', function($row) {
                    if ($row->is_active) return 'On Roll';
                    if (!$row->is_active && isset($row->is_freeze) && $row->is_freeze) return 'Freeze';
                    return 'Left';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function($q) use ($search) {
                            $q->where('student_id', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('father_name', 'like', "%{$search}%");
                        });
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['name', 'class', 'section', 'summary'])
                ->make(true);
        }

        return view('acadmeic.reports.student_list');
    }


    public function StrengthSummaryCurrent(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('students as s')
                ->where('s.is_active', 1)
                ->leftJoin('classes as c', 's.class_id', '=', 'c.id')
                ->leftJoin('sections as sec', 's.section_id', '=', 'sec.id')
                ->leftJoin('acadmeic_sessions', 's.session_id', '=', 'acadmeic_sessions.id')
                ->select(
                    'c.id as class_id',
                    'c.name as class_name',
                    'sec.id as section_id',
                    'sec.name as section_name',
                    'acadmeic_sessions.name as session_name',
                    'acadmeic_sessions.id as acadmeic_session_id',
                    DB::raw('COUNT(s.id) as students_count')
                )
                ->groupBy('c.id', 'c.name', 'sec.id', 'sec.name' , 'acadmeic_sessions.name');
            if ($request->filled('class_id')) {
                $query->where('c.id', $request->class_id);
            }
            if ($request->filled('section_id')) {
                $query->where('sec.id', $request->section_id);
            }
            if ($request->filled('acadmeic_session_id')) {
                $query->where('acadmeic_sessions.id', $request->acadmeic_session_id);
            }

            return DataTables::of($query)
                ->addColumn('class', function ($row) {
                    return $row->class_name ?? '-';
                })
                ->addColumn('section', function ($row) {
                    return $row->section_name ?? '-';
                })
                ->addColumn('number_of_students', function ($row) {
                    return (int) $row->students_count;
                })
                ->addColumn('total', function ($row) {
                    return (int) $row->students_count;
                })

                ->addColumn('session_name', function ($row) {
                    return $row->session_name ?? '-';
                })
                ->filter (function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function($q) use ($search) {
                            $q->where('c.name', 'like', "%{$search}%")
                              ->orWhere('sec.name', 'like', "%{$search}%")
                              ->orWhere('acadmeic_sessions.name', 'like', "%{$search}%");
                        });
                    }
                })
                ->rawColumns(['class','section' ,'number_of_students', 'total' , 'session_name'])
                ->addIndexColumn()
                ->make(true);
        }

        $classes = AcademicClass::where('status', 1)->get();
        $sections = Section::where('status', 1)->get();
        $acadmeic_sessions = DB::table('acadmeic_sessions')->where('status', 1)->get();
        return view('acadmeic.reports.strength_summary_current' , compact('classes', 'sections' , 'acadmeic_sessions'));
    }


    public function StudentLeave(Request $request)
    {
        if ($request->ajax()) {
            $query = Students::with('AcademicClass', 'section', 'approvedBy')->where('is_active', 0); // Only inactive students

            // Filter by class
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            
            // Filter by section
            if ($request->filled('month')) {
                $query->where('leave_date', $request->month);
               
            }

            return Datatables::of($query)
                ->addColumn('name', function ($row) {
                    return $row->full_name ?? '';
                })
                ->addColumn('student_id', function ($row) {
                    return $row->student_id ?? '-';
                })
                ->addColumn('father_name', function ($row) {
                    return $row->father_name ?? '-';
                })
                ->addColumn('class', function ($row) {
                    return $row->AcademicClass->name ?? '-';
                })
                ->addColumn('section', function ($row) {
                    return $row->section->name ?? '-';
                })
                ->addColumn('leave_reason', function ($row) {
                    return $row->leave_reason ?? '-';
                })
                ->addColumn('approve_by_name', function ($row) {
                    return $row->approved_by ? $row->approvedBy->name : '-';
                })
                ->addColumn('leave_date', function ($row) {
                    return $row->leave_date ? Carbon::parse($row->leave_date)->format('d-m-Y') : '-';
                })

                ->addColumn('status', function ($row) {
                    return 'Inactive'; // Always inactive
                })
                 ->filter (function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function($q) use ($search) {
                            $q->where('student_id', 'like', "%{$search}%")
                              ->orWhere('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('father_name', 'like', "%{$search}%");
                        });
                    }
                })

                ->addIndexColumn()
                ->rawColumns(['name', 'class', 'section', 'status', 'approve_by_name'])
                ->make(true);
        }

        return view('acadmeic.reports.student_list_leave'); 
    }
    /**
     * Students consolidated by Month
     */
    public function studentsByMonth(Request $request)
    {
         if ($request->ajax()) {
            $month = $request->filled('month') ? intval($request->month) : Carbon::now()->month;
            $year  = $request->filled('year') ? intval($request->year) : Carbon::now()->year;

            $periodStart = Carbon::create($year, $month, 1)->startOfDay();
            $periodEnd   = (clone $periodStart)->endOfMonth()->endOfDay();

            // Student list: students admitted during the month
            $query = Students::with('academicClass', 'section')
                ->whereBetween(DB::raw('DATE(admission_date)'), [$periodStart->toDateString(), $periodEnd->toDateString()]);

            return datatables()->of($query)
                ->addColumn('student_id', fn($r)=> $r->student_id)
                ->addColumn('name', fn($r)=> $r->full_name ?? $r->name ?? '')
                ->addColumn('father_name', fn($r)=> $r->father_name)
                ->filter (function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function($q) use ($search) {
                            $q->where('student_id', 'like', "%{$search}%")
                              ->orWhere('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('father_name', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('class_section', fn($r)=> (optional($r->academicClass)->name ?? '-') . ' / ' . (optional($r->section)->name ?? '-'))
                ->make(true);
        }

        // Non-AJAX: show blade (month picker + table + summary)
        return view('acadmeic.reports.students_by_month');
    }

    /**
     * Students consolidated by Year
     */
    public function studentsByYear(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->filled('year') ? intval($request->year) : Carbon::now()->year;

            $periodStart = Carbon::create($year, 1, 1)->startOfDay();
            $periodEnd   = Carbon::create($year, 12, 31)->endOfDay();

            // Student list: admitted in that year
            $query = Students::with('academicClass', 'section')
                ->whereYear('admission_date', $year);

            return datatables()->of($query)
                ->addColumn('student_id', fn($r)=> $r->student_id)
                ->addColumn('name', fn($r)=> $r->full_name ?? $r->name ?? '')
                ->addColumn('father_name', fn($r)=> $r->father_name)
                ->filter (function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function($q) use ($search) {
                            $q->where('student_id', 'like', "%{$search}%")
                              ->orWhere('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('father_name', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('class_section', fn($r)=> (optional($r->academicClass)->name ?? '-') . ' / ' . (optional($r->section)->name ?? '-'))
                ->make(true);
        }

        return view('acadmeic.reports.students_by_year');
    }

    /**
     * Students consolidated by Term (Bi-annual)
     */
    public function studentsByTerm(Request $request)
    {
        if ($request->ajax()) {
            $term = $request->filled('term') ? intval($request->term) : 1;
            $year = $request->filled('year') ? intval($request->year) : Carbon::now()->year;

            if ($term === 1) {
                $periodStart = Carbon::create($year, 1, 1)->startOfDay();
                $periodEnd   = Carbon::create($year, 6, 30)->endOfDay();
            } else {
                $periodStart = Carbon::create($year, 7, 1)->startOfDay();
                $periodEnd   = Carbon::create($year, 12, 31)->endOfDay();
            }

            // Students admitted within term
            $query = Students::with('academicClass', 'section')
                ->whereBetween(DB::raw('DATE(admission_date)'), [$periodStart->toDateString(), $periodEnd->toDateString()]);

            return datatables()->of($query)
                ->addColumn('student_id', fn($r)=> $r->student_id)
                ->addColumn('name', fn($r)=> $r->full_name ?? $r->name ?? '')
                ->addColumn('father_name', fn($r)=> $r->father_name)
                ->filter (function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function($q) use ($search) {
                            $q->where('student_id', 'like', "%{$search}%")
                              ->orWhere('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('father_name', 'like', "%{$search}%");
                        });
                    }
                })
                ->addColumn('class_section', fn($r)=> (optional($r->academicClass)->name ?? '-') . ' / ' . (optional($r->section)->name ?? '-'))
                ->make(true);
        }

        return view('acadmeic.reports.students_by_term');
    }

    /**
     * Counts summary endpoint.
     * Accepts start_date & end_date (YYYY-MM-DD). Returns array of { class_id, class_name, opening, closing }.
     */
    public function countsSummary(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end   = $request->filled('end_date') ? Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        $counts = $this->countsByClassForPeriod($start, $end);

        // Fetch class names
        $classIds = array_keys($counts);
        $classes = DB::table('classes')->whereIn('id', $classIds)->get()->keyBy('id');
        $out = [];
        foreach ($counts as $cid => $data) {
            $out[] = [
                'class_id' => $cid,
                'class_name' => $classes[$cid]->name ?? ('Class ' . $cid),
                'opening' => $data['opening'],
                'closing' => $data['closing'],
            ];
        }

        return response()->json($out);
    }
      protected function countsByClassForPeriod(Carbon $periodStart, Carbon $periodEnd)
    {
        // Opening counts: admitted on/before periodStart AND (is_active = 1 OR inactive after periodStart)
        $opening = Students::select('class_id', DB::raw('COUNT(*) as opening_count'))
            ->whereDate('admission_date', '<=', $periodStart->toDateString())
            ->where(function($q) {
                $q->where('is_active', 1); // consider active students
            })
            ->groupBy('class_id')
            ->get()
            ->keyBy('class_id')
            ->map(fn($r) => (int) $r->opening_count)
            ->toArray();

        // Closing counts: admitted on/before periodEnd AND is_active = 1
        $closing = Students::select('class_id', DB::raw('COUNT(*) as closing_count'))
            ->whereDate('admission_date', '<=', $periodEnd->toDateString())
            ->where('is_active', 1)
            ->groupBy('class_id')
            ->get()
            ->keyBy('class_id')
            ->map(fn($r) => (int) $r->closing_count)
            ->toArray();

        $classIds = array_unique(array_merge(array_keys($opening), array_keys($closing)));

        $result = [];
        foreach ($classIds as $cid) {
            $result[$cid] = [
                'opening' => $opening[$cid] ?? 0,
                'closing' => $closing[$cid] ?? 0,
            ];
        }

        return $result;
    }



}