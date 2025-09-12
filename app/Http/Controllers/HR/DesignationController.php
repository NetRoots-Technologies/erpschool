<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Imports\DesignationExcelImport;
use App\Models\Admin\Department;
use App\Models\HR\Designation;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DesignationSampleExport;
use Illuminate\Support\Facades\Log;


class DesignationController extends Controller
{

    public function __construct(DesignationService $designationService)
    {
        $this->DesignationService = $designationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $Designation = $this->DesignationService->getdata();
        return $Designation;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $departments = Department::where('status', 1)->get();
        return view('hr.designation.index', compact('departments'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $Designation = $this->DesignationService->store($request);
    }


    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $Designation = $this->DesignationService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->DesignationService->destroy($id);

    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $designation = $this->DesignationService->changeStatus($request);


    }

    public function handleBulkAction(Request $request)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Designation::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }



    public function exportbulkfile()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Excel::download(new DesignationSampleExport, 'Designation_bulk_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            // Minimal check to avoid runtime errors
            if (!$request->hasFile('import_file')) {
                return back()->with('error', 'No file uploaded.');
            }

            Excel::import(new DesignationExcelImport, $request->file('import_file'));
            return back()->with('success', 'Designations imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $firstError = !empty($failures) && !empty($failures[0]->errors())
                ? $failures[0]->errors()[0]
                : 'Validation failed.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Exception $e) {
            Log::error('Designation Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

}
