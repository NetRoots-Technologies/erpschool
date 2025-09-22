<?php

namespace App\Services;

use App\Models\Academic\AcademicClass;
use App\Models\Academic\SchoolType;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use Illuminate\Support\Facades\Auth;


class ClassService
{

    public function store($request)
    {

        //dd($request->all());
        $academic = AcademicClass::create([
            'name' => $request->name,
            'school_type_id' => $request->school_id,
            'branch_id' => $request->branch_id,
            'session_id' => $request->session_id,
            'company_id' => $request->company_id,
        ]);

    }


    public function getdata()
    {

        $query = AcademicClass::with('school', 'branch')->orderBy('created_at', 'desc');

        if (Auth::check()) {
            $user = Auth::user();


            if (!is_null($user->company_id)) {
                $query->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        return Datatables::of($query)->addIndexColumn()
            ->addColumn('school', function ($row) {
                return $row->school ? $row->school->name : "N/A";
            })
            ->addColumn('branch', function ($row) {
                return $row->branch ? $row->branch->name : "N/A";
            })
            ->addColumn('status', function ($row) {
                return ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("academic.classes.destroy", $row->id) . '" id="class-' . $row->id . '" method="POST">';

                if (Gate::allows('Class-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm classes_edit" data-class-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('Class-delete')) {
                    $btn .= ' <button data-id="branch-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                }

                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action', 'status', 'school', 'branch'])
            ->make(true);
    }

    public function edit($id)
    {

        return AcademicClass::find($id);


    }


    public function update($request, $id)
    {

        $data = AcademicClass::find($id);
        $input = $request->all();
        $data->update($input);
    }

    public function destroy($id)
    {

        $class = AcademicClass::findOrFail($id);
        if ($class)
            $class->delete();
    }

    public function changeStatus($request)
    {

        $class = AcademicClass::find($request->id);
        if ($class) {
            $class->status = ($request->status == 'active') ? 1 : 0;
            $class->save();
            return $class;
        }
    }
}

