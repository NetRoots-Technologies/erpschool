<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\Section;
use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Student\AcademicSession;
use App\Services\SectionService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Exports\SectionSampleExport;
use App\Imports\SectionExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;

class SectionController extends Controller
{
    protected $SectionService;

    public function __construct(SectionService $sectionService)
    {
        $this->SectionService = $sectionService;

          // 🔐 Permissions middleware
        $this->middleware('can:Section-list')->only(['index', 'getData']);
        $this->middleware('can:Section-create')->only(['create', 'store', 'importBulkFile']);
        $this->middleware('can:Section-edit')->only(['edit', 'update', 'changeStatus']);
        $this->middleware('can:Section-delete')->only(['destroy', 'handleBulkAction']);

    }

    public function index()
    {

        $formattedSessions = AcademicSession::where('status', 1)->get();
        $sessions = [];

        foreach ($formattedSessions as $session) {
            $sessions[$session->id] = $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y', strtotime($session->end_date));
        }

        $branches = Branch::where('status', 1)->get();
        $classes = AcademicClass::where('status', 1)->get();
        $companies = Company::where('status', 1)->get();

        return view('acadmeic.section.index', compact('companies', 'sessions', 'classes', 'branches'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {


        return $this->SectionService->store($request);
    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

        return $this->SectionService->update($request, $id);
    }

    public function destroy($id)
    {


        return $this->SectionService->destroy($id);
    }

    public function getData()
    {

        return $this->SectionService->getdata();
    }

    public function changeStatus(Request $request)
    {

        return $this->SectionService->changeStatus($request);
    }

    public function handleBulkAction(Request $request)
    {

        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Section::where('id', $id)->delete();
        }

        return response()->json(['message' => 'Bulk action completed successfully']);
    }

    public function exportbulkfile()
    {

        return Excel::download(new SectionSampleExport, 'section_bulk_sample.xlsx');
    }

  public function importBulkFile(Request $request)
{

    $request->validate([
        'import_file' => 'required|file|mimes:xlsx,xls,csv',
    ]);
    // dd($request->file('import_file'));

    try {
        Excel::import(new SectionExcelImport, $request->file('import_file'));
        return back()->with('success', 'Sections imported successfully!');
    } catch (ValidationException $e) {
        $failures = $e->failures();
        $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';
        return back()->with('error', 'Import Failed: ' . $firstError);
    } catch (\Throwable $e) {
        Log::error('Section Import Exception: ' . $e->getMessage());
        return back()->with('error', 'Import Failed: ' . $e->getMessage());
    }
}

}
