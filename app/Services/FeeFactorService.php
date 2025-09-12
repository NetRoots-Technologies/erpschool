<?php

namespace App\Services;

use App\Models\Admin\Company;
use App\Models\Admin\FeeFactor;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;


class FeeFactorService
{


    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeFactor = FeeFactor::create(['name' => $request->name]);

    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = FeeFactor::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()

            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("admin.fee-factor.destroy", $row->id) . '"   id="fee-factor-' . $row->id . '"  method="POST"> ';
                // if (Gate::allows('company-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm fee_factor_edit"  data-fee-factor-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('company-delete'))
                $btn = $btn . ' <button data-id="company-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
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
        return FeeFactor::find($id);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeFactor = FeeFactor::find($id);
        $feeFactor->name = $request->name;
        $feeFactor->save();
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeFactor = FeeFactor::findOrFail($id);
        if ($feeFactor)
            $feeFactor->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeFactor = FeeFactor::find($request->id);
        if ($feeFactor) {
            $feeFactor->status = ($request->status == 'active') ? 1 : 0;
            $feeFactor->save();
            return $feeFactor;
        }
    }
}
