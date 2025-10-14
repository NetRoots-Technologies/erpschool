<?php

namespace App\Services;

use Config;
use App\Models\User;
use DataTables;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;

class UserServise
{

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    public function apiindex()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return User::all();

    }

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Role::all();
    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // dd($request->all());
        $data = $request->all();
        $data['image'] = "dist/Profile/defualt.png";

        $fileNameToStore = null;
        $data['password'] = Hash::make($request->password);
        if ($request->hasfile('profile')) {
            $file = $request->file('profile');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'dist/Profile';
            $file->move($destinationPath, $fileNameToStore);
            $data['image'] = $fileNameToStore;
        }
        $data['role_id'] = json_encode($request->role_id); // 👈 key step

        //    dd($data);
        $user = User::create($data);
        $roles = $request->role_id ? $request->role_id : [];
        $user->role_id = $request->role_id;

        $user->assignRole($roles);

    }

    public function user_deactive($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $user = User::find($id);
        if ($user) {
            $user->active = 0;
            $user->save();
        }
    }

    public function user_data($slug)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $user = User::where('slug', $slug)->first();

    }

    public function user_active($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $user = User::find($id);
        if ($user) {
            $user->active = 1;
            $user->save();
        }

    }


    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = User::query();
        

        return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                return ($row->active == 1) ? 'Active' : 'Deactive';
            })->addColumn('email_verified_at', function ($row) {
                return ($row->email_verified_at != null) ? ' verified ' : 'Not verified';
            })
            ->addColumn('status', function ($row) {

                return ($row->active == 1) ? 'Active' : 'Deactive';

            })
            ->addColumn('action', function ($row) {

                $btn = ' <form class="delete_form" data-route="' . route("users.destroy", $row->id) . '"   id="User-' . $row->id . '"  method="POST"> ';

                // if (Gate::allows('users-edit'))
                // $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm user_edit"  data-user-edit=\'' . $row . '\'>Edit</a>';
                $btn = $btn . '<a  data-id="' . $row->id . '" class="btn btn-primary text-white  btn-sm user_edit"  data-user-edit=\'' . json_encode([
                    'id' => $row->id,
                    'name' => $row->name,
                    'email' => $row->email,
                    'company_id' => $row->company_id,
                    'branch_id' => $row->branch_id,
                    'department_id' => $row->department_id,
                    'role_id' => $row->roles->pluck('id'), // ✅ this returns an array of role IDs
                ]) . '\'
                        >Edit</a>';
                // if (Gate::allows('users-delete'))
                $btn = $btn . ' <button data-id="User-' . $row->id . '" type="button" class="btn btn-danger delete btn-sm "" >Delete</button>';

                $btn = $btn . method_field('DELETE') . '' . csrf_field();

                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['name', 'email', 'role', 'status', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return User::find($id);


    }

    // public function update($request, $id)
    // {

    //     $input = $request->all();

    //     $user = User::find($id);
    //     if ($request->password) {
    //         $user->password = Hash::make($request->password);
    //     }
    //     $fileNameToStore = null;
    //     if ($request->hasfile('profile')) {
    //         $file = $request->file('profile');
    //         $filenameWithExt = $file->getClientOriginalName();
    //         $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
    //         $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
    //         $filename = preg_replace("/\s+/", '-', $filename);
    //         $extension = $file->getClientOriginalExtension();
    //         $fileNameToStore = $filename . '_' . time() . '.' . $extension;
    //         $destinationPath = 'dist/Profile';
    //         $file->move($destinationPath, $fileNameToStore);
    //         $user->image = $fileNameToStore;
    //     }
    //     // $user->role_id = $request->role_id[0];
    //     $user->email = $request->email;
    //     $user->name = $request->name;
    //     $user->save();
    //     $roles = $request->role_id ?? [];
    //     $user->syncRoles($roles);

    //     return 'done';

    // }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $input = $request->all();
        $user = User::find($id);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasfile('profile')) {
            $file = $request->file('profile');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', pathinfo($filenameWithExt, PATHINFO_FILENAME));
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'dist/Profile';
            $file->move($destinationPath, $fileNameToStore);
            $user->image = $fileNameToStore;
        }

        // Don't save role_id into user table — just update other fields
        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();

        // Correctly sync roles
        $roles = $request->role_id ?? [];
        $user->syncRoles($roles);

        return 'done';
    }


    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $User = User::findOrFail($id);
        if ($User)
            $User->delete();

    }
}

