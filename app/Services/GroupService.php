<?php

namespace App\Services;

use Config;
use DataTables;
use Inertia\Inertia;
use App\Models\Group;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\Permission\Models\Permission;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;

class GroupService
{

    public function index($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%")->orWhere('id', 'LIKE', "%{$value}%")->orWhere('created_at', 'LIKE', "%{$value}%");
            });
        });

        $groups = QueryBuilder::for(Group::class)
            ->defaultSort('name')
            ->allowedSorts(['name', 'created_at', 'id'])
            ->allowedFilters(['name', $globalSearch])
            ->paginate()
            ->withQueryString();

        return Inertia::render('Group/Index', [
            'groups' => $groups,
        ])->table(function (InertiaTable $table) {
            $table->addSearchRows([
                'id' => 'Id',
                'name' => 'Name',
                'created_at' => 'Created At',
            ])->addColumns([
                        'id' => 'Id',
                        'name' => 'Name',
                        'created_at' => 'Created At',
                    ]);
        });

    }

    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Permission::with('child')->where('parent_id', 0)->get();

    }

    public function store($request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $permissions = [];
        $input = $request->all();
        $permissions = $input['checkedNames'];
        $role = Role::create(['name' => $input['name'], 'guard_name' => 'web']);
        $role->givePermissionTo($permissions);
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = Role::select('id', 'name')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form  method="POST" onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');"  action="' . route("admin.roles.destroy", $row->id) . '"> ';
                $btn = $btn . '<a href=" ' . route("admin.roles.show", $row->id) . '"  class="ml-2"><i class="fas fa-eye"></i></a>';
                $btn = $btn . ' <a href="' . route("admin.roles.edit", $row->id) . '" class="ml-2">  <i class="fas fa-edit"></i></a>';
                $btn = $btn . '<button  type="submit" class="ml-2" ><i class="fas fa-trash"></i></button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Role::find($id);


    }

    public function AllowedPermissions($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where(['role_has_permissions.role_id' => $id])
            ->get()->pluck('name', 'id');
    }

    public function update($request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $permissions = $request->checkedNames;
        $role->syncPermissions($permissions);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $role = Role::findOrFail($id);
        if ($role)
            $role->delete();

    }

}
