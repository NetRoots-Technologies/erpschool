<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\ApprovalRole;
use App\Models\User;
use App\Models\ApprovalAuthority;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SignatoryAuthorityController extends Controller
{

    public function index()
    {
        if (!Gate::allows('SignatoryAuthorities-list')) {
            return abort(503);
        }
        return view('admin.approval_authorities.index');
    }

    public function getData()
    {
        if (!Gate::allows('SignatoryAuthorities-list')) {
            return abort(503);
        }

        $data = ApprovalAuthority::with(['company', 'branch', 'role', 'user'])->orderBy('id', 'asc');

        if (Auth::check()) {
            $company_id = Auth::user()->company_id;
            $branch_id = Auth::user()->branch_id;
            if (!is_null($company_id)) {
                $data->where('company_id', $company_id);
            }

            if (!is_null($branch_id)) {
                $data->where('branch_id', $branch_id);
            }
        }

        return datatables()->of($data)
            ->addIndexColumn()

            // Use addColumn instead of editColumn for related model fields
            ->addColumn('company', fn($row) => $row->company->name ?? '-')
            ->addColumn('branch', fn($row) => $row->branch->name ?? '-')
            ->addColumn('role', fn($row) => $row->role->name ?? '-')
            ->addColumn('user', fn($row) => $row->user->name ?? '-')

            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })

            // ->addColumn('action', function ($row) {
            //     $editUrl = route('admin.signatory-authorities.edit', $row->id);
            //     $deleteUrl = route('admin.signatory-authorities.destroy', $row->id);

            //     return '
            //         <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
            //         <form method="POST" action="' . $deleteUrl . '" style="display:inline-block;">
            //             ' . method_field('DELETE') . csrf_field() . '
            //             <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
            //         </form>
            //     ';
            // })

            ->rawColumns(['status'])
            ->make(true);
    }




    public function create()
    {
        if (!Gate::allows('SignatoryAuthorities-create')) {
            return abort(503);
        }
        $companies = Company::all();
        $roles = ApprovalRole::all();

        $users = User::where('id', '!=', auth()->id())->get(); // Exclude current user

        return view('admin.approval_authorities.create', compact('companies', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('SignatoryAuthorities-create')) {
            return abort(503);
        }
        $request->validate([
            'company_id' => 'required',
            'branch_id' => 'required',
            'user_id' => 'required',
            'approval_role_id' => [
                'required',
                Rule::unique('approval_authorities')
                    ->where(function ($query) use ($request) {
                        return $query->where('company_id', $request->company_id)
                            ->where('branch_id', $request->branch_id)
                            ->where('user_id', $request->user_id);
                    }),
            ],
        ]);

        ApprovalAuthority::create($request->all());

        return redirect()->route('admin.signatory-authorities.index')->with('success', 'Authority added successfully.');
    }

    public function edit($id)
    {
        if (!Gate::allows('SignatoryAuthorities-edit')) {
            return abort(503);
        }
        $authority = ApprovalAuthority::findOrFail($id);
        $companies = Company::all();
        $roles = ApprovalRole::all();
        $users = User::all();
        $authorities = ApprovalAuthority::with(['company', 'branch', 'role', 'user'])->get();

        return view('admin.approval_authorities.create', compact('companies', 'roles', 'users', 'authority', 'authorities'));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('SignatoryAuthorities-edit')) {
            return abort(503);
        }
        $authority = ApprovalAuthority::findOrFail($id);

        $validated = $request->validate([
            'module' => 'required|string|max:255',
            'company_id' => 'required|exists:company,id',
            'branch_id' => 'nullable|exists:branches,id',
            'user_id' => 'nullable|exists:users,id',
            'role_id' => 'nullable|exists:approval_roles,id',
            'is_active' => 'required|boolean',
        ]);

        $authority->update($validated);

        return redirect()->route('admin.approval-authorities.index')->with('success', 'Signatory authority updated successfully.');
    }

    public function destroy($id)
    {
        if (!Gate::allows('SignatoryAuthorities-delte')) {
            return abort(503);
        }
        $authority = ApprovalAuthority::findOrFail($id);
        $authority->delete();

        return redirect()->back()->with('success', 'Signatory authority deleted successfully.');
    }
}
