<?php

namespace App\Http\Controllers\Fee;

use App\Http\Controllers\Controller;
use App\Services\FeeTermService;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Student\AcademicSession;

class FeeTermController extends Controller
{
    protected $feeTermService;

    public function __construct(FeeTermService $feeTermService)
    {
        $this->feeTermService = $feeTermService;
    }

    public function index()
    {
        if (!Gate::allows('FeeTerm-list')) {
            return abort(403);
        }

        $companies = Company::all();
        $branches = Branch::all();
        $academicYears = AcademicSession::where('status', 1)->get();

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $companies = Company::where('id', $user->company_id)->get();
            }

            if (!is_null($user->branch_id)) {
                $branches = Branch::where('id', $user->branch_id)->get();
            }
        }

        return view('admin.fee.fee-terms.index', compact('companies', 'branches', 'academicYears'));
    }

    public function create()
    {
        if (!Gate::allows('FeeTerm-create')) {
            return abort(403);
        }

        $companies = Company::all();
        $branches = Branch::all();

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $companies = Company::where('id', $user->company_id)->get();
            }

            if (!is_null($user->branch_id)) {
                $branches = Branch::where('id', $user->branch_id)->get();
            }
        }

        return view('admin.fee.fee-terms.create', compact('companies', 'branches'));
    }

    public function store(Request $request)
    {
        return $this->feeTermService->store($request);
    }

    public function show($id)
    {
        if (!Gate::allows('FeeTerm-view')) {
            return abort(403);
        }

        $feeTerm = $this->feeTermService->edit($id);
        return view('admin.fee.fee-terms.show', compact('feeTerm'));
    }

    public function edit($id)
    {
        if (!Gate::allows('FeeTerm-edit')) {
            return abort(403);
        }

        $feeTerm = $this->feeTermService->edit($id);
        $companies = Company::all();
        $branches = Branch::all();

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $companies = Company::where('id', $user->company_id)->get();
            }

            if (!is_null($user->branch_id)) {
                $branches = Branch::where('id', $user->branch_id)->get();
            }
        }

        return view('admin.fee.fee-terms.edit', compact('feeTerm', 'companies', 'branches'));
    }

    public function update(Request $request, $id)
    {
        return $this->feeTermService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->feeTermService->destroy($id);
    }

    public function getdata()
    {
        return $this->feeTermService->getdata();
    }

    public function changeStatus(Request $request)
    {
        $feeTerm = $this->feeTermService->changeStatus($request);
        return response()->json(['message' => 'Status changed successfully', 'data' => $feeTerm]);
    }

    public function handleBulkAction(Request $request)
    {
        if ($request->action == 'delete') {
            $ids = $request->ids;
            foreach ($ids as $id) {
                $this->feeTermService->destroy($id);
            }
            return response()->json(['message' => 'Selected fee terms deleted successfully']);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $this->feeTermService->destroy($id);
        }
        return response()->json(['message' => 'Selected fee terms deleted successfully']);
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status === 'active' ? 1 : 0;
        
        foreach ($ids as $id) {
            $feeTerm = \App\Models\Fee\FeeTerm::find($id);
            if ($feeTerm) {
                $feeTerm->status = $status;
                $feeTerm->save();
            }
        }
        
        $statusText = $request->status === 'active' ? 'activated' : 'deactivated';
        return response()->json(['message' => "Selected fee terms {$statusText} successfully"]);
    }
}