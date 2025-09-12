<?php

use App\Http\Controllers\Type\TypeController;
use App\Http\Controllers\Maintainer\MaintainerController;
use App\Http\Controllers\Building\BuildingController;


Route::group(['middleware' => ['auth']], function () {
Route::resource('type', TypeController::class);
Route::post('type/delete/{type}', [TypeController::class,'destroy'])->name('type.destroy');
Route::resource('maintainer' , MaintainerController::class);
// Route::resource('/building' , BuildingController::class);
Route::get('/building/index' , [BuildingController::class , 'index'])->name('building.index');
Route::get('/building/create' , [BuildingController::class , 'create'])->name('building.create');
Route::post('/building/store' , [BuildingController::class , 'store'])->name('building.store');

Route::get('/building/edit/{id}' , [BuildingController::class , 'edit'])->name('building.edit');
Route::post('/building/update/{id}' , [BuildingController::class , 'update'])->name('building.update');

Route::get('/building/show/{id}' , [BuildingController::class , 'show'])->name('building.show');
Route::post('/building/destroy/{id}' , [BuildingController::class , 'destroy'])->name('building.destroy');
Route::get('get-branch-by-company/{id}', [BuildingController::class, 'getBuildingsByCompany']);


// Floor Oprations
Route::post('/building/floor/store/{building_id}' , [BuildingController::class , 'storeFloor'])->name('building.floor.store');

// Building Area
// Route::get('/building/area/{building_id}' , [BuildingController::class , 'getPropertyArea'])->name('building.area');



});
