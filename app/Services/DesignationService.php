<?php

namespace App\Services;


use App\Models\HR\Designation;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class DesignationService
{

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $designation = Designation::create(['name' => $request->name, 'department_id' => $request->selectDepartment]);
    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Designation::orderby('id', 'DESC');

        return Datatables::of($data)->addIndexColumn()

            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("hr.designations.destroy", $row->id) . '"   id="designation-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm designation_edit"  data-designation-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="designation-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Designation::find($id);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Designation::find($id);
        $data->name = $request->name;
        $data->department_id = $request->selectDepartment;
        $data->update();
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Designation = Designation::findOrFail($id);
        if ($Designation)
            $Designation->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $designation = Designation::find($request->id);
        if ($designation) {
            $designation->status = ($request->status == 'active') ? 1 : 0;
            $designation->save();
            return $designation;
        }
    }

}

