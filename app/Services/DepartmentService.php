<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Admin\Department;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class DepartmentService
{


    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        // return Permission::with('child')->where('main', 1)->get();
        return Company::where('status', 1)->get();
    }

    public function company($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $html = "<option value='' selected disabled>Select Branch</option> ";


        // return Permission::with('child')->where('main', 1)->get();
        $branches = Branch::where('status', 1)->where('company_id', $request->id)->get();
        foreach ($branches as $item) {
            $html .= "<option  value='" . $item->id . "'>" . $item->name . "</option> ";
        }
        return $html;
    }

    public function getdepartments(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $html = "<option value='' selected disabled>Select Department</option>";

        // Filter by both branch_id and company_id
        if ($request->branch_id && $request->company_id) {

            $departments = Department::where('status', 1)
                ->where('branch_id', $request->branch_id)
                ->where('company_id', $request->company_id)
                ->get();

            foreach ($departments as $item) {
                $html .= "<option value='" . $item->id . "'>" . $item->name . "</option>";
            }
        }

        return $html;
    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        //dd($request->all());
        $main = $request->input('main');
        $categoryIds = $request->input('selectCategory'); // This is an array

        $department = new Department();
        $department->category_id = json_encode($categoryIds); // 🔥 Encode array as JSON string
        $department->company_id = $request->input('company_id');
        $department->branch_id = $request->input('branch_id');
        $department->name = $request->input('name');

        if ($main == 0) {
            $department->parent_id = $request->input('parent_id');
        }

        $department->save();

        return $department;
    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Department::with('branch.company')->OrderBy('created_at', 'desc');

        //To Check Department show only of his company and branch

        if (Auth::check()) {
            $company_id = Auth::user()->company_id;
            $branch_id = Auth::user()->branch_id;
            if (!is_null($company_id)) {
                $data->where('company_id', $company_id);
            }

            // if (!is_null($branch_id)) {
            //     $data->where('branch_id', $branch_id);
            // }
             foreach(Auth::user()->roles()->get() as $role){
            if($role->name != 'Admin'){
                if (!is_null($branch_id)) {
                $data->where('branch_id', );
            }
            }
        }

        }

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('company', function ($row) {
                if ($row->branch) {
                    if ($row->branch->company)
                        return $row->branch->company->name;
                    else
                        return "N/A";

                } else {
                    return "N/A";
                }


            })->addColumn('branch', function ($row) {
                if ($row->branch)
                    return $row->branch->name;
                else
                    return "N/A";

            })
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('action', function ($row) {


                $btn = ' <form class="delete_form" data-route="' . route("admin.departments.destroy", $row->id) . '"   id="department-' . $row->id . '"  method="POST"> ';
                //                if (Gate::allows('branches-edit'))
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm department_edit"  data-department-edit=\'' . $row . '\'>Edit</a>';


                //                if (Gate::allows('branches-delete'))
                $btn = $btn . ' <button data-id="department-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
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
        return Department::find($id);
    }


    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $categoryIds = $request->input('selectCategory');
        $department = Department::find($id);
        $department->category_id = $categoryIds;
        $department->company_id = $request->input('company_id');
        $department->branch_id = $request->input('branch_id');
        $department->name = $request->input('name');
        //$department->parent_id=$request->input('parent_id');
        $department->save();
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Department = Department::findOrFail($id);
        if ($Department)
            $Department->delete();
    }


    public function changeStatus($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $department = Department::find($request->id);
        if ($department) {
            $department->status = ($request->status == 'active') ? 1 : 0;
            $department->save();
            return $department;
        }
    }
}
