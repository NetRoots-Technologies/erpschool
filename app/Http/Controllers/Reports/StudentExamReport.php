<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Branch;
use App\Models\Academic\AcademicClass; 
use App\Models\Academic\Section;
use App\Models\Student\Students;
use App\Models\Admin\Course;
use App\Models\Exam\EffortLevel;
use App\Models\Exam\SkillType;
use DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Academic\ActiveSession;



class StudentExamReport extends Controller
{
   public function index()
    {
        $branches = Branch::select('id','name')->orderBy('name')->get();
        return view('reports.students.exam_report', compact('branches'));
    }

    public function getAcademicSession(Request $request){
        return  ActiveSession::join('acadmeic_sessions', 'active_sessions.session_id', '=', 'acadmeic_sessions.id')
            ->select('acadmeic_sessions.id','acadmeic_sessions.name')
            ->where('active_sessions.branch_id', $request->branch_id)
            ->groupBy('active_sessions.session_id')
            ->orderBy('acadmeic_sessions.name')
            ->get();
// return
            // dd($as);
    }

    public function getClasses(Request $request)
    {
        // dd($request->all());
        // $request->validate(['branch_id' => 'required|integer']);
        // return as simple array [{id, name}, ...]
        return  AcademicClass::select('id','name')
            ->where('session_id', $request->academic_id)
            ->orderBy('name')
            ->get();

        // dd($ac);
    }

    public function getSections(Request $request)
    {
        $request->validate(['class_id' => 'required|integer']);
        return Section::select('id','name')
            ->where('class_id', $request->class_id)
            ->orderBy('name')
            ->get();
    }

    public function studentsTable(Request $request)
    {
        // If any filter is missing → send empty table
        if (!$request->filled(['branch_id','class_id','section_id'])) {
            return DataTables::of(collect([]))->make(true);
        }

        $q = Students::with(['branch:id,name','class:id,name','section:id,name'])
            ->where('branch_id', $request->branch_id)
            ->where('class_id',   $request->class_id)
            ->where('section_id', $request->section_id)
            ->select(['id','first_name','last_name','branch_id','class_id','section_id']); // adjust cols if needed
            return DataTables::of($q)
            ->addColumn('name', fn($r) => trim(($r->first_name ?? '').' '.($r->last_name ?? '')) ?: '-')
            ->addColumn('branch', fn($r) => $r->branch->name ?? '-')
            ->addColumn('class',  fn($r) => $r->class->name ?? '-')
            ->addColumn('section',fn($r) => $r->section->name ?? '-')
            ->addColumn('action', function($r){
                // change to your route if you want a detailed view
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary view-student" data-id="'.$r->id.'">View</a>';
            })
            ->rawColumns(['action', 'name' , 'branch' , 'class' , 'section'])
            ->make(true);
    }

    
        public function viewReport($student_id)
        {
            // dd("d");
             $student = Students::with(['branch','class','section.session','studentPictures'])->findOrFail($student_id);
             $efforts = EffortLevel::where('student_id', $student_id)->get()->groupBy('student_id');    
             $skills = SkillType::with([
                    'subject.EvolutionKeySkills' => function ($q) use ($student_id) {
                        $q->where('student_id', $student_id)   // Student ka filter sirf yahan
                        ->with('key');      // Related keys ko load karo
                    },
                    'group',
                    'skill'
                ])
                ->where('class_id', $student->class_id)
                ->whereHas('subject.EvolutionKeySkills', function ($q) use ($student_id) {
                    $q->where('student_id', $student_id);
                })
            ->get()
            ->groupBy('subject_id');


            // dd($skills , $student_id , request()->all() , $student , $efforts);
           $pdf = Pdf::loadView('reports.students.report_card', compact('student','efforts','skills'))
              ->setOptions([
                  'isRemoteEnabled' => true,
                  'defaultFont' => 'DejaVu Sans'
              ]);

        return $pdf->download('report-card-'.$student->id.'.pdf');

        }


}


