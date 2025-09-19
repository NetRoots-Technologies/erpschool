<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Imports\CompanyExcelImport;
use Illuminate\Validation\ValidationException;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompanySampleExport;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(CompanyService $CompanyService)
    {
        $this->CompanyService = $CompanyService;
    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Company = $this->CompanyService->getdata();
        return $Company;
    }
    public function index()
    {
        if (!Gate::allows('Company-list')) {
            return abort(503);
        }
        return view('admin.comapny.index');
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

        return view('admin.comapny.create');
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
        $image = null;
        if ($request->hasFile('voucher_image')) {
            $image = base64_encode(file_get_contents($request->file('voucher_image')));
        }
        return $Company = $this->CompanyService->store($request, $image);
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
        return view('admin.comapny.show');
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
        return view('admin.comapny.edit');
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
        $image = '';
        if ($request->hasFile('voucher_image')) {
            $image = base64_encode(file_get_contents($request->file('voucher_image')));
        }

        return $Company = $this->CompanyService->update($request, $id, $image);
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
        return $this->CompanyService->destroy($id);
    }


    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $company = $this->CompanyService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->get('ids');
        foreach ($ids as $id) {
            $company = Company::find($id);
            if ($company) {
                $company->delete();
            }
        }
        return response()->json(['message' => 'Bulk Action Completed Successfully']);
    }

    // Download Company Sample Bulk File (only Company Name)
    public function exportBulkFile()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new CompanySampleExport, 'company_bulk_sample.xlsx');
    }

    // Import Company Bulk File (only Company Name)
    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CompanyExcelImport, $request->file('import_file'));
            return back()->with('success', 'Companies imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {

            Log::error('Company Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }



}

