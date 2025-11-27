<?php

namespace App\Http\Controllers\Academic;

use Illuminate\Http\Request;
use App\Models\Academic\Section;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use Yajra\DataTables\Facades\DataTables;


class AcadmicReportController extends Controller
{

      public function Studentstatus(Request $request)
    {

        if ($request->ajax()) {
            $query = Students::with('AcademicClass', 'section');
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', 1);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', 0);
                }
            }
            return Datatables::of($query)

                ->addColumn('name', function ($row) {
                    
                    return $row->full_name ;
                   
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
                ->addColumn('status', function ($row) {
                    if (isset($row->status)) {
                        return $row->status ? 'Active' : 'Inactive';
                    }
                    if (isset($row->is_active)) {
                        return $row->is_active ? 'Active' : 'Inactive';
                    }
                    return '-';
                })
                ->addIndexColumn()
                ->rawColumns(['name', 'class', 'section', 'status'])
                ->make(true);
        }

        // Non-AJAX: return the view
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
                $query = Students::with('AcademicClass', 'section', 'approvedBy')->where('is_active', 0);
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
                    ->addColumn('approve_by_name', function ($row) {
                        if($row->approved_by == null){
                            return '-';

                        }else{
                            return $row->approvedBy->name;
                        }
                        
                    })
                    
                    ->addColumn('status', function ($row) {
                        if (isset($row->status)) {
                            return $row->status ? 'Active' : 'Inactive';
                        }
                        if (isset($row->is_active)) {
                            return $row->is_active ? 'Active' : 'Inactive';
                        }
                        return '-';
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name', 'class', 'section', 'status', 'approve_by_name'])
                    ->make(true);
            }

            // Non-AJAX: return the view
            return view('acadmeic.reports.student_list_leave');
        }


}