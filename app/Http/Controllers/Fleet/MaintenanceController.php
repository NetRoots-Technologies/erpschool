<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Fleet\Driver;
use App\Models\Fleet\Maintenance;
use App\Models\Fleet\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Fleet-maintenance-list')) {
            return abort(503);
        }
        $maintenances = Maintenance::with(['vehicle', 'driver', 'company', 'branch'])->paginate(10);
        return view('fleet.maintenance.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Fleet-maintenance-create')) {
            return abort(503);
        }
        return view('fleet.maintenance.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    if (!Gate::allows('Fleet-maintenance-create')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'maintenance_type' => 'required|in:regular,scheduled,emergency,repair',
            'maintenance_date' => 'required|date',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'service_provider' => 'nullable|string|max:255',
            'service_provider_phone' => 'nullable|string|max:20',
            'status' => 'required|in:completed,pending,cancelled',
            'notes' => 'nullable|string',
        ]);

        $maintenance = Maintenance::create($request->all());

        // ✅ ACCOUNTING INTEGRATION
        try {
            $vehicle = Vehicle::find($request->vehicle_id);
            $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();
            $integrationRequest = new \Illuminate\Http\Request([
                'vehicle_id' => $maintenance->vehicle_id,
                'expense_amount' => $maintenance->cost,
                'expense_date' => $maintenance->maintenance_date,
                'expense_type' => 'maintenance',
                'reference' => 'MAINT-' . $maintenance->id . ' - ' . ($vehicle->registration_number ?? 'Vehicle'),
            ]);
            $integrationController->recordFleetExpense($integrationRequest);
        } catch (\Exception $e) {
            \Log::error('Maintenance accounting failed: ' . $e->getMessage());
        }

        return redirect()->route('fleet.maintenance.index')
            ->with('success', 'Maintenance record created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fleet\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function show(Maintenance $maintenance)
    {
    if (!Gate::allows('Fleet-maintenance-view')) {
            return abort(503);
        }

        $maintenance->load(['vehicle', 'driver', 'company', 'branch']);
        return view('fleet.maintenance.show', compact('maintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fleet\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Maintenance $maintenance)
    {
    if (!Gate::allows('Fleet-maintenance-edit')) {
            return abort(503);
        }
        return view('fleet.maintenance.edit', compact('maintenance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fleet\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Maintenance $maintenance)
    {
    if (!Gate::allows('Fleet-maintenance-edit')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'maintenance_type' => 'required|in:regular,scheduled,emergency,repair',
            'maintenance_date' => 'required|date',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'service_provider' => 'nullable|string|max:255',
            'service_provider_phone' => 'nullable|string|max:20',
            'status' => 'required|in:completed,pending,cancelled',
            'notes' => 'nullable|string',
        ]);

        $maintenance->update($request->all());

        return redirect()->route('fleet.maintenance.index')
            ->with('success', 'Maintenance record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fleet\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maintenance $maintenance)
    {
    if (!Gate::allows('Fleet-maintenance-delete')) {
            return abort(503);
        }
        $maintenance->delete();

        return redirect()->route('fleet.maintenance.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }
}