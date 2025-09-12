<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\HR\Designation;
use App\Models\HRM\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (!Gate::allows('Teachers-list')) {
        return abort(403); // 403 forbidden better hai 503 se
    }
        return view('acadmeic.teachers.index');
    }


    public function getData()
    {
        if (!Gate::allows('Teachers-list')) {
        return abort(403);
    }
        $designations = Designation::where('name', 'Teacher')->pluck('id')->toArray();

        if (!empty($designations)) {
            $teacherData = [];

            foreach ($designations as $designationId) {
                $teachers = Employees::where('designation_id', $designationId)->get();
                foreach ($teachers as $teacher) {
                    $teacherData[] = [
                        'teacher_name' => $teacher->name,
                        'specialization_subject' => $teacher->specialization_subject,
                        'action' => '<button>Edit</button>',
                    ];
                }
            }
            return DataTables::of($teacherData)->make(true);
        } else {
            return DataTables::of([])->make(true);
        }
    }




}
