<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Fleet\Driver;
use Illuminate\Http\Request;
use App\Models\Fleet\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
           if (!Gate::allows('vahicals-list')) {
            return abort(503);
        }
        $vehicles = Vehicle::with(['driver', 'company', 'branch'])->paginate(10);
        return view('fleet.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
           if (!Gate::allows('vahicals-create')) {
            return abort(503);
        }
        return view('fleet.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
           if (!Gate::allows('vahicals-create')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:fleet_vehicles',
            'vehicle_type' => 'required|in:bus,van,car,mini_bus',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'capacity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('fleet.vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fleet\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
           if (!Gate::allows('vahicals-view')) {
            return abort(503);
        }
        $vehicle->load(['driver', 'company', 'branch', 'routes']);
        return view('fleet.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fleet\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
           if (!Gate::allows('vahicals-edit')) {
            return abort(503);
        }
        return view('fleet.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fleet\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {   

           if (!Gate::allows('vahicals-edit')) {
            return abort(503);
        }

        $request->validate([
            'vehicle_number' => 'required|string|max:255|unique:fleet_vehicles,vehicle_number,' . $vehicle->id,
            'vehicle_type' => 'required|in:bus,van,car,mini_bus',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'capacity' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,maintenance',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('fleet.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fleet\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {

           if (!Gate::allows('vahicals-delete')) {
            return abort(503);
        }
        $vehicle->delete();

        return redirect()->route('fleet.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}