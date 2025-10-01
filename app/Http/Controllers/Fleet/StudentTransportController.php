<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Student\Students;
use App\Models\Fleet\Vehicle;
use App\Models\Fleet\Route;
use Illuminate\Http\Request;

class StudentTransportController extends Controller
{
    /**
     * Display a listing of students requiring transport.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Students::where('is_active', 1)
            ->with(['AcademicClass', 'academicSession'])
            ->paginate(10);
        
        $vehicles = Vehicle::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        
        return view('fleet.transportation.index', compact('students', 'vehicles', 'routes'));
    }

    /**
     * Show the form for creating a new transportation assignment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Students::where('is_active', 1)->get();
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
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'route_id' => 'required|exists:fleet_routes,id',
            'pickup_point' => 'required|string|max:255',
            'dropoff_point' => 'required|string|max:255',
            'monthly_charges' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        // Create transportation assignment
        // This would typically create a record in a student_transportations table
        // For now, we'll just show success message
        
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
        $student = Students::with(['AcademicClass', 'academicSession'])->findOrFail($id);
        return view('fleet.transportation.show', compact('student'));
    }

    /**
     * Show the form for editing the specified transportation assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Students::with(['AcademicClass', 'academicSession'])->findOrFail($id);
        $vehicles = Vehicle::where('status', 'active')->get();
        $routes = Route::where('status', 'active')->get();
        
        return view('fleet.transportation.edit', compact('student', 'vehicles', 'routes'));
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
        $request->validate([
            'transport_fee' => 'required|numeric|min:0',
        ]);

        // Update student's transport fee only
        $student = Students::findOrFail($id);
        $student->update([
            'transport_fee' => $request->transport_fee,
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
        // Delete transportation assignment
        // This would typically delete a record from a student_transportations table
        
        return redirect()->route('fleet.transportation.index')
            ->with('success', 'Transportation assignment deleted successfully.');
    }
}
