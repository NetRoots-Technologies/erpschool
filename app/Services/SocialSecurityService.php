<?php

namespace App\Services;


use App\Models\HR\SocialSecurity;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class SocialSecurityService
{


    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        foreach ($request->get('employee_id') as $key => $employee) {
            $SocialSecurity = SocialSecurity::firstOrCreate(['employee_id' => $employee]);

            $SocialSecurity->employee_id = $employee;
            $SocialSecurity->branch_id = $request->get('branch_id');
            $SocialSecurity->percentage = $request->get('percentage')[$key];

            $SocialSecurity->save();
        }

    }


    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = SocialSecurity::with('branch', 'employee')->get();
        //            dd($data);
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("hr.social-security.destroy", $row->id) . '"> ';

                $btn = $btn . '<a href="' . route("hr.social-security.edit", $row->id) . '" class="btn btn-primary  ml-2 mr-2 btn-sm">Edit</a>';

                $btn = $btn . ' <button  type="submit" class="btn btn-danger btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })->addColumn('employee', function ($row) {
                if ($row->employee) {
                    return $row->employee->name;
                } else {
                    return 'N/A';
                }
            })->addColumn('branch', function ($row) {
                if ($row->branch) {
                    return $row->branch->name;
                } else {
                    return 'N/A';
                }
            })

            ->rawColumns(['action', 'branch', 'employee'])
            ->make(true);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $social = SocialSecurity::find($id);

        $socialSecurity = [
            'branch_id' => $request->branch_id,
            'employee_id' => $request->employee_id,
            'percentage' => $request->percentage,
        ];

        $socialSecurity->update($social);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $social = SocialSecurity::find($id);
        if ($social) {
            $social->delete();
        }
    }

}
