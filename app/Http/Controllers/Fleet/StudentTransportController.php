<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Fleet\Route;
use Illuminate\Http\Request;
use App\Models\Fleet\Vehicle;
use App\Models\Student\Students;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Fleet\Transportation;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransportExcelImport;
class StudentTransportController extends Controller
{
    /**
     * Display a listing of students requiring transport.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!Gate::allows('students-transport-list')) {
            return abort(503);
        }
        $transportations = Transportation::with(['student.AcademicClass', 'student.academicSession', 'vehicle', 'route'])
            ->paginate(10);

        $vehicles = Vehicle::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();

        return view('fleet.transportation.index', compact('transportations', 'vehicles', 'routes'));
    }

    /**
     * Show the form for creating a new transportation assignment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students-transport-create')) {
            return abort(503);
        }
        $students = Students::where('is_active', 1)
            ->whereDoesntHave('transportations')
            ->with(['AcademicClass', 'academicSession'])
            ->get();

        $vehicles = Vehicle::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();

        return view('fleet.transportation.create', compact('students', 'vehicles', 'routes'));
    }

    /**
     * Store a newly created transportation assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
        if (!Gate::allows('students-transport-create')) {
            return abort(503);
        }
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'route_id' => 'required|exists:fleet_routes,id',
            'pickup_point' => 'nullable|string|max:255',
            'dropoff_point' => 'nullable|string|max:255',
            'monthly_charges' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Check if student already has an active transportation assignment
        $existingTransportation = Transportation::where('student_id', $request->student_id)
            ->where('status', 'active')
            ->first();

        if ($existingTransportation) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This student already has an active transportation assignment.');
        }

        // Check vehicle capacity
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $currentAssignments = Transportation::where('vehicle_id', $request->vehicle_id)
            ->where('status', 'active')
            ->count();

        if ($currentAssignments >= $vehicle->capacity) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vehicle capacity exceeded. Please select another vehicle.');
        }

        // Create transportation assignment
        $transportation = Transportation::create([
            'student_id' => $request->student_id,
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
            'pickup_point' => $request->pickup_point ?? NULL,
            'dropoff_point' => $request->dropoff_point ?? NULL,
            'monthly_charges' => $request->monthly_charges,
            'status' => $request->status,
            'start_date' => now(),
            'notes' => $request->notes,
            'company_id' => auth()->user()->company_id ?? 1,
            'branch_id' => auth()->user()->branch_id ?? 1,
        ]);

        // ✅ ACCOUNTING INTEGRATION - Record transport fee as revenue
        try {
            $student = Students::find($request->student_id);
            $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();
            $integrationRequest = new \Illuminate\Http\Request([
                'student_id' => $transportation->student_id,
                'fee_amount' => $transportation->monthly_charges,
                'collection_date' => now()->format('Y-m-d'),
                'reference' => 'TRANS-' . $transportation->id . ' - ' . ($student->fullname ?? 'Student') . ' (Monthly)',
            ]);
            $integrationController->recordAcademicFee($integrationRequest);
        } catch (\Exception $e) {
            \Log::error('Transportation fee accounting failed: ' . $e->getMessage());
        }

        return redirect()->route('fleet.transportation.index')
            ->with('success', 'Transportation assignment created successfully.');
    }

    /**
     * Display the specified transportation assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students-transport-view')) {
            return abort(503);
        }
        $transportation = Transportation::with(['student.AcademicClass', 'student.academicSession', 'vehicle', 'route'])
            ->findOrFail($id);
        return view('fleet.transportation.show', compact('transportation'));
    }

    /**
     * Show the form for editing the specified transportation assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students-transport-edit')) {
            return abort(503);
        }
        $transportation = Transportation::with(['student.AcademicClass', 'student.academicSession', 'vehicle', 'route'])
            ->findOrFail($id);
        $vehicles = Vehicle::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();

        return view('fleet.transportation.edit', compact('transportation', 'vehicles', 'routes'));
    }

    /**
     * Update the specified transportation assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students-transport-edit')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'route_id' => 'required|exists:fleet_routes,id',
            'pickup_point' => 'nullable|string|max:255',
            'dropoff_point' => 'nullable|string|max:255',
            'monthly_charges' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Update transportation assignment
        $transportation = Transportation::findOrFail($id);
        $transportation->update([
            'vehicle_id' => $request->vehicle_id,
            'route_id' => $request->route_id,
            'pickup_point' => $request->pickup_point ?? NULL,
            'dropoff_point' => $request->dropoff_point ?? NULL,
            'monthly_charges' => $request->monthly_charges,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('fleet.transportation.index')
            ->with('success', 'Transportation assignment updated successfully.');
    }

    /**
     * Remove the specified transportation assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students-transport-delete')) {
            return abort(503);
        }
        // Delete transportation assignment
        $transportation = Transportation::findOrFail($id);
        $transportation->delete();

        return redirect()->route('fleet.transportation.index')
            ->with('success', 'Transportation assignment deleted successfully.');
    }
     
public function import(Request $request)
{
    $request->validate([
        'import_file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    $import = new TransportExcelImport();

    try {
        Excel::import($import, $request->file('import_file'));

        // Success
        return redirect()->back()->with('success', 'Import completed successfully.');
    } catch (\Exception $e) {
        // The import class throws combined row errors, so show to user via toast
        // Make sure message is safe — in this case it's fine because it's row error text.
        return redirect()->back()->with('error', $e->getMessage());
    }
}

}
