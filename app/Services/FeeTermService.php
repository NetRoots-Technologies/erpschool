<?php

namespace App\Services;

use App\Models\Fee\FeeTerm;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class FeeTermService
{
    public function store($request)
    {
        if (!Gate::allows('FeeTerm-create')) {
            return abort(403);
        }

        $input = $request->all();
        $input['created_by'] = Auth::id();
        $input['company_id'] = Auth::user()->company_id ?? $request->company_id;
        $input['branch_id'] = Auth::user()->branch_id ?? $request->branch_id;

        $feeTerm = FeeTerm::create($input);

        return response()->json(['message' => 'Fee term created successfully', 'data' => $feeTerm]);
    }

    public function getdata()
    {
        if (!Gate::allows('FeeTerm-list')) {
            return abort(403);
        }
        
        $query = FeeTerm::with(['company', 'branch', 'createdBy'])
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
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('description', function ($row) {
                return $row->description ?? "N/A";
            })
            ->addColumn('start_date', function ($row) {
                return $row->start_date ? $row->start_date->format('Y-m-d') : "N/A";
            })
            ->addColumn('end_date', function ($row) {
                return $row->end_date ? $row->end_date->format('Y-m-d') : "N/A";
            })
            ->addColumn('due_date', function ($row) {
                return $row->due_date ? $row->due_date->format('Y-m-d') : "N/A";
            })
            ->addColumn('company', function ($row) {
                return $row->company ? $row->company->name : "N/A";
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
                $btn = '<form class="delete_form" data-route="' . route("fee.fee-terms.destroy", $row->id) . '" id="fee-term-' . $row->id . '" method="POST">';

                if (Gate::allows('FeeTerm-edit')) {
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm fee-term-edit" data-fee-term-edit=\'' . $row . '\'>Edit</a>';
                }

                if (Gate::allows('FeeTerm-delete')) {
                    $btn .= ' <button data-id="fee-term-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm">Delete</button>';
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
        return FeeTerm::with(['company', 'branch'])->find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('FeeTerm-edit')) {
            return abort(403);
        }
        
        $feeTerm = FeeTerm::find($id);
        $input = $request->all();
        $input['updated_by'] = Auth::id();
        $feeTerm->update($input);

        return response()->json(['message' => 'Fee term updated successfully', 'data' => $feeTerm]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('FeeTerm-delete')) {
            return abort(403);
        }
        
        $feeTerm = FeeTerm::findOrFail($id);
        if ($feeTerm) {
            $feeTerm->delete();
        }

        return response()->json(['message' => 'Fee term deleted successfully']);
    }

    public function changeStatus($request)
    {
        $feeTerm = FeeTerm::find($request->id);
        if ($feeTerm) {
            $feeTerm->status = ($request->status == 'active') ? 1 : 0;
            $feeTerm->save();
            return $feeTerm;
        }
    }
}
