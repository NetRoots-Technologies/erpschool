<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\SchoolType;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Services\ClassService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


use App\Exports\AcademicClassSampleExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AcademicClassExcelImport;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{

    public function __construct(ClassService $classService)
    {
        $this->ClassService = $classService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       if (!Gate::allows('Class-list')) {
        abort(403);
    }


        $schools = SchoolType::where('status', 1)->get();

        $formattedSessions = AcademicSession::where('status', 1)->get();

        $sessions = [];


        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }
        $branches = Branch::where('status', 1);
        $companies = Company::where('status', 1);

        if (Auth::check()) {
            $user = Auth::user();

            if (!is_null($user->company_id)) {
                $branches->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $branches->where('id', $user->branch_id);
            }
        }

        return view('acadmeic.class.index', [
            'schools' => $schools,
            'branches' => $branches->get(),
            'sessions' => $sessions,
            'companies' => $companies->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       if (!Gate::allows('Class-create')) {
        abort(403);
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if (!Gate::allows('Class-create')) {
        abort(403);
    }
        return $this->ClassService->store($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('Class-edit')) {
        abort(403);
    }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!Gate::allows('Class-edit')) {
        abort(403);
    }
        return $this->ClassService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          if (!Gate::allows('Class-delete')) {
        abort(403);
    }
        return $this->ClassService->destroy($id);

    }


    public function getdata()
    {
          if (!Gate::allows('Class-list')) {
        abort(403);
    }
        return $this->ClassService->getdata();
    }

    public function changeStatus(Request $request)
    {

        return $class = $this->ClassService->changeStatus($request);

    }

    public function handleBulkAction(Request $request)
    {

        $ids = $request->get('ids');
        AcademicClass::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Bulk  action Completed successfully']);
    }

    public function fetchAcademicSession(Request $request)
    {

        $sessions = AcademicSession::where('company_id', $request->companyid)->get();
        return $sessions;
    }


    public function exportbulkfile()
    {
        return Excel::download(new AcademicClassSampleExport, 'class_bulk_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {

        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AcademicClassExcelImport, $request->file('import_file'));

            return back()->with('success', 'Academic classes imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failures();

            // Optional: Log details
            Log::error('Excel Import Validation Failed', ['errors' => $failures]);

            // Get first failure message
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';

            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('Excel Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

}

