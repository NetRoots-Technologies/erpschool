<?php

namespace App\Services;

use App\Models\Fee\FeeStructure;
use App\Models\Fee\FeeCategory;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class FeeStructureService
{

    public function store($request)
    {
        if (!Gate::allows('FeeStructure-create')) {
            return abort(403);
        }

        $input = $request->all();
        $input['created_by'] = Auth::id();
        $input['company_id'] = Auth::user()->company_id ?? $request->company_id;
        $input['branch_id'] = Auth::user()->branch_id ?? $request->branch_id;

        $feeStructure = FeeStructure::create($input);

        return response()->json(['message' => 'Fee structure created successfully', 'data' => $feeStructure]);
    }

    public function getdata()
    {
        if (!Gate::allows('FeeStructure-list')) {
            return abort(403);
        }
        
        $query = FeeStructure::with(['feeCategory', 'company', 'branch', 'academicSession', 'academicClass', 'createdBy'])
            ->orderBy('created_at', 'desc');

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
            ->addColumn('fee_category', function ($row) {
                return $row->feeCategory ? $row->feeCategory->name : "N/A";
            })
            ->addColumn('class', function ($row) {
                return $row->academicClass ? $row->academicClass->name : "N/A";
            })
            ->addColumn('session', function ($row) {
                return $row->academicSession ? $row->academicSession->name : "N/A";
            })
            ->addColumn('company', function ($row) {
                return $row->company ? $row->company->name : "N/A";
            })
            ->addColumn('branch', function ($row) {
                return $row->branch ? $row->branch->name : "N/A";
            })
            ->addColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })
            ->addColumn('status', function ($row) {
                return ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("fee.fee-structures.destroy", $row->id) . '" id="fee-structure-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeStructure-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-structure-edit" data-fee-structure-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('FeeStructure-delete')) {
                    $btn .= ' <button data-id="fee-structure-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                }

                $btn .= '</form>';
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function edit($id)
    {
        return FeeStructure::with(['feeCategory', 'company', 'branch', 'academicSession', 'classModel'])->find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('FeeStructure-edit')) {
            return abort(403);
        }
        
        $feeStructure = FeeStructure::find($id);
        $input = $request->all();
        $input['updated_by'] = Auth::id();
        $feeStructure->update($input);

        return response()->json(['message' => 'Fee structure updated successfully', 'data' => $feeStructure]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeStructure-delete')) {
            return abort(403);
        }
        
        $feeStructure = FeeStructure::findOrFail($id);
        if ($feeStructure) {
            $feeStructure->delete();
        }

        return response()->json(['message' => 'Fee structure deleted successfully']);
    }

    public function changeStatus($request)
    {
        $feeStructure = FeeStructure::find($request->id);
        if ($feeStructure) {
            $feeStructure->status = ($request->status == 'active') ? 1 : 0;
            $feeStructure->save();
            return $feeStructure;
        }
    }

    public function bulkDelete($ids)
    {
        if (!Gate::allows('FeeStructure-delete')) {
            return abort(403);
        }
        
        FeeStructure::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk delete completed successfully']);
    }
}
