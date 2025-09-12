<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\HR\Teacher;
use Illuminate\Support\Facades\Gate;



class TeacherServices
{

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Teacher::all();
    }



    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent = new Teacher();
        $agent->name = $request->input('name');
        $agent->email = $request->input('email');
        $agent->address = $request->input('address');
        $agent->mobile = $request->input('mobile');
        $agent->salary = $request->input('salary');
        $fileNameToStore = null;
        if ($request->hasfile('image')) {

            $file = $request->file('image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'teacher_files';
            $file->move($destinationPath, $fileNameToStore);
            $agent->image = $destinationPath . '/' . $fileNameToStore;
        }
        $agent->save();

    }

    //    public function user_deactive($id)
//    {
//        $user = User::find($id);
//        if ($user) {
//            $user->active = 0;
//            $user->save();
//        }
//    }
//
//
//    public function user_active($id)
//    {
//        $user = User::find($id);
//        if ($user) {
//            $user->active = 1;
//            $user->save();
//        }
//
//    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Teacher::select('id', 'name', 'email', 'address', 'mobile', 'salary', 'status')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {

                return ($row->status == 1) ? 'Active' : 'Inactive';

            })

            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("hr.teacher.destroy", $row->id) . '"   id="Teacher-' . $row->id . '"  method="POST"> ';

                $btn = $btn . '<a  data-id="' . $row->id . '"  class="btn btn-primary  btn-sm teacher_edit"  data-teacher-edit=\'' . $row . '\'>Edit</a>';

                $btn = $btn . ' <button data-id="Teacher-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Teacher::find($id);


    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $agent = Teacher::find($request->id);
        $agent->name = $request->input('name');
        $agent->email = $request->input('email');
        $agent->address = $request->input('address');
        $agent->mobile = $request->input('mobile');
        $agent->salary = $request->input('salary');
        $fileNameToStore = null;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'teacher_files';
            $file->move($destinationPath, $fileNameToStore);
            $agent->image = $destinationPath . '/' . $fileNameToStore;
        }

        $agent->save();

    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $teacher = Teacher::findOrFail($id);
        if ($teacher)
            $teacher->delete();

    }
}

