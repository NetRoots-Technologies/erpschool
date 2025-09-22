<?php

namespace App\Http\Controllers;

use App\Services\RolesServise;
use Illuminate\Http\Request;
use App\Services\PermisionServices;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $PermisionServices;
    public function __construct(Request $request,PermisionServices $PermisionServices)
    {
        $this->PermisionServices = $PermisionServices;
    }

    public function index()
    {
        if (!Gate::allows('Permissions-list')) {
            return abort(503);
        }

        $mainpermissions = Permission::where('main', 1)->get();

        return view('admin.permisions.index', compact('mainpermissions'));
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Users = $this->PermisionServices->getdata();
        return $Users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('permission-create')) {
            return abort(503);
        }
        $mainpermissions = Permission::where('main', 1)->get();
        return view('admin.permisions.create', compact('mainpermissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('permission-create')) {
            return abort(503);
        }

        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);

        $data = $this->PermisionServices->store($request);


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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('permission-edit')) {
            return abort(503);
        }
        $Permision = $this->PermisionServices->edit($id);
        $mainpermissions = Permission::where('main', 1)->get();
        return view('admin.permisions.edit', compact('Permision', 'mainpermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $id, $request)
    {
        if (!Gate::allows('permission-edit')) {
            return abort(503);
        }
        $Permision = $this->PermisionServices->update($id, $request);
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
        if (!Gate::allows('permission-delete')) {
            return abort(503);
        }
        $Permision = $this->PermisionServices->destroy($id);
    }
}

