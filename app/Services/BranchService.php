<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\SchoolTypeBranch;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;


class BranchService
{

    public function index()
    {
        
    }


    public function store($request)
    {
        
        //dd($request->all());
        $schoolIds = $request->get('selectSchool');
        $branch = Branch::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'ip_config' => $request->ip_config,
            'port' => $request->port,
            'address' => $request->address,
            'branch_code' => $request->branch_code,
            //            'emp_branch_code' => $request->emp_branch_code
            'emp_branch_code' => $request->branch_code
        ]);

        if ($schoolIds) {
            foreach ($schoolIds as $key => $schoolType) {
                SchoolTypeBranch::create([
                    'school_type_id' => $schoolType,
                    'branch_id' => $branch->id
                ]);
            }
        }

        return $branch;
    }


    public function getdata()
    {
        
        
        $data = Branch::with('company', 'schoolBranch')->OrderBy('created_at', 'desc');

        //Get Auth
        if (Auth::check()) {
            $company_id = Auth::user()->company_id;
            if (!is_null($company_id)) {
                $data->where('company_id', $company_id);
            }
        }

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('company', function ($row) {
                if ($row->company)
                    return $row->company->name;
                else
                    return "N/A";

            })
            ->addColumn('status', function ($row) {
                $statusButton = ($row->status == 1)
                    ? '<button type="button" class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="inactive">Active</button>'
                    : '<button type="button" class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="active">Inactive</button>';

                return $statusButton;
            })
            ->addColumn('sync_Data', function ($row) {

                $syncbtn = '<button type="button" class="btn btn-success btn-sm sync_data" data-id="' . $row->id . '" data-status="inactive">Sync Data</button>';


                return $syncbtn;
            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("admin.branches.destroy", $row->id) . '"   id="branche-' . $row->id . '"  method="POST"> ';
                if (auth()->user()->can('Branches-create')){
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm branches_edit"  data-branch-edit=\'' . $row . '\'>Edit</a>';

                }

                if (auth()->user()->can('Branches-delete')){
                    $btn = $btn . ' <button data-id="branch-' . $row->id . '" type="submit" class="btn btn-danger delete btn-sm "" >Delete</button>';
                    $btn = $btn . method_field('DELETE') . '' . csrf_field();
                    $btn = $btn . ' </form>';
                }
                
                return $btn;
            })
            ->rawColumns(['action', 'status', 'sync_Data'])
            ->make(true);
    }

    public function edit($id)
    {
        
        return Branch::find($id);
    }


    public function update($request, $id)
    {
        
        $branch = Branch::find($id);

        if ($request->has('selectSchool')) {
            $branch->schoolBranch()->delete();

            foreach ($request->get('selectSchool') as $key => $schoolType) {
                SchoolTypeBranch::create([
                    'school_type_id' => $schoolType,
                    'branch_id' => $branch->id
                ]);
            }
        }

        $branch->update([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'ip_config' => $request->ip_config,
            'port' => $request->port,
            'address' => $request->address,
            'branch_code' => $request->branch_code,
            //            'emp_branch_code' => $request->emp_branch_code,
            'emp_branch_code' => $request->branch_code,
        ]);
    }

    public function destroy($id)
    {
        
        $Branch = Branch::findOrFail($id);
        if ($Branch) {
            $Branch->schoolBranch()->delete();

            $Branch->delete();
        }
    }

    public function changeStatus($request)
    {
        
        $branch = Branch::find($request->id);
        if ($branch) {
            $branch->status = ($request->status == 'active') ? 1 : 0;
            $branch->save();
            return $branch;
        }
    }
}

