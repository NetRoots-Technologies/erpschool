<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\DepartmentService;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartmentSampleExport;
use App\Imports\DepartmentImport;
use App\Models\Admin\Department;
use Illuminate\Support\Facades\Auth;


class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct(DepartmentService $DepartmentService)
    {
        $this->DepartmentService = $DepartmentService;
    }

    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $user = Auth::user();

        $categories = Category::all();
        $company = $this->DepartmentService->create();

        $departmentsQuery = Department::select('id', 'name');

        if (Auth::check()) {
            if (!is_null($user->company_id)) {
                $departmentsQuery->where('company_id', $user->company_id);
            }

            if (!is_null($user->branch_id)) {
                $departmentsQuery->where('branch_id', $user->branch_id);
            }
        }

        $departments = $departmentsQuery->get();

        return view('admin.department.index', compact('company', 'categories', 'departments'));
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

    public function company(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->DepartmentService->company($request);
    }

  public function getdepartments(Request $request)    
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // dd($request->all());
      return $this->DepartmentService->getdepartments($request);
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->DepartmentService->getdata();
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
        return $this->DepartmentService->store($request);
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
        return $this->DepartmentService->update($request, $id);
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
        return $this->DepartmentService->destroy($id);
    }

    public function changeStatus(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $department = $this->DepartmentService->changeStatus($request);


    }


        public function exportbulkfile(){
            if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return Excel::download(new DepartmentSampleExport, 'Department_bulk_sample.xlsx');
    }

//     public function importBulkFile(Request $request)
//     {
//         try {
//             // Minimal check to avoid runtime errors
//             if (!$request->hasFile('import_file')) {
//                 return back()->with('error', 'No file uploaded.');
//             }

//             // Excel::import(new DepartmentExcelImport, $request->file('import_file'));

//          Excel::import(new DepartmentImport, $request->file('import_file'));
//                 return back()->with('success', 'Department imported successfully!');
//         } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
//             $failures = $e->failures();
//             $firstError = !empty($failures) && !empty($failures[0]->errors())
//                 ? $failures[0]->errors()[0]
//                 : 'Validation failed.';
//             return back()->with('error', 'Import Failed: ' . $firstError);
//         } catch (\Exception $e) {
//             Log::error('Department Import Exception: ' . $e->getMessage());
//             return back()->with('error', 'Import Failed: ' . $e->getMessage());
//         }
// }


public function importBulkFile(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        try {
            // Minimal check to avoid runtime errors
            if (!$request->hasFile('import_file')) {
                return back()->with('error', 'No file uploaded.');
            }

            // Validate the uploaded file
            $request->validate([
                'import_file' => 'required|file|mimes:xlsx,xls,csv',
            ]);

            $file = $request->file('import_file');

            // Import with explicit configuration
            Excel::import(new DepartmentImport, $file, null, \Maatwebsite\Excel\Excel::XLSX, [
                'heading_row' => 1, // Explicitly set header row
                'start_row' => 2,  // Start data from row 2
            ]);

            return back()->with('success', 'Department imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $firstError = !empty($failures) && !empty($failures[0]->errors())
                ? $failures[0]->errors()[0]
                : 'Validation failed.';
            Log::error('Excel Validation Exception: ' . $firstError, ['failures' => $failures]);
            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Exception $e) {
            Log::error('Department Import Exception: ' . $e->getMessage(), ['file' => $file->getClientOriginalName()]);
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }



}

