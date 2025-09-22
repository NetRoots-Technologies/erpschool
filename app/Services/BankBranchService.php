<?php

namespace App\Services;

use App\Models\Admin\BankBranch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Gate;

class BankBranchService
{
    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankBranch = new BankBranch();
        $bankBranch->bank_id = $request->input('bank_id');
        $bankBranch->branch_code = $request->input('branch_code');
        $bankBranch->branch_name = $request->input('branch_name');

        $bankBranch->save();
        
        return $bankBranch;
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BankBranch::with('bank')->orderby('id', 'DESC');
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("admin.banks_branches.destroy", $row->id) . '" id="bank-' . $row->id . '" method="POST">';
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm bank_branch_edit" data-bank_branch-edit=\'' . $row . '\'>Edit</a>';
                $btn .= ' <button type="button" class="btn btn-danger delete btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                return $btn;
            })

            ->addColumn('bank', function ($row) {
                if ($row->bank)
                    return $row->bank->name;
                else
                    return "N/A";

            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return BankBranch::find($id);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankBranch = BankBranch::find($id);
        $bankBranch->bank_id = $request->input('bank_id');
        $bankBranch->branch_code = $request->input('branch_code');
        $bankBranch->branch_name = $request->input('branch_name');


        $bankBranch->save();
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankBranch = BankBranch::findOrFail($id);
        if ($bankBranch)
            $bankBranch->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bankBranch = BankBranch::find($request->id);
        if ($bankBranch) {
            $bankBranch->status = ($request->status == 'active') ? 1 : 0;
            $bankBranch->save();
            return $bankBranch;
        }
    }
}


