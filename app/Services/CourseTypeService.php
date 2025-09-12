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

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    public function apiindex()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Role::all();

    }

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //return Permission::with('child')->where('main', 1)->get();

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $coursetype = CourseType::create(['name' => $request->name, 'description' => $request->description]);
    }



    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
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
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm course_type_edit"  data-course_type-edit=\'' . $row . '\'>Edit</a>';
                $btn = $btn . ' <button data-id="branch-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm btnDelete"" data-id="'. $row->id .'" data-url="'. route("academic.subject-type.destroy", $row->id) .'" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }




    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Role::find($id);


    }

    public function AllowedPermissions($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where(['role_has_permissions.role_id' => $id])
            ->get()->pluck('name', 'id');

    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = CourseType::find($id);
        $input = $request->all();
        $data->update($input);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $course_type = CourseType::findOrFail($id);

        if ($course_type)
            $course_type->delete();

    }


    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $coursetype = CourseType::find($request->id);
        if ($coursetype) {
            $coursetype->status = ($request->status == 'active') ? 1 : 0;
            $coursetype->save();
            return $coursetype;
        }
    }

    public function sync()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
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
        if (!Gate::allows('students')) {
            return abort(503);
        }
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
