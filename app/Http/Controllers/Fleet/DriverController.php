<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Fleet\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('drivers-list')) {
            return abort(503);
        }
        $drivers = Driver::with(['company', 'branch'])->paginate(10);
        return view('fleet.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('drivers-create')) {
            return abort(503);
        }
        return view('fleet.drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('drivers-create')) {
            return abort(503);
        }
        $request->validate([
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'required|string|max:20',
            'driver_cnic' => 'required|string|max:20|unique:fleet_drivers',
            'license_number' => 'required|string|max:50|unique:fleet_drivers',
            'license_expiry' => 'required|date|after:today',
            'address' => 'nullable|string',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        Driver::create($request->all());

        return redirect()->route('fleet.drivers.index')
            ->with('success', 'Driver created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fleet\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        if (!Gate::allows('drivers-view')) {
            return abort(503);
        }
        $driver->load(['company', 'branch']);
        return view('fleet.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fleet\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        if (!Gate::allows('drivers-edit')) {
            return abort(503);
        }
        return view('fleet.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fleet\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        if (!Gate::allows('drivers-edit')) {
            return abort(503);
        }
        $request->validate([
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'required|string|max:20',
            'driver_cnic' => 'required|string|max:20|unique:fleet_drivers,driver_cnic,' . $driver->id,
            'license_number' => 'required|string|max:50|unique:fleet_drivers,license_number,' . $driver->id,
            'license_expiry' => 'required|date',
            'address' => 'nullable|string',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $driver->update($request->all());

        return redirect()->route('fleet.drivers.index')
            ->with('success', 'Driver updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fleet\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        if (!Gate::allows('drivers-delete')) {
            return abort(503);
        }
        $driver->delete();

        return redirect()->route('fleet.drivers.index')
            ->with('success', 'Driver deleted successfully.');
    }
}