<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use App\Services\UserServise;
use Illuminate\Support\Facades\Session;
use App\Models\Admin\Company;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    protected $UserServise;
    public function __construct(UserServise $UserServise)
    {
        $this->UserServise = $UserServise;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('Users-list')) {
            return abort(503);
        }
        $companies=Company::select('id','name')->where('status',1)->get();
        $roles = Role::get();
        return view('admin.users.index', compact('roles','companies'));
    }
    public function getData()
    {
        $Users = $this->UserServise->getdata();
        return $Users;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Gate::allows('Users-create')) {
            return abort(503);
        }
        $roles = Role::get();
        return view('admin.users.create', compact('roles'));
    }


    public function store(Request $request)
    {
         if (!Gate::allows('Users-create')) {
            return abort(503);
        }
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $this->UserServise->store($request);

        return response()->json([
            'status' => 'success',
            'message' => 'User added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Users-edit')) {
            return abort(503);
        }
        //$user = User::find($id);
        $user = User::find($id);
        $roles = Role::get();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        if (!Gate::allows('Users-edit')) {
            return abort(503);
        }
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|array|min:1'
        ]);
        return $this->UserServise->update($request, $id);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Users-delete')) {
            return abort(503);
        }
        User::find($id)->delete();
        return 0;
    }
}

