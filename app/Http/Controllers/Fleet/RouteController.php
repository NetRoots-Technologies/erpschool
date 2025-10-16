<?php

namespace App\Http\Controllers\Fleet;

use App\Models\Fleet\Route;
use Illuminate\Http\Request;
use App\Models\Fleet\Vehicle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          if (!Gate::allows('routes-list')) {
            return abort(503);
        }
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
          if (!Gate::allows('routes-create')) {
            return abort(503);
        }
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
          if (!Gate::allows('routes-create')) {
            return abort(503);
        }
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
          if (!Gate::allows('routes-view')) {
            return abort(503);
        }
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
          if (!Gate::allows('routes-edit')) {
            return abort(503);
        }
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
          if (!Gate::allows('routes-edit')) {
            return abort(503);
        }
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
          if (!Gate::allows('routes-delete')) {
            return abort(503);
        }
        $route->delete();

        return redirect()->route('fleet.routes.index')
            ->with('success', 'Route deleted successfully.');
    }
}