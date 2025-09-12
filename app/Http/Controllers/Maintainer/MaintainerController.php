<?php

namespace App\Http\Controllers\Maintainer;

use Config;
use App\Models\Type;
use App\Models\User;
use App\Models\Group;
use App\Models\Property;
use App\Models\Maintainer;
use App\Helper\CoreAccounts;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\Branches;

class MaintainerController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage maintainer')) {

            $parentId = Auth::user()->id;
            $maintainers = Maintainer::where('parent_id', $parentId)->get();
            return view('maintainer.index', compact('maintainers'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create maintainer')) {

            $parentId = Auth::user()->id;
            $branches = Branches::get()->pluck('name', 'id');
            
            $types = Type::where('parent_id', $parentId)->where('type', 'maintainer_type')->get()->pluck('title', 'id');
            $types->prepend(__('Select Type'), '');

            return view('maintainer.create', compact('branches', 'types'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function store(Request $request)
    {
       
        if (\Auth::user()->can('create maintainer')) {
            $validator = \Validator::make(
                $request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users,email',
                    'password' => 'required',
                    // 'phone_number' => 'required',
                    'branch_id' => 'required',
                    'type_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $userRole = Role::where('name', 'maintainer')->first();
            $user = new User();
            $user->name = $request->first_name. ' ' .$request->last_name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->branch_id = $request->branch_id;
            $user->role_id = $userRole->id;
            // $user->profile = 'avatar.png';
            // $user->lang = 'english';
            // $user->parent_id = parentId();
            $user->save();
            $user->assignRole($userRole);

            // if (!empty($request->profile)) {
            //     $maintainerFilenameWithExt = $request->file('profile')->getClientOriginalName();
            //     $maintainerFilename = pathinfo($maintainerFilenameWithExt, PATHINFO_FILENAME);
            //     $maintainerExtension = $request->file('profile')->getClientOriginalExtension();
            //     $maintainerFileName = $maintainerFilename . '_' . time() . '.' . $maintainerExtension;
            //     $dir = storage_path('upload/profile');
            //     if (!file_exists($dir)) {
            //         mkdir($dir, 0777, true);
            //     }
            //     $request->file('profile')->storeAs('upload/profile/', $maintainerFileName);
            //     $user->profile = $maintainerFileName;
            //     $user->save();
            // }


            $maintainer = new Maintainer();
            $maintainer->user_id = $user->id;
            $maintainer->branch_id = !empty($request->branch_id) ? $request->branch_id : 0;
            $maintainer->type_id = $request->type_id;
            $maintainer->parent_id = auth()->user()->id;
            $maintainer->save();

            // $parent_groups = Config::get('constants.maintainer_group_ids');
            // $groups = Group::whereIn('id', $parent_groups)->where('type', 'maintainer')->pluck('id');
            // $type_name = Type::where('id', $request->type_id)->value('title');
            // foreach ($groups as $group_id) {
            //     $ledger_name = 'Maintainer - ' . $request->first_name . ' ' . $request->last_name . ' (' . $type_name . ')';
            //     CoreAccounts::create_ledger($group_id, $ledger_name, null, $maintainer->id);
            // }

            return redirect()->route('maintainer.index')->with('success', 'Maintainer successfully created.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show(Maintainer $maintainer)
    {
        //
    }


    public function edit(Maintainer $maintainer)
    {
        // dd($maintainer);
        if (\Auth::user()->can('edit maintainer')) {
            
            $parentId = Auth::user()->id;
            $branches = Branches::get()->pluck('name', 'id');
            
            $types = Type::where('parent_id', $parentId)->where('type', 'maintainer_type')->get()->pluck('title', 'id');
            $types->prepend(__('Select Type'), '');

            $user = User::find($maintainer->user_id);

            return view('maintainer.edit', compact('branches', 'maintainer', 'types', 'user'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Maintainer $maintainer)
    {
        // dd($request->all() , $maintainer);
        if (\Auth::user()->can('edit maintainer')) {
            $validator = \Validator::make(
                $request->all(), [
                    'first_name' => 'required',
                    // 'last_name' => 'required',
                    'email' => 'required|unique|users:email,',$maintainer->id,
                    // 'phone_number' => 'required',
                    'branch_id' => 'required',
                    'type_id' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $user = User::find($maintainer->user_id);
            $user->name = $request->first_name;
            // $user->last_name = $request->last_name;
            $user->email = $request->email;
            // $user->phone_number = $request->phone_number;
            $user->save();

            // if (!empty($request->profile)) {
            //     $maintainerFilenameWithExt = $request->file('profile')->getClientOriginalName();
            //     $maintainerFilename = pathinfo($maintainerFilenameWithExt, PATHINFO_FILENAME);
            //     $maintainerExtension = $request->file('profile')->getClientOriginalExtension();
            //     $maintainerFileName = $maintainerFilename . '_' . time() . '.' . $maintainerExtension;
            //     $dir = storage_path('upload/profile');
            //     if (!file_exists($dir)) {
            //         mkdir($dir, 0777, true);
            //     }
            //     $request->file('profile')->storeAs('upload/profile/', $maintainerFileName);
            //     $user->profile = $maintainerFileName;
            //     $user->save();
            // }

            $maintainer->branch_id = !empty($request->branch_id) ? $request->branch_id : 0;
            $maintainer->type_id = $request->type_id;
            $maintainer->save();


            return redirect()->route('maintainer.index')->with('success', 'Maintainer successfully updated.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Maintainer $maintainer)
    {
        if (\Auth::user()->can('delete maintainer')) {
            $maintainer->delete();
            return redirect()->back()->with('success', 'Maintainer successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
}
