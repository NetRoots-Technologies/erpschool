<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\sync;
use App\Models\Permission;

//use Spatie\Permission\Models\Permission;
use App\Models\Admin\Course;
use App\Models\Academic\Section;
use App\Models\Admin\CourseType;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;


class CourseTypeService
{

    public function index() {}

    public function apiindex()
    {

        return Role::all();
    }

    public function create()
    {

        //return Permission::with('child')->where('main', 1)->get();

    }

    public function store($request)
    {

        $coursetype = CourseType::create(['name' => $request->name, 'description' => $request->description]);
    }



    public function getdata()
    {

        $data = CourseType::latest()->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })

            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("academic.subject-type.destroy", $row->id) . '"   id="courseType-' . $row->id . '"  method="POST"> ';
                if (auth()->user()->can('SubjectType-edit')) {
                    $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm course_type_edit"  data-course_type-edit=\'' . $row . '\'>Edit</a>';
                }

                if (auth()->user()->can('SubjectType-delete')) {
                    $btn = $btn . ' <button data-id="branch-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm btnDelete"" data-id="' . $row->id . '" data-url="' . route("academic.subject-type.destroy", $row->id) . '" >Delete</button>';
                    $btn = $btn . method_field('DELETE') . '' . csrf_field();
                    $btn = $btn . ' </form>';
                }

                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }




    public function edit($id)
    {

        return Role::find($id);
    }

    public function AllowedPermissions($id)
    {

        return Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where(['role_has_permissions.role_id' => $id])
            ->get()->pluck('name', 'id');
    }

    public function update($request, $id)
    {

        $data = CourseType::find($id);
        $input = $request->all();
        $data->update($input);
    }

    public function destroy($id)
    {

        $course_type = CourseType::findOrFail($id);

        if ($course_type)
            $course_type->delete();
    }


    public function changeStatus($request)
    {

        $coursetype = CourseType::find($request->id);
        if ($coursetype) {
            $coursetype->status = ($request->status == 'active') ? 1 : 0;
            $coursetype->save();
            return $coursetype;
        }
    }

    public function sync()
    {

        $sync = sync::find(1);
        if ($sync) {
        } else {
            $sync = new sync();
            $sync->data = 212;
            $sync->user = 212;
            $sync->save();
        }
        $sync->generateCode();
    }

    public function syncdb()
    {

        $sync = sync::find(1);
        if ($sync) {
        } else {
            $sync = new sync();
            $sync->data = 212;
            $sync->user = 212;
            $sync->save();
        }
        $sync->resetdb();
    }
}
