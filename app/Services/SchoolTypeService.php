<?php

namespace App\Services;

use App\Models\Academic\SchoolType;
use App\Models\Admin\Company;
use Config;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Academic\SchoolTypeBranch;
class SchoolTypeService
{

    public function store($request)
    {

        $schooltype = SchoolType::create(['company_id' => $request->company_id, 'branch_id' => $request->branch_id, 'name' => $request->name]);
        SchoolTypeBranch::create(['branch_id' => $request->branch_id, 'school_type_id' => $schooltype->id]);
    }
    //,'company_id' => $request->company_id,'branch_id' => $request->branch_id

    public function getdata()
    {

        $query = SchoolType::with('company', 'branch')->orderBy('id', 'DESC');

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $query->where('company_id', $user->company_id);
            }


            if (!is_null($user->branch_id)) {
                $query->where('branch_id', $user->branch_id);
            }
        }

        return DataTables::of($query)->addIndexColumn()
            ->addColumn('status', function ($row) {
                return ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" data-route="' . route("academic.schools.destroy", $row->id) . '" id="school-' . $row->id . '" method="POST">';
                if (Gate::allows('SchoolType-edit')) {

                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary text-white btn-sm school_edit" data-school-edit=\'' . $row . '\'>Edit</a>';
                   }
                if (Gate::allows('SchoolType-delete')) {
                $btn .= '<button data-id="school-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                }
                return $btn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function edit($id)
    {

        return SchoolType::find($id);
    }


    public function update($request, $id)
    {

        $schoolType = SchoolType::findOrFail($id);

        // Update SchoolType
        $schoolType->update([
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'name' => $request->name,
        ]);

        // Update or create the SchoolTypeBranch entry
        SchoolTypeBranch::updateOrCreate(
            [
                'school_type_id' => $schoolType->id,
            ],
            [
                'branch_id' => $request->branch_id,
            ]
        );
    }

    public function destroy($id)
    {
        
        $school = SchoolType::findOrFail($id);
        if ($school)
            $school->delete();
    }

    public function changeStatus($request)
    {

        $school = SchoolType::find($request->id);
        if ($school) {
            $school->status = ($request->status == 'active') ? 1 : 0;
            $school->save();
            return $school;
        }
    }
}

