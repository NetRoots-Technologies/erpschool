<?php

namespace App\Services;


use App\Models\Admin\Bank;
use Illuminate\Support\Str;
use App\Models\Admin\Company;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class BankService
{
    public function store($request)
    {
        return Bank::create(['name' => Str::upper($request->name)]);
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Bank::orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("admin.banks.destroy", $row->id) . '" id="bank-' . $row->id . '" method="POST">';
                // if (Gate::allows('Bank-edit'))
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm bank_edit" data-bank-edit=\'' . $row . '\'>Edit</a>';

                // if (Gate::allows('Bank-delete'))
                $btn .= ' <button type="button" class="btn btn-danger delete btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
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
        return Bank::find($id);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Bank = Bank::find($id);
        $Bank->name = $request->name;
        $Bank->save();
    }


    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Bank = Bank::findOrFail($id);
        if ($Bank)
            $Bank->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Bank = Bank::find($request->id);
        if ($Bank) {
            $Bank->status = ($request->status == 'active') ? 1 : 0;
            $Bank->save();
            return $Bank;
        }
    }

}
