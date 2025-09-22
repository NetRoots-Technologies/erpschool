<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Services\RolesServise;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $RolesServise;

    //
    public function __construct(RolesServise $RolesServise)
    {
        $this->RolesServise = $RolesServise;
    }

    public function index()
    {
        if (!Gate::allows('Roles-list')) {
            return abort(503);
        }
        $permissions = $this->RolesServise->index();
        return view('admin.roles.index', compact('permissions'));
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Users = $this->RolesServise->getdata();
        return $Users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Roles-create')) {
            return abort(503);
        }
        $permissions = $this->RolesServise->index();
        return view('admin.roles.create', compact('permissions'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Roles-create')) {
            return abort(503);
        }

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permisions' => 'required',
        ]);
        $this->RolesServise->store($request);
        return 'done';
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();
        return view('admin.roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Roles-edit')) {
            return abort(503);
        }

        $role = Role::find($id);

        $permissions = $this->RolesServise->index();
        $AllowedPermissions = $this->RolesServise->AllowedPermissions($id);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions', 'AllowedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('Roles-edit')) {
            return abort(503);
        }

        $this->validate($request, [
            'name' => 'required',
            'permisions' => 'required',
        ]);
        $this->RolesServise->update($request, $id);
        return 'done';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('roles-delete')) {
            return abort(503);
        }
        $this->RolesServise->destroy($id);
        return 'done';
    }
}

