<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Region\Http\Controllers\RegionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/region', function (Request $request) {
    return $request->user();
});


Route::post('/regions', [RegionController::class, 'fetchTableData']);
Route::middleware(['web','auth'])->prefix('admin/region')->group(function() {
    Route::post('/store',[RegionController::class, 'store'])->name('region.store');
    Route::post('/update',[RegionController::class, 'update'])->name('region.update');
    Route::post('/delete',[RegionController::class, 'destroy'])->name('region.delete');
    Route::get('/export',[RegionController::class, 'export'])->name('region.export');
    Route::get('/{region}/districts',[RegionController::class, 'districts'])->name('region.districts');

});