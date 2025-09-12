<?php

namespace App\Services;


use App\Models\Admin\FeeSection;
use Yajra\DataTables\DataTables;
use App\Models\Admin\FeeCategory;
use Illuminate\Support\Facades\Gate;

class FeeCategoryService
{
    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $category = $request->input('category');
        $fa_percentage = $request->input('fa%');
        $session_id = $request->get('session_id');
        $company_id = $request->get('company_id');
        $branch_id = $request->get('branch_id');
        //        $class_id = $request->get('class_id');

        //        $existingFeeCategory = FeeCategory::
//            where('session_id', $session_id)
//            ->where('company_id', $company_id)
//            ->where('branch_id', $branch_id)
////            ->where('class_id', $class_id)
//            ->first();
//
//        if ($existingFeeCategory) {
//            return 'A fee category with the same attributes already exists.';
//        }

        $full_fee = $request->has('full_fee') ? 1 : 0;
        $fa_checkbox = $request->has('FA') ? 1 : 0;
        $feeCategory = FeeCategory::create([
            'category' => $category,
            'fa_percent' => $fa_percentage,
            'full_fee' => $full_fee,
            'FA' => $fa_checkbox,
            'session_id' => $session_id,
            'company_id' => $company_id,
            'branch_id' => $branch_id,
            //            'class_id' => $class_id,
        ]);

        return null;
    }


    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeCategory = FeeCategory::find($id);
        if ($feeCategory) {
            $feeCategory->delete();
        }
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeCategory = FeeCategory::find($id);

        $category = $request->input('category');
        $fa_percentage = $request->input('fa');

        $full_fee = $request->has('full_fee') ? 1 : 0;
        $fa_checkbox = $request->has('FA') ? 1 : 0;
        $feeCategory->update([
            'category' => $category,
            'fa_percent' => $fa_percentage,
            'full_fee' => $full_fee,
            'FA' => $fa_checkbox,
            'session_id' => $feeCategory->session_id,
            'company_id' => $feeCategory->company_id,
            'branch_id' => $feeCategory->branch_id,
            //            'class_id' => $request->get('class_id'),
        ]);

        return $feeCategory;
    }

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = FeeCategory::orderBy('created_at', 'desc')->get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div style="display: flex;">';

                //                if (Gate::allows('Employee-edit'))
                $btn .= '<a href="' . route("admin.fee-category.edit", $row->id) . '" class="btn btn-primary btn-sm"  style="margin-right: 4px;">Edit</a>';

                //                if (Gate::allows('Employee-destroy')) {
                $btn .= '<form method="POST" onsubmit="return confirm(\'Are you sure you want to Delete this?\');" action="' . route("admin.fee-category.destroy", $row->id) . '">';
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" style="margin-right: 4px;">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                //                }
                $btn .= '</div>';

                return $btn;

            })->addColumn('active', function ($row) {
                $statusButton = ($row->active == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('full_fee', function ($row) {
                if ($row->full_fee == '1') {
                    return 'Yes';
                } else {
                    return "No";
                }
            })
            ->addColumn('FA', function ($row) {
                if ($row->FA == '1') {
                    return 'Yes';
                } else {
                    return "No";
                }
            })->addColumn('fa_percent', function ($row) {
                if ($row->fa_percent) {
                    return $row->fa_percent . '%';
                } else {
                    return "No";
                }
            })
            ->rawColumns(['action', 'full_fee', 'active', 'FA', 'fa_percent'])
            ->make(true);
    }

    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $feeCategory = FeeCategory::find($request->id);
        if ($feeCategory) {
            $feeCategory->active = ($request->status == 'active') ? 1 : 0;
            $feeCategory->save();
            return $feeCategory;
        }
    }

}
