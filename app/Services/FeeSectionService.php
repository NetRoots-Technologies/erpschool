<?php

namespace App\Services;

use App\Models\Admin\FeeSection;
use App\Models\HRM\Employees;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeSectionService
{

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $feeSection = FeeSection::create([
            'branch_id' => $request->branch_id,
            'print_section' => $request->print_section,
            'name' => $request->name,
        ]);

    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $query = FeeSection::with('branch')->orderBy('created_at', 'desc');
        if (Auth::check()) {
            $user = Auth::user();
            if (!is_null($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        $data = $query->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("admin.fee-sections.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("admin.fee-sections.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                //                }
                $btn .= '</div>';

                return $btn;

            })
            ->addColumn('branch', function ($row) {


                if ($row->branch) {
                    return $row->branch->name;

                } else {
                    return "N/A";
                }


            })->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->rawColumns(['action', 'branch', 'status'])
            ->make(true);

    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeSection = FeeSection::find($id);
        $feeSection->update([
            'branch_id' => $request->branch_id,
            'print_section' => $request->print_section,
            'name' => $request->name,
        ]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeSection = FeeSection::findOrFail($id);
        if ($feeSection)
            $feeSection->delete();
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeSection = FeeSection::find($request->id);
        if ($feeSection) {
            $feeSection->status = ($request->status == 'active') ? 1 : 0;
            $feeSection->save();
            return $feeSection;
        }
    }

}
