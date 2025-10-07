<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Type\TypeController;
use App\Http\Controllers\Unit\UnitController;
use App\Http\Controllers\Floor\FloorController;
use App\Http\Controllers\Building\BuildingController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\Maintainer\MaintainerController;


Route::group(['middleware' => ['auth'], 'prefix' => 'maintainer', 'as' => 'maintainer.'], function () {

Route::resource('type', TypeController::class);
Route::post('type/delete/{type}', [TypeController::class,'destroy'])->name('type.destroy');
Route::resource('maintainer' , MaintainerController::class);

Route::get('/building/index' , [BuildingController::class , 'index'])->name('building.index');
Route::get('/building/create' , [BuildingController::class , 'create'])->name('building.create');
Route::post('/building/store' , [BuildingController::class , 'store'])->name('building.store');

Route::get('/building/edit/{id}' , [BuildingController::class , 'edit'])->name('building.edit');
Route::post('/building/update/{id}' , [BuildingController::class , 'update'])->name('building.update');

Route::get('/building/show/{id}' , [BuildingController::class , 'show'])->name('building.show');
Route::post('/building/destroy/{id}' , [BuildingController::class , 'destroy'])->name('building.destroy');
Route::get('get-branch-by-company', [BuildingController::class, 'getBuildingsByCompany'])->name('get.branch');


// Floor Oprations
Route::resource('floor', FloorController::class);
Route::post('/building/floor/store/{building_id}' , [FloorController::class , 'storeFloor'])->name('building.floor.store');

// Unit Oprations
Route::resource('units', UnitController::class);
Route::post('/building/unit/store/{building_id}' , [UnitController::class , 'unitStore'])->name('building.units.store');
Route::get('/building/floor/area/{floor_id}' , [UnitController::class , 'floorArea'])->name('building.floor.area');

});

Route::group(['middleware' => ['web' , 'auth']], function () {
Route::resource('maintenance-request', MaintenanceRequestController::class)->except(['show']);
Route::get('/buildings/{building}/units', [MaintenanceRequestController::class, 'unitsByBuilding'])->name('buildings.units');

Route::get('maintenance-request/pending', [MaintenanceRequestController::class, 'pendingRequest'])->name('maintenance-request.pending');
Route::get('maintenance-request/in-progress', [MaintenanceRequestController::class, 'inProgressRequest'])->name('maintenance-request.inprogress');
Route::get('maintenance-request/approval', [MaintenanceRequestController::class, 'approval'])->name('maintenance-request.approval');
Route::POST('maintenance-request/approval-request/{id}', [MaintenanceRequestController::class, 'ApprovalRequest'])->name('maintenance-request.approvalRequest');
Route::POST('maintenance-request/reject/{id}', [MaintenanceRequestController::class, 'rejectRequest'])->name('maintenance-request.reject');
 Route::post('maintenance-request/{id}/progress', [MaintenanceRequestController::class, 'markInProgress'])
        ->name('maintenance-request.progress');

    Route::post('maintenance-request/{id}/complete', [MaintenanceRequestController::class, 'markCompleted'])
        ->name('maintenance-request.complete');

Route::get('maintenance-request/show/{id}', [MaintenanceRequestController::class, 'Show'])->name('maintenance-request.show');

});
