<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Fleet\DashboardController;
use App\Http\Controllers\Fleet\VehicleController;
use App\Http\Controllers\Fleet\DriverController;
use App\Http\Controllers\Fleet\RouteController;
use App\Http\Controllers\Fleet\MaintenanceController;
use App\Http\Controllers\Fleet\FuelController;
use App\Http\Controllers\Fleet\ExpenseController;
use App\Http\Controllers\Fleet\StudentTransportController;

/*
|--------------------------------------------------------------------------
| Fleet Management Routes
|--------------------------------------------------------------------------
|
| Here are the routes for fleet management system
|
*/

Route::middleware(['auth'])->prefix('fleet')->name('fleet.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Vehicles
    Route::resource('vehicles', VehicleController::class);
    Route::get('vehicles/{vehicle}/maintenance', [VehicleController::class, 'maintenance'])->name('vehicles.maintenance');
    Route::get('vehicles/{vehicle}/fuel', [VehicleController::class, 'fuel'])->name('vehicles.fuel');
    Route::get('vehicles/{vehicle}/expenses', [VehicleController::class, 'expenses'])->name('vehicles.expenses');
    
    // Drivers
    Route::resource('drivers', DriverController::class);
    Route::get('drivers/{driver}/vehicles', [DriverController::class, 'vehicles'])->name('drivers.vehicles');
    
    // Routes
    Route::resource('routes', RouteController::class);
    Route::get('routes/{route}/points', [RouteController::class, 'points'])->name('routes.points');
    Route::post('routes/{route}/points', [RouteController::class, 'storePoint'])->name('routes.points.store');
    Route::put('routes/{route}/points/{point}', [RouteController::class, 'updatePoint'])->name('routes.points.update');
    Route::delete('routes/{route}/points/{point}', [RouteController::class, 'destroyPoint'])->name('routes.points.destroy');
    
    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);
    Route::post('maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    
    // Fuel Records
    Route::resource('fuel', FuelController::class);
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // Student Transportation
    Route::get('transportation', [StudentTransportController::class, 'index'])->name('transportation.index');
    Route::get('transportation/create', [StudentTransportController::class, 'create'])->name('transportation.create');
    Route::post('transportation', [StudentTransportController::class, 'store'])->name('transportation.store');
    Route::get('transportation/{transportation}', [StudentTransportController::class, 'show'])->name('transportation.show');
    Route::get('transportation/{transportation}/edit', [StudentTransportController::class, 'edit'])->name('transportation.edit');
    Route::put('transportation/{transportation}', [StudentTransportController::class, 'update'])->name('transportation.update');
    Route::delete('transportation/{transportation}', [StudentTransportController::class, 'destroy'])->name('transportation.destroy');
    // 🔹 BULK IMPORT (FILE UPLOAD)
    Route::post('transportation/import', [StudentTransportController::class, 'import'])->name('transportation.import');
    
    // AJAX Routes
    Route::get('api/vehicles', [VehicleController::class, 'apiVehicles'])->name('api.vehicles');
    Route::get('api/drivers', [DriverController::class, 'apiDrivers'])->name('api.drivers');
    Route::get('api/routes', [RouteController::class, 'apiRoutes'])->name('api.routes');
    Route::get('api/route-points/{route}', [RouteController::class, 'apiRoutePoints'])->name('api.route-points');
});
