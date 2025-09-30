<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Fleet\Route;
use App\Models\Fleet\Vehicle;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = Route::with(['vehicle', 'company', 'branch'])->paginate(10);
        return view('fleet.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = Vehicle::where('status', 'active')->get();
        return view('fleet.routes.create', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'route_name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'total_distance' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        Route::create($request->all());

        return redirect()->route('fleet.routes.index')
            ->with('success', 'Route created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fleet\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        $route->load(['vehicle', 'company', 'branch']);
        return view('fleet.routes.show', compact('route'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fleet\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function edit(Route $route)
    {
        $vehicles = Vehicle::where('status', 'active')->get();
        return view('fleet.routes.edit', compact('route', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fleet\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'route_name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'total_distance' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        $route->update($request->all());

        return redirect()->route('fleet.routes.index')
            ->with('success', 'Route updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fleet\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return redirect()->route('fleet.routes.index')
            ->with('success', 'Route deleted successfully.');
    }
}