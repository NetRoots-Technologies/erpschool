<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\ZktecoController;
use App\Models\Academic\SchoolType;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Accounts\AccountGroup;
use App\Models\HR\OtherBranch;
use App\Services\BranchService;
use App\Services\LedgerService;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BranchSampleExport;
use Illuminate\Validation\ValidationException;
use App\Imports\BranchExcelImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $BranchService;
    protected $EmployeeController;
    protected $ledgerService;


    public function __construct(BranchService $BranchService, EmployeeController $employeeController, LedgerService $ledgerService)
    {
        $this->BranchService = $BranchService;
        $this->EmployeeController = $employeeController;
        $this->ledgerService = $ledgerService;
    }


    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $company = collect();
        $schoolTypes = SchoolType::where('status', 1)->get();


        //Only display those companies from where user belongs To 

        if (Auth::check()) {
            $user = Auth::user();
            if (!is_null($user->company_id)) {
                $company = Company::where('status', 1)
                            ->where('id', $user->company_id)
                            ->get();
            } else {
                $company = Company::where('status', 1)->get();
            }
        }

        return view('admin.branches.index', compact('company', 'schoolTypes'));
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
        DB::beginTransaction();

        try
        {
            $dt['payroll'] = [config('constants.FixedGroups.Payroll')];
            $dt['eobi'] = config('constants.FixedGroups.EOBI');
            $dt['pf'] = config('constants.FixedGroups.PF');
            $dt['ss'] = config('constants.FixedGroups.SS');

            $branch = $this->BranchService->store($request);

            foreach($dt as $d){
                $this->ledgerService->createAutoLedgers($d ,$branch->name, $branch->id, Branch::class, $branch->id);
            }
            DB::commit();

            return response()->json(['error' => false,
                'message' => 'Branch Created Successfully',
                'data'=> $branch,
            ], 200);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

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
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->BranchService->update($request, $id);
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->BranchService->getdata();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->BranchService->destroy($id);

    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $branch = $this->BranchService->changeStatus($request);

    }

    public function handleBulkAction(Request $request)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        //            dd($ids);
        foreach ($ids as $id) {
            Branch::where('id', $id)->delete();
            return response()->json(['message' => 'Bulk  action Completed successfully']);
        }
    }

    public function syncData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            $id = $request->get('id');
            $data = $this->EmployeeController->addAttendance($id);
            if ($data === true) {
                return response()->json(['message' => 'Attendance added Successfully']);
            } else {
                return response()->json(['message' => $data], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }


     // ✅ Export the sample file with required headers
    public function exportBulkFile()
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
           return Excel::download(new \App\Exports\BranchSampleExport, 'Branch_bulk_sample.xlsx');

    }

   public function importBulkFile(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
        Excel::import(new \App\Imports\BranchExcelImport, $request->file('import_file'));
        return back()->with('success', 'Branches imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $firstError = count($failures) > 0 && count($failures[0]->errors()) > 0 ? $failures[0]->errors()[0] : 'Validation failed.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('Branch Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

}

