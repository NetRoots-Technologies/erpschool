<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Student\Students;
use App\Exports\PreAdmissionExport;
use App\Imports\PreAdmissionImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Admin\StudentDataBank;
use App\Services\StudentDataBankService;
use Maatwebsite\Excel\Validators\ValidationException;

class StudentDataBankController extends Controller
{
    protected $StudentDataBankService;
    public function __construct(StudentDataBankService $studentDataBankService)
    {
        $this->StudentDataBankService = $studentDataBankService;
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
        return view('acadmeic.student_databank.index');
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
        $year = Carbon::now()->format('y');
        $branchCode = 'LHR';
        $regNo = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $referenceNo = "CSS-$year-$branchCode-$regNo";
        return view('acadmeic.student_databank.create', compact('referenceNo'));

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
        // dd($request->all());
        $this->StudentDataBankService->store($request);
        return redirect()->route('academic.studentDataBank.index')->with('success', 'Databank created successfully');
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
        $studentDatabank = StudentDataBank::find($id);
        return view('acadmeic.student_databank.edit', compact('studentDatabank'));

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
        $this->StudentDataBankService->update($request, $id);

        return redirect()->route('academic.studentDataBank.index')->with('success', 'Databank Updated successfully');

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
        $this->StudentDataBankService->destroy($id);

        return redirect()->route('academic.studentDataBank.index')->with('success', 'Databank Deleted successfully');

    }

    public function getData()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->StudentDataBankService->getData();
        return $data;
    }

    public function addStudent($id = 0)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $studentDatabank = StudentDataBank::find($id);
        $companies = Company::where('status', 1)->get();
        $branches = Branch::where('status', 1)->get();
        $students = Students::all();

        if ($studentDatabank != null) {
            return view('acadmeic.student.databank', compact('students', 'branches', 'studentDatabank', 'companies'));
        }
    }


    public function handleBulkAction(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            StudentDataBank::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }

    public function exportBulkFile()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new PreAdmissionExport, 'preadmission_sample.xlsx');
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
            Excel::import(new PreAdmissionImport, $request->file('import_file'));
            return back()->with('success', 'Admission Form Excel File imported successfully!');
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('PreAdmissionForm Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

}

