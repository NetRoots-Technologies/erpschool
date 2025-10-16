<?php

namespace App\Services;

use Config;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use App\Models\Permission;
use DataTables;

class RolesServise
{

    public function index()
    {
       
        return Permission::with('child')->where('main', 1)->get();
    }

    public function apiindex()
    {
       
        return Role::all();

    }

    public function create()
    {
       
        return Permission::with('child')->where('main', 1)->get();

    }

    public function store($request)
    {
       
        $permissions = [];
        $permissions = $request->permisions;
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->givePermissionTo($permissions);

    }


    public function getdata()
    {
       

        $data = Role::select('id', 'name')->orderby('id', 'DESC')->get();

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form class="delete_form" data-route="' . route("roles.destroy", $row->id) . '"   id="Role-' . $row->id . '"  method="POST"> ';
                if (Gate::allows('Roles-edit'))
                    $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm role_edit"   >Edit</a>';
                if (Gate::allows('Roles-delete'))
                    $btn = $btn . ' <button data-id="Role-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;

            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function edit($id)
    {
       
        return Role::find($id);


    }

    public function AllowedPermissions($id)
    {
       
        return Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where(['role_has_permissions.role_id' => $id])
            ->get()->pluck('name', 'id');

    }

    public function update($request, $id)
    {
       
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $permissions = $request->permisions;
        $role->syncPermissions($permissions);
    }

    public function destroy($id)
    {
       
        $role = Role::findOrFail($id);
        if ($role)
            $role->delete();

    }
}

