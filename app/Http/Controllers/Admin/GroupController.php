<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Helper\GroupsTree;
use App\Models\AccountType;
use App\Helper\CoreAccounts;
use Illuminate\Http\Request;
use App\Services\GroupService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class GroupController extends Controller
{

    protected $groupService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }


    public function index(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $AccountTypes = AccountType::all()->getDictionary();
        $parentGroups = new GroupsTree();
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toListView($parentGroups, -1);
        $Groups = $parentGroups->groupListView;
        return view('admin.groups.index', compact('Groups', 'AccountTypes'))->with('succesMessage', 'Group');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $AccountTypes = AccountType::getActiveListDropdown(true);

        $Groups = GroupsTree::buildOptions3(GroupsTree::buildTree(Group::OrderBy('name', 'asc')->get()->toArray()), old('parent_id'));
        return view('admin.groups.create', compact('Groups', 'AccountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }


        try {
            $groupResponse = CoreAccounts::createGroup($request->all());

            if (!$groupResponse['status']) {
                throw new \Exception($groupResponse['error']);
            }

            if ($groupResponse['level'] == 4) {
                $ledgerResponse = CoreAccounts::createLedger($groupResponse['id']);
                if (!$ledgerResponse['status']) {
                    throw new \Exception($ledgerResponse['error']);
                }
            }

            return redirect()->back()->with('success', 'Chart of Account created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }


    public function edit(Group $group)
    {
       if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        if (in_array($group->id, Config::get('constants.accounts_main_heads')) && $group->parent_id == 0) {
            return redirect()->route('admin.groups.index');
        }

        // Eager-load the 'ledgers' relationship
        $group->load('ledgers');

        $Groups = GroupsTree::buildOptions(
            GroupsTree::buildTree(Group::orderBy('name', 'asc')->get()->toArray(), 0, $group->id),
            $group->parent_id
        );
        return view('admin.groups.edit', compact('group', 'Groups'));
    }




    /**
     * Update Group in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        if (!Gate::allows('Groups-edit')) {
            return abort(503);
        }

        $group = Group::findOrFail($id);
        $group->name = $request->input('name');
        $group->save();

        return redirect()->route('admin.accounts.chart_of_accounts.ledger_tree')->with('success', 'Group updated successfully');
    }

    /**
     * Remove Group from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $group = Group::find($id);

            if (!$group) {
                throw new \Exception('Group not found.');
            }

            if (!Gate::allows('Groups-delete')) {
                throw new \Exception('You don’t have permission to delete this group.');
            }

            if ($group->children->count()) {
                throw new \Exception('First delete child groups.');
            }

            // if ($group->ledgers->count()) {
            //     throw new \Exception('First delete ledgers.');
            // }

            $group->delete();

            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'message' => 'Group deleted.']);
            }

            return redirect()->back()->with('success', 'Group deleted.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function fetch_coa($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $groups = Group::with('ledgers')->where('id', $id)->get();
        //dd($groups);
        return response()->json(['result' => $groups]);
    }
}

