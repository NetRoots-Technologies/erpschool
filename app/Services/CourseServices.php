<?php

namespace App\Services;

use App\Models\Admin\Course;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class CourseServices
{

    public function index()
    {
       
        return Course::all();
    }


    public function create()
    {
       

    }

    public function store($request)
    {
       
        $coursetype = Course::create([
            'name' => $request->name,
            'subject_code' => $request->subject_code,
            'course_type_id' => $request->course_type_id,
            'class_id' => $request->class_id,
            'branch_id' => $request->branch_id,
            'company_id' => $request->company_id,
            'session_id' => $request->session_id,
            'active_session_id' => $request->active_session_id,
        ]);

    }

    public function user_deactive($id)
    {
       
        $user = User::find($id);
        if ($user) {
            $user->active = 0;
            $user->save();
        }
    }


    public function user_active($id)
    {
       
        $user = User::find($id);
        if ($user) {
            $user->active = 1;
            $user->save();
        }

    }


    public function getdata()
    {
       
        $data = Course::with('courseType', 'company', 'branch')->orderBy('created_at', 'desc');
        
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     if (!is_null($user->company_id)) {
        //         $data->where('company_id', $user->company_id);
        //     }
        //     if (!is_null($user->branch_id)) {
        //         $data->where('branch_id', $user->branch_id);
        //     }
        // }

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                if(auth()->user()->can('Subjects-edit')){
                $btn .= '<a href="' . route("academic.subjects.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                }

                if(auth()->user()->can('Subjects-delete')){
                  $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("academic.subjects.destroy", $row->id) . '">';
                $btn .= '<button type="button" class="btn btn-danger btn-sm deleteBtn"
                data-id="' . $row->id . '"
                data-url="' . route("academic.subjects.destroy", $row->id) . '"
                style="margin-right: 4px;">
                Delete
                </button>';
                $btn .= '<button type="button" class="btn btn-info clone-btn btn-sm" style="margin-right: 4px;" data-id='.$row->id.'>Clone</button>';

                $btn .= '</form>';


                $btn .= '</div>';  
                }
                

                return $btn;

            })
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('courseType', function ($row) {


                if ($row->courseType) {
                    return $row->courseType->name;

                } else {
                    return "N/A";
                }
            })
            ->addColumn('company', function ($row) {


                if ($row->company) {
                    return $row->company->name;

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
            ->rawColumns(['action', 'courseType', 'status'])
            ->make(true);
    }


    public function edit($id)
    {
       
        return Agent::find($id);


    }

    public function update($request, $id)
    {
       
        $data = Course::find($id);
        $input = $request->all();
        $data->update($input);

    }

    public function destroy($id)
    {
       
        $course = Course::findOrFail($id);

        if ($course)
            $course->delete();

    }

    public function changeStatus($request)
    {
       
        $coursetype = Course::find($request->id);
        if ($coursetype) {
            $coursetype->status = ($request->status == 'active') ? 1 : 0;
            $coursetype->save();
            return $coursetype;
        }
    }

}


