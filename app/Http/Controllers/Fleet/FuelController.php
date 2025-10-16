<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Fleet\Fuel;
use App\Models\Fleet\Driver;
use Illuminate\Http\Request;
use App\Models\Fleet\Vehicle;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class FuelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (!Gate::allows('Fule-record-list')) {
            return abort(503);
        }
        $fuels = Fuel::with(['vehicle', 'driver', 'company', 'branch'])->paginate(10);
        return view('fleet.fuel.index', compact('fuels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!Gate::allows('Fule-record-craete')) {
            return abort(503);
        }
        return view('fleet.fuel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if (!Gate::allows('Fule-record-craete')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'fuel_date' => 'required|date',
            'fuel_type' => 'required|in:diesel,petrol,cng,lpg',
            'quantity' => 'required|numeric|min:0',
            'rate_per_liter' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'fuel_station' => 'nullable|string|max:255',
            'odometer_reading' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $fuel = Fuel::create($request->all());

        // ✅ ACCOUNTING INTEGRATION
        try {
            $vehicle = Vehicle::find($request->vehicle_id);
            $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();
            $integrationRequest = new \Illuminate\Http\Request([
                'vehicle_id' => $fuel->vehicle_id,
                'expense_amount' => $fuel->total_cost,
                'expense_date' => $fuel->fuel_date,
                'expense_type' => 'fuel',
                'reference' => 'FUEL-' . $fuel->id . ' - ' . ($vehicle->registration_number ?? 'Vehicle'),
            ]);
            $integrationController->recordFleetExpense($integrationRequest);
        } catch (\Exception $e) {
            \Log::error('Fuel accounting failed: ' . $e->getMessage());
        }

        return redirect()->route('fleet.fuel.index')
            ->with('success', 'Fuel record created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fleet\Fuel  $fuel
     * @return \Illuminate\Http\Response
     */
    public function show(Fuel $fuel)
    {
        if (!Gate::allows('Fule-record-view')) {
            return abort(503);
        }
        $fuel->load(['vehicle', 'driver', 'company', 'branch']);
        return view('fleet.fuel.show', compact('fuel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fleet\Fuel  $fuel
     * @return \Illuminate\Http\Response
     */
    public function edit(Fuel $fuel)
    {
        if (!Gate::allows('Fule-record-edit')) {
            return abort(503);
        }
        return view('fleet.fuel.edit', compact('fuel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fleet\Fuel  $fuel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fuel $fuel)
    {
        if (!Gate::allows('Fule-record-edit')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'fuel_date' => 'required|date',
            'fuel_type' => 'required|in:diesel,petrol,cng,lpg',
            'quantity' => 'required|numeric|min:0',
            'rate_per_liter' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'fuel_station' => 'nullable|string|max:255',
            'odometer_reading' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $fuel->update($request->all());

        return redirect()->route('fleet.fuel.index')
            ->with('success', 'Fuel record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fleet\Fuel  $fuel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fuel $fuel)
    {
        if (!Gate::allows('Fule-record-delete')) {
            return abort(503);
        }
        $fuel->delete();

        return redirect()->route('fleet.fuel.index')
            ->with('success', 'Fuel record deleted successfully.');
    }
}