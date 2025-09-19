<?php

namespace App\Services;


use App\Models\HR\Allowance;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class AllowanceService
{

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $allowance = new Allowance();
        $allowance->type = $request->get('type');
        $allowance->save();
        return $allowance;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Allowance::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("hr.allowances.destroy", $row->id) . '"   id="allowance-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary  btn-sm allowance_edit"  data-allowance-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="allowance-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Allowance::find($id);
        $input = $request->all();
        $data->update($input);
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Allowance = Allowance::findOrFail($id);
        if ($Allowance)
            $Allowance->delete();
    }


    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Allowance = Allowance::find($request->id);
        if ($Allowance) {
            $Allowance->status = ($request->status == 'active') ? 1 : 0;
            $Allowance->save();
            return $Allowance;
        }
    }
}

