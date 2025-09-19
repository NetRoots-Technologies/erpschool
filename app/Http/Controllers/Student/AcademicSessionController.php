<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Academic\SchoolType;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AcademicSessionImport;
use App\Models\Student\AcademicSession;
use App\Services\AcademicSessionService;
use Dotenv\Exception\ValidationException;
use App\Exports\AcademicSessionSampleExport;

class AcademicSessionController extends Controller
{
    protected $AcademicSessionService;
    public function __construct(AcademicSessionService $academicSessionService)
    {
        $this->AcademicSessionService = $academicSessionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $companies = Company::where('status', 1)->get();
        $schools = SchoolType::where('status', 1)->get();

        return view('acadmeic.academic_session.index', compact('companies', 'schools'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $financial = $this->AcademicSessionService->store($request);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $Company = $this->AcademicSessionService->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->AcademicSessionService->destroy($id);

    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $AcademicSession = $this->AcademicSessionService->getdata();
        return $AcademicSession;
    }

    public function changeStatus(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $academic = $this->AcademicSessionService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            AcademicSession::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }
    public function exportBulkFile()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new AcademicSessionSampleExport, 'academic_session_bulk.xlsx');
    }
    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate(
            [
                'import_file' => 'required|file|mimes:xlsx,xls,csv'
            ]
        );
        try {
            Excel::import(new AcademicSessionImport, $request->file('import_file'));
            return back()->with('success', 'Companies imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failure();
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('Company Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }

    }

}

