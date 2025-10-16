<?php

namespace App\Services;

use Config;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use DataTables;

class PermissionServices
{

    public function index()
    {
       
        $data = Permission::select('id', 'name')->get();
    }

    public function apiindex()
    {
       
        return Permission::all();
    }

    public function create()
    {
       
    }

    public function store($request)
    {
        if (!Gate::allows('Permissions-create')) {
            return abort(503);
        }

        $data = Permission::create([
            'name' => $request->name,
            'main' => $request->main ?? 0,
            'parent_id' => $request->parent_id ?? 0,
        ]);

        return $data;
    }

    public function edit($id)
    {
        if (!Gate::allows('Permissions-edit')) {
            return abort(503);
        }
        return Permission::find($id);
    }

    public function update($request, $id)
    {
        if (!Gate::allows('Permissions-edit')) {
            return abort(503);
        }

        $data = Permission::find($id);
        $data->update([
            'name' => $request->name,
            'main' => $request->main ?? 0,
            'parent_id' => $request->parent_id ?? 0,
        ]);

        return $data;
    }

    public function destroy($id)
    {
        if (!Gate::allows('Permissions-delete')) {
            return abort(503);
        }

        $data = Permission::find($id);
        $data->delete();

        return $data;
    }

    public function getdata()
    {
       

        return DataTables::of(Permission::query())
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Gate::allows('Permissions-edit')) {
                    $btn .= '<a href="' . route('permissions.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a> ';
                }
                if (Gate::allows('Permissions-delete')) {
                    $btn .= '<a href="#" class="btn btn-danger btn-sm delete" data-route="' . route('permissions.destroy', $row->id) . '">Delete</a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
